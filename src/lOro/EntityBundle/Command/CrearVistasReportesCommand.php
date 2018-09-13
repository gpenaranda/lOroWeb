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