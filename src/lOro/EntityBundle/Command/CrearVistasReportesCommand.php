<?php

namespace lOro\EntityBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CrearVistasReportesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('textileslolo:crear-vistas')
            ->setDescription('Crear las Vistas en la Base de Datos utilizadas para el Sistema lOroWeb')               
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $connection = $em->getConnection();

        $output->writeln('<comment>Iniciando el registro vistas del Sistema en la DB ...</comment>');
      
      
        $vistas = $this->vistasDelSistema();
        foreach($vistas as $vista):
             $output->writeln('<info>Eliminando la vista si existe: </info> <comment>'.$vista['nombreVista'].'</comment>');
             $eliminarVista = $this->eliminarVista($vista['nombreVista']);
             
             $output->writeln('<info>Creando la vista </info> <comment>'.$vista['nombreVista'].'</comment>');
             $crearVista = $this->crearVista($vista['nombreVista'],$vista['sqlCreacion']);
        endforeach;

        $output->writeln('<info>Creacion de vistas finalizado ...</info>');
    }
    
    protected function crearVistasDelSistema($output) {
    }

    protected function vistasDelSistema() {

        /************************** BALANCE ABONOS POR MES Y ANIO    *******************/
        $datosVistas['nombreVista'] = 'balance_abonos_mes_anio';
        $datosVistas['sqlCreacion'] = "CREATE VIEW balance_abonos_mes_anio AS (
                                        SELECT  YEAR(a.fe_abono) AS anio,
                                                MONTH(a.fe_abono) AS mes,
                                                a.tipo_pago AS tipo_pago,
                                                SUM(a.monto) AS monto_abonado
                                        FROM abonos AS a
                                        GROUP BY MONTH(a.fe_abono ),
                                                 YEAR(a.fe_abono ),
                                                 a.tipo_pago
                                        );";

        

        $arregloVistas[] = $datosVistas;
        /*******************************************************************************/

        /**************************  CONCILIACION  DE CAJA BOLIVARES   *******************/
        $datosVistas['nombreVista'] = 'v_conciliacion_caja_bolivares';
        $datosVistas['sqlCreacion'] = "CREATE VIEW v_conciliacion_caja_bolivares AS 
                                       (
                                        SELECT pm.id AS id_transaccion,
                                            'pagos_minoristas' AS tipo_transaccion,
                                                pm.fe_pago AS fecha,
                                                concat(p.nb_proveedor,' - ',epm.nombre_empresa) AS descripcion,
                                                0 AS credito,
                                                pm.monto_pagado AS debito 
                                        FROM pagos_minoristas AS pm 
                                        JOIN empresas_proveedores AS epm ON (epm.id = pm.empresa_proveedor_id) 
                                        JOIN proveedores AS p ON (p.id = epm.proveedor_id) 
                                        WHERE pm.conciliado_en_caja = 0
                                        ) 
                                        UNION 
                                        (
                                        SELECT vd.id AS id_transaccion,
                                            'ventas_dolares' AS tipo_transaccion,
                                            vd.fecha_venta AS fecha,
                                            concat_ws(' - ',concat_ws(' X ',concat_ws('',replace(replace(replace(format((vd.cantidad_dolares_comprados / 1000),0),'.','@'),',','.'),'@',','),'K'),vd.dolar_referencia),p.nb_proveedor) AS descripcion,
                                            vd.monto_venta_bolivares AS credito,
                                            0 AS debito 
                                        FROM ventas_dolares vd 
                                        LEFT JOIN proveedores p ON (p.id = vd.comprador_id)
                                        WHERE vd.conciliado_en_caja = 0
                                        )
                                        UNION 
                                        (
                                        SELECT pv.id AS id_transaccion,
                                            'pagos_varios' AS tipo_transaccion,
                                            pv.fe_pago AS fecha,
                                            pv.descripcion_pago AS descripcion,
                                            0 AS credito,
                                            pv.monto_pago AS debito 
                                        FROM pagos_varios pv 
                                        WHERE pv.conciliado_en_caja = 0
                                        ) 
                                        UNION 
                                        (
                                        SELECT pp.id AS id_transaccion,
                                            'pagos_proveedores' AS tipo_transaccion,
                                            pp.fe_pago AS fecha,
                                            concat_ws(' - ',p.nb_proveedor,ep.nombre_empresa) AS descripcion,
                                            0 AS credito,
                                            pp.monto_pagado AS debito 
                                        FROM pagos_proveedores AS pp 
                                        LEFT JOIN empresas_proveedores ep ON (ep.id = pp.empresa_proveedor_id)
                                        LEFT JOIN proveedores p ON (p.id = ep.proveedor_id)
                                        WHERE pp.conciliado_en_caja = 0
                                        )
                                        UNION 
                                        (
                                        SELECT a.id AS id_transaccion,
                                            'abonos' AS tipo_transaccion,
                                                a.fe_abono AS fecha,
                                            a.descripcion AS descripcion,
                                            a.monto AS credito,
                                            0 AS debito 
                                        FROM abonos AS a 
                                        WHERE (a.conciliado_en_caja = 0) 
                                        )
                                        UNION
                                        ( 
                                        SELECT d.id AS id_transaccion,
                                        'debitos' AS tipo_transaccion,
                                        d.fe_debito AS fecha,
                                        d.descripcion AS descripcion,
                                        0 AS credito,
                                        d.monto AS debito 
                                        FROM debitos AS d 
                                        WHERE d.conciliado_en_caja = 0
                                        );";

        

        $arregloVistas[] = $datosVistas;
        /*******************************************************************************/

        return $arregloVistas;
    }

    protected function eliminarVista($nombreVista) {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $connection = $em->getConnection();

        $statement = $connection->prepare("DROP VIEW IF EXISTS $nombreVista");
        $statement->execute();

        $resultado = $statement->rowCount();

        return $resultado;
    }

    protected function crearVista($nombreVista,$sql) {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $connection = $em->getConnection();

        $statement = $connection->prepare($sql);
        $statement->execute();

        $resultado = $statement->rowCount();

        return $resultado;
    }
}