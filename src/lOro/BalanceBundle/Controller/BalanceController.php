<?php

namespace lOro\BalanceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use lOro\BalanceBundle\Form\GenerarBalanceType;
use lOro\EntityBundle\Entity\BalancesGanancia;
use Symfony\Component\HttpFoundation\JsonResponse;

class BalanceController extends Controller
{
    public function indexAction(Request $request)
    {
      ini_set('memory_limit', '2048M');
      $em = $this->getDoctrine()->getManager();
      $form = $this->crearGenerarBalanceForm();
      $mostrar = false;
      $totalOroMeDeben = 0;
      $totalBolivaresDebo = 0;
      $transAdeudadas = 0;
      $creditoHc = 0;
      $data['balanceAcumuladoBolivares'] = 0;
      $data['creditosBolivaresMes'] = 0;
      $data['debitosBolivaresMes'] = 0;
      $data['balanceAcumuladoDolares'] = 0;
      $data['creditosDolaresMes'] = 0;
      $data['debitosDolaresMes'] = 0;
      $data['totalValorOroEnDolares'] = 0;
      $data['bolivaresEnDolaresDebo'] = 0;
      
      
      /** Valor de la Onza troy **/
      $onzaTroyGramos = $em->getRepository('lOroEntityBundle:Parametros')->find(1);      
      
      /** Margen de Ganancia **/
      $ganancia = $em->getRepository('lOroEntityBundle:MargenesGanancias')->findOneBy(array('nbMargenGanancia' => 'venta','estatus' => 'A'));
        
      /* Servicio para sacar el promedio de los dolares de referencia */
      $verdesTransferidos = $em->getRepository('lOroEntityBundle:Balances')->calcularDolaresTransferidosAupanasAnual();
           
      $valorOnzaTroy = $onzaTroyGramos->getValorParametro();
      $valorMargenGanancia = $ganancia->getTipoMargen();
      
      
      
      /* Servicio para sacar el promedio de los dolares de referencia */
      $promDolarReferencia = $this->get('loro_datos_generales')->generarPromDolaresReferencia(2);
      
      $form->get('dolarReferencia')->setData($promDolarReferencia);
      
      if($request->isMethod('POST')):
        $form->handleRequest($request);
        $valorOnza = $form->get('valorOnza')->getData();
        $dolarReferencia = $form->get('dolarReferencia')->getData();
       // $creditoHc = $form->get('creditoHc')->getData();
        
        if ($form->isValid()):
          $mostrar = true;
          
      $balanceActivo = $em->getRepository('lOroEntityBundle:Balances')->findOneBy(array('estatus' => 'A'));
      $arregloProveedores = null;
     
      


        
        $data['promDolarReferencia'] = $dolarReferencia;
        
        
        
        $gramosQueDeboEnDolares = $em->getRepository('lOroEntityBundle:Balances')->gramosQueDeboEnDolares();
        
        $data['gramosQueDeboEnDolares'] = $gramosQueDeboEnDolares['gramos_en_dolares_que_debo'];
        
  
      
      if($balanceActivo):
          

        /** Entregas realizadas en el balance activo **/
        $entregas = $em->getRepository('lOroEntityBundle:Entregas')->findBy(array('balance' => $balanceActivo));
     
        /** Cierres realizados con los proveedores en el balance activo **/
        $cierresProveedor = $em->getRepository('lOroEntityBundle:VentasCierres')->findBy(array('balance' => $balanceActivo, 
                                                                                             'tipoCierre' => 'proveedor'));
        $arregloProveedores = $this->get('loro_datos_generales')->generarArregloSumatoriasPorProveedores($balanceActivo,$cierresProveedor,$entregas);
        
        $data['arregloProveedores'] = $arregloProveedores;
        
        foreach($arregloProveedores as $row):
          $totalOroMeDeben += $row['totalOro'];
          $totalBolivaresDebo += $row['bolivares'];
        endforeach;
      endif;

         $valorGramoEnDolares = ($valorOnza / $onzaTroyGramos->getValorParametro()) * $ganancia->getTipoMargen();
         $totalValorOroEnDolares = $totalOroMeDeben * $valorGramoEnDolares;
         
         $data['valorGramoEnDolares'] = $valorGramoEnDolares;
         $data['totalValorOroEnDolares'] = $totalValorOroEnDolares;
         $data['bolivaresEnDolaresDebo'] = ($totalBolivaresDebo / $this->get('loro_datos_generales')->generarPromDolaresReferencia(2));
        endif;
        
      endif;


        
      $data['form'] = $form->createView();
      $data['mostrar'] = $mostrar;
      
      //$data['creditoHc'] = $creditoHc;
      $data['valorOnza'] = (isset($valorOnza) ? $valorOnza : 0);
      $data['totalOroMeDeben'] = $totalOroMeDeben;
      $data['totalBolivaresDebo'] = $totalBolivaresDebo;
      $data['verdesTransferidosHcAnioCurso'] = $verdesTransferidos['dolares_transferidos_hc'];
      $data['promDolarReferencia'] = $this->get('loro_datos_generales')->generarPromDolaresReferencia(2); 


      
     /* Llamado a la funcion para el acumulado dolares y bolivares en el sistema
      * (Se sacan el acumulado del sistema y del mes y se insertan en el arreglo $data)
      */        
      $this->relacionBolivaresDolares($em,$data);  
      
      $this->relacionGramosEntregadosCerrados($em, $data);
      

      $balanceHc = ($data['balanceAcumuladoGramosHc'] + ($data['gramosCerradosHcMes'] - $data['gramosEntregadosMes']));
      $balanceProveedores = ($data['balanceAcumuladoGramosProveedores'] + ($data['gramosCerradosProveedoresMes'] - $data['gramosEntregadosMes']));
      
      $gramosAdeudadosAupanas = $balanceHc;
      $gramosNoCerrados = $balanceHc - $balanceProveedores;
      
      $data['balanceHc'] = $balanceHc;
      $data['balanceProveedores'] = $balanceProveedores;
      $data['gramosNoCerrados'] = $gramosNoCerrados;
      
      
      $cierresAupanas = $em->getRepository('lOroEntityBundle:VentasCierres')->findBy(array('tipoCierre' => 'hc'),array('id' => 'DESC'));
      
      $sumDolares = 0;
      $sumCierres = 0;
      $arregloMatPendiente = array();
      foreach($cierresAupanas as $row):
        $sumCierres += $row->getCantidadTotalVenta();
        
        if($sumCierres >= $gramosAdeudadosAupanas):
          $difUltimoCierre = ($sumCierres - $gramosAdeudadosAupanas);
          
          $montoUltimoCierre = $row->getCantidadTotalVenta() - $difUltimoCierre;
          $dolaresMatPendiente = ((($row->getValorOnza() / $onzaTroyGramos->getValorParametro()) * $ganancia->getTipoMargen()) * $montoUltimoCierre);
          
          $sumDolares += $dolaresMatPendiente;

            $datosMatPendiente['feVenta'] = $row->getFeVenta();
            $datosMatPendiente['cantidadTotalVenta'] = $montoUltimoCierre;
            $datosMatPendiente['valorOnza'] = $row->getValorOnza();
            $datosMatPendiente['montoTotalDolar'] = $dolaresMatPendiente;
            $arregloMatPendiente[] = $datosMatPendiente;
          break;
        endif;
        
        $dolaresMatPendiente = ((($row->getValorOnza() / $onzaTroyGramos->getValorParametro()) * $ganancia->getTipoMargen()) * $row->getCantidadTotalVenta());
        $sumDolares += $dolaresMatPendiente;
        
        $datosMatPendiente['feVenta'] = $row->getFeVenta();
        $datosMatPendiente['cantidadTotalVenta'] = $row->getCantidadTotalVenta();
        $datosMatPendiente['valorOnza'] = $row->getValorOnza();
        $datosMatPendiente['montoTotalDolar'] = $row->getMontoTotalDolar();
        
        $arregloMatPendiente[] = $datosMatPendiente;
      endforeach; 
      

        
      $noEnviadoHc = $em->getRepository('lOroEntityBundle:Transferencias')->findBy(array('estatus' => 'N'));
      
      $totalTransferenciasPendientes = 0;
      if($noEnviadoHc):
        foreach($noEnviadoHc as $row):
          $totalTransferenciasPendientes += $row->getMontoTransferencia();  
        endforeach;    
      endif;
      
      /* Credito de Comprador - Berty */
      $creditoDolaresComprador = $em->getRepository('lOroEntityBundle:VentasDolares')->getCreditoDolaresComprador(15);
      
      $totalTransferenciasPendientes = ($totalTransferenciasPendientes + $creditoDolaresComprador['total_credito_comprador']);
      
      $creditoHc = $em->getRepository('lOroEntityBundle:Balances')->getBalanceCreditoHc();
      
      $montoTotalCreditoHc = $creditoHc['dolares_por_material_entregado'] - $creditoHc['total_monto_de_transferencias'];
      
      $data['totalTransferenciasPendientes'] = $totalTransferenciasPendientes;
      $data['creditoHc'] = $montoTotalCreditoHc;
      $data['dolaresMatPendienteAupanas'] = $sumDolares;
      $data['gramosAdudadosAupanas'] = $gramosAdeudadosAupanas;
      $data['arregloMatPendiente'] = $arregloMatPendiente;
      
      
      
      if($request->isMethod('POST')):
        if ($form->isValid() && $request->request->get('submit_guardar_balance')):   
          $arregloMaterialNoCerrado['balanceHc'] = $balanceHc;
          $arregloMaterialNoCerrado['balanceProveedores'] = $balanceProveedores;
          $arregloMaterialNoCerrado['gramosNoCerrados'] = $gramosNoCerrados;
        
          $balancesGanancia = new BalancesGanancia();
          $balancesGanancia->setDolarReferencia($dolarReferencia);
          $balancesGanancia->setValorOnza($valorOnza);
          $balancesGanancia->setFeBalance(new \DateTime('now'));
          $balancesGanancia->setMaterialPorEntregarHc($arregloMatPendiente); 
          $balancesGanancia->setMaterialNoCerrado($arregloMaterialNoCerrado);
          $balancesGanancia->setDeboProveedores($arregloProveedores);
        
          $balancesGanancia->setBolivaresCaja($data['balanceTotalBolivares']);
          $balancesGanancia->setTransferenciasPendientes($totalTransferenciasPendientes);
          $balancesGanancia->setCreditoHc($montoTotalCreditoHc);
                
          $em->persist($balancesGanancia);
          $em->flush();
          
          $this->get('session')->getFlashBag()->set('success', 'El Balance N° '.$balancesGanancia->getId().' ha sido generado de manera satisfactoria.');    
          return $this->redirect($this->generateUrl('balance'));
        endif;
      endif;
        
      
      return $this->render('lOroBalanceBundle:Balance:index.html.twig',$data);
    }
    
    public function conciliacionCajaIndexAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $form = $this->crearGenerarBalanceForm();
      
      $vConciliacionCajaBolivares = $em->getRepository('lOroEntityBundle:Balances')->findVConciliacionCajaPagosVarios();
      
      
      $data['vConciliacionCajaBolivares'] = $vConciliacionCajaBolivares;
      return $this->render('lOroBalanceBundle:ConciliacionCaja:index.html.twig',$data);
    }
        
    public function ajaxConciliacionCajaAction() {
      $em = $this->getDoctrine()->getManager();
      $transacciones = $_POST['transacciones'];
      
      foreach($transacciones as $row):
        $transaccion = explode("|", $row);
        $em->getRepository('lOroEntityBundle:Balances')->guardarTransaccionesConciliadas($transaccion[0],$transaccion[1]);
      endforeach;
      
      $this->get('session')->getFlashBag()->set('success', 'Se reaizado la conciliacion de las transacciones de manera satisfactoria');    
        
      
      return new JsonResponse("Exito");
    }
    
    /**
     * Funcion que permite realizar la relación de los Gramos entregados con los
     * gramos cerrados (HC y Proveedores) para el balance acumulado y ademas genera
     * los gramos cerrados y entregados del Mes en Curso
     * 
     * @author Gabriel E. Peñaranda G. <gabriel.e.p.gonzalez@gmail.com>
     * 
     * @param object $em  - Objeto utilizado por Symfony para el manejo de las Entidades
     * @param array $data - Arreglo que posee los parametros que seran pasados a la vista
     * 
     */
    protected function relacionGramosEntregadosCerrados($em,&$data) {
      $fechaActual = new \ DateTime('now');
      
      $cantidadGramosEntregadosAcumulado = 0.00;
      $cantidadGramosCerradosProveedores = 0.00;
      $cantidadGramosCerradosHc = 0.00;
      $gramosEntregadosMes = 0.00;
      $gramosCerradosProveedoresMes = 0.00;
      $gramosCerradosHcMes = 0.00;
      
      $buscarGramosCerradosAnuales = $em->getRepository('lOroEntityBundle:Balances')->findGramosCierresEntregasAnuales();
      
      if($buscarGramosCerradosAnuales):
        foreach($buscarGramosCerradosAnuales as $row):
          
          /* Si el Año de la Venta y el Mes es igual a la Fecha Actual (osea 
           * el mes en curso), se sacan los gramos del Mes (Entregados,Proveedor y HC)
           */
          if($row['anio_venta'] == $fechaActual->format('Y') && $row['mes_venta'] == $fechaActual->format('m')):
            $gramosEntregadosMes = $row['gramos_entregados'];
            $gramosCerradosProveedoresMes = $row['gramos_cerrados_proveedor'];
            $gramosCerradosHcMes = $row['gramos_cerrados_hc'];
            
          /* Si no significa que es un monto que va para el acumulado del sistema */
          else:
            $cantidadGramosEntregadosAcumulado += (float) $row['gramos_entregados']; 
            $cantidadGramosCerradosProveedores += (float) $row['gramos_cerrados_proveedor'];
            $cantidadGramosCerradosHc += (float) $row['gramos_cerrados_hc'];
          endif;
        endforeach;
      endif;
      
      $adeudadoGuillen = 0; //7710.80;
      $deudaOficina = 4533; //2.266,5; // La deuda es de 1340.71 pero se mult por dos para ponerlo en positivo
      $deudaCarmela = 0; //2681.42; // La deuda es de 1340.71 pero se mult por dos para ponerlo en positivo
      $deudaJoseito = 1985.56; // La deuda es de 992.78 pero se mult por dos para ponerlo en positivo (El dif se calculo 
                               // 33.35 $ (calculo al .95) por 160k$ que se le enviaron a stefano global
                               // 32.59 $ (calculo al .93) por 50k$ que se le enviaron a Joseito
      $difSistemaViejo = 23933.54; //24342.14;
      
      $data['balanceAcumuladoGramosProveedores'] = ($deudaJoseito + $deudaCarmela + $deudaOficina + ($cantidadGramosCerradosProveedores - $cantidadGramosEntregadosAcumulado) - $adeudadoGuillen);
      $data['balanceAcumuladoGramosHc'] = (($difSistemaViejo) + ($cantidadGramosCerradosHc - $cantidadGramosEntregadosAcumulado));
      $data['gramosEntregadosMes'] = $gramosEntregadosMes;
      $data['gramosCerradosProveedoresMes'] = $gramosCerradosProveedoresMes;
      $data['gramosCerradosHcMes'] = $gramosCerradosHcMes;
    }
    
    

    
    
    public function crearGenerarBalanceForm() {
      $form = $this->createForm(new GenerarBalanceType(), null, array(
            'action' => $this->generateUrl('balance'),
            'method' => 'POST',
            'attr' => array('id' => 'generar-balance-form')
      ));

        
      $form->add('submit', 'submit', array('label' => 'Agregar',
                                             'attr' => array('class' =>'btn btn-success')));
      return $form;        
    }
    
    
    
    
    /**
     * Funcion que permite realizar la relación de los Dolares y Bolivares por
     * los diferentes conceptos que se manejan en el sistema para generar el balance
     * general acumulado y mensual (Restas y Sumas en Dolares y Bolivares)
     * 
     * @author Gabriel E. Peñaranda G. <gabriel.e.p.gonzalez@gmail.com>
     * 
     * @param object $em  - Objeto utilizado por Symfony para el manejo de las Entidades
     * @param array $data - Arreglo que posee los parametros que seran pasados a la vista
     */
    protected function relacionBolivaresDolares($em,&$data) {
      $fechaActual = new \ DateTime('now');
            
      $pagoProveedoresBolivaresMes = 0.00;
      $pagoProveedoresDolaresMes = 0.00;
      $pagoProveedoresBolivaresAcumulado = 0.00;
      $pagoProveedoresDolaresAcumulado = 0.00;
      $pagoVariosBolivaresMes = 0.00;
      $pagoVariosDolaresMes = 0.00;
      $pagoVariosBolivaresAcumulado = 0.00;
      $pagoVariosDolaresAcumulado = 0.00;
      $dolaresVendidosMes = 0.00;
      $bolivaresGeneradosVentasDolaresMes = 0.00;
      $dolaresVendidosAcumulado = 0.00;
      $bolivaresGeneradosVentasAcumulado = 0.00;
      $abonosBolivaresMes = 0.00;
      $abonosDolaresMes = 0.00;
      $abonosBolivaresAcumulado = 0.00;
      $abonosDolaresAcumulado = 0.00;
      $transferenciasDolaresMes = 0.00;
      $transferenciasDolaresAcumulado = 0.00;
      $dolaresRelacionEntregasCierresHcMes = 0.00;
      $dolaresRelacionEntregasCierresHcAcumulado = 0.00;     
    
      $debitosVariosDolaresMes = 0;
      $debitosVariosBolivaresMes= 0;
      $debitosVariosBolivaresAcumulado= 0;
      $debitosVariosDolaresAcumulado= 0;      
      
              
      /* PAGOS A PROVEEDORES */
      $balancePagosProveedoresMesAnio = $em->getRepository('lOroEntityBundle:Balances')->findBalancePagosProveedoresMesAnio();

        
      if($balancePagosProveedoresMesAnio):
          
        /* Resta Bolivares (tipo_pago = B) y Resta Dolares (tipo_pago = V) */
        foreach($balancePagosProveedoresMesAnio as $row):
          
          /* Si el Año de la Venta y el Mes es igual a la Fecha Actual (osea 
           * el mes en curso)
           */
          if($row['anio'] == $fechaActual->format('Y') && $row['mes'] == $fechaActual->format('m')):
            /* Si el tipo_pago = B es una Resta de Bolivares */
            if($row['tipo_pago'] == 'B'):
              $pagoProveedoresBolivaresMes =  (float) $row['monto_pagado'];
            /* Si no es una Resta de Dolares */
            else:
              $pagoProveedoresDolaresMes = (float) $row['monto_pagado'];
            endif; 
          else:
            /* Si el tipo_pago = B es una Resta de Bolivares */
            if($row['tipo_pago'] == 'B'):
              $pagoProveedoresBolivaresAcumulado += (float) $row['monto_pagado'];
            
            /* Si no es una Resta de Dolares */
            else:
              $pagoProveedoresDolaresAcumulado += (float) $row['monto_pagado'];
            endif;               
          endif;
          
        endforeach;
      endif;      
      /* PAGOS A PROVEEDORES */
      
      
      /* PAGOS VARIOS */
      $balancePagosVariosMesAnio = $em->getRepository('lOroEntityBundle:Balances')->findBalancePagosVariosMesAnio();
      
      if($balancePagosVariosMesAnio):
          
        /* Resta Bolivares (tipo_pago = B) y Resta Dolares (tipo_pago = V) */
        foreach($balancePagosVariosMesAnio as $row):
          
          /* Si el Año de la Venta y el Mes es igual a la Fecha Actual (osea 
           * el mes en curso) */
          if($row['anio'] == $fechaActual->format('Y') && $row['mes'] == $fechaActual->format('m')):
            /* Si el tipo_pago = B es una Resta de Bolivares */
            if($row['tipo_pago'] == 'B'):
              $pagoVariosBolivaresMes =  (float) $row['monto_pagado'];
            /* Si no es una Resta de Dolares */
            else:
              $pagoVariosDolaresMes = (float) $row['monto_pagado'];
            endif; 
          else:
            /* Si el tipo_pago = B es una Resta de Bolivares */
            if($row['tipo_pago'] == 'B'):
              $pagoVariosBolivaresAcumulado += (float) $row['monto_pagado'];
            
            /* Si no es una Resta de Dolares */
            else:
              $pagoVariosDolaresAcumulado += (float) $row['monto_pagado'];
            endif;               
          endif;
          
        endforeach;
      endif;      
      /* PAGOS VARIOS */
      
      /* VENTAS DE DOLARES */
      $balanceVentasDolaresMesAnio = $em->getRepository('lOroEntityBundle:Balances')->findBalanceVentasDolaresMesAnio();
      
      if($balanceVentasDolaresMesAnio):
          
        /* Resta Dolares (dolares_vendidos) y Suman Bolivares (bolivares_generados) */
        foreach($balanceVentasDolaresMesAnio as $row):
          
          /* Si el Año de la Venta y el Mes es igual a la Fecha Actual (osea 
           * el mes en curso) */
          if($row['anio'] == $fechaActual->format('Y') && $row['mes'] == $fechaActual->format('m')):
            $dolaresVendidosMes =  (float) $row['dolares_vendidos'];
            $bolivaresGeneradosVentasDolaresMes = (float) $row['bolivares_generados'];
          else:
            $dolaresVendidosAcumulado += (float) $row['dolares_vendidos'];
            $bolivaresGeneradosVentasAcumulado += (float) $row['bolivares_generados'];
          endif;
          
        endforeach;
      endif;   
      /* VENTAS DE DOLARES */      

      /* ABONOS */
      $balanceAbonosMesAnio = $em->getRepository('lOroEntityBundle:Balances')->findBalanceAbonosMesAnio();
      
      if($balanceAbonosMesAnio):
          
        /* Suman Dolares (tipo_pago = V) y Suman Bolivares (tipo_pago = B) */
        foreach($balanceAbonosMesAnio as $row):
          
          /* Si el Año de la Venta y el Mes es igual a la Fecha Actual (osea 
           * el mes en curso) */
          if($row['anio'] == $fechaActual->format('Y') && $row['mes'] == $fechaActual->format('m')):
            /* Si el tipo_pago = B es una Suma de Bolivares */
            if($row['tipo_pago'] == 'B'):
              $abonosBolivaresMes = (float) $row['monto_abonado'];
            
            /* Si no es una Resta de Dolares */
            else:
              $abonosDolaresMes = (float) $row['monto_abonado'];
            endif; 
          else:
            /* Si el tipo_pago = B es una Resta de Bolivares */
            if($row['tipo_pago'] == 'B'):
              $abonosBolivaresAcumulado += (float) $row['monto_abonado'];
            
            /* Si no es una Resta de Dolares */
            else:
              $abonosDolaresAcumulado += (float) $row['monto_abonado'];
            endif; 
          endif;
          
        endforeach;
      endif;      
      /* ABONOS */
      
      /* TRANSFERENCIAS DOLARES CONFIRMADAS */
      $balanceTransferenciasConfirmadasMesAnio = $em->getRepository('lOroEntityBundle:Balances')->findBalanceTransferenciasConfirmadasMesAnio();
      
      if($balanceTransferenciasConfirmadasMesAnio):
          
        /* Resta Dolares (monto_transferencias) */
        foreach($balanceTransferenciasConfirmadasMesAnio as $row):
          
          /* Si el Año de la Venta y el Mes es igual a la Fecha Actual (osea 
           * el mes en curso) */
          if($row['anio'] == $fechaActual->format('Y') && $row['mes'] == $fechaActual->format('m')):
            $transferenciasDolaresMes =  (float) $row['monto_transferencias'];
          else:
            $transferenciasDolaresAcumulado += (float) $row['monto_transferencias'];
          endif;
          
        endforeach;
      endif;        
      /* TRANSFERENCIAS DOLARES CONFIRMADAS */
      
      /* DOLARES GENERADOS RELACION ENTREGAS - CIERRES HC */
      $balanceDolaresGeneradosRelacionEntregasCierresHcMesAnio = $em->getRepository('lOroEntityBundle:Balances')->findBalanceDolaresGeneradosRelacionEntregasCierresHcMesAnio();
      
      if($balanceDolaresGeneradosRelacionEntregasCierresHcMesAnio):
          
        /* Suma Dolares (monto) */
        foreach($balanceDolaresGeneradosRelacionEntregasCierresHcMesAnio as $row):
          
          /* Si el Año de la Venta y el Mes es igual a la Fecha Actual (osea 
           * el mes en curso) */
          if($row['anio'] == $fechaActual->format('Y') && $row['mes'] == $fechaActual->format('m')):
            $dolaresRelacionEntregasCierresHcMes =  (float) $row['monto'];
          else:
            $dolaresRelacionEntregasCierresHcAcumulado += (float) $row['monto'];
          endif;
          
        endforeach;
      endif;        
      /* DOLARES GENERADOS RELACION ENTREGAS - CIERRES HC */
      
      
      
/* DEBITOS VARIOS */
      $balanceDebitosMesAnio = $em->getRepository('lOroEntityBundle:Balances')->findBalanceDebitosMesAnio();
      
      if($balanceDebitosMesAnio):
          
        /* Restan Euros (tipo_pago = E) y Restan Bolivares (tipo_pago = B) */
        foreach($balanceDebitosMesAnio as $row):
          
          /* Si el Año de la Venta y el Mes es igual a la Fecha Actual (osea 
           * el mes en curso) */
          if($row['anio'] == $fechaActual->format('Y') && $row['mes'] == $fechaActual->format('m')):
            /* Si el tipo_pago = B es una Suma de Bolivares */
            if($row['tipo_pago'] == 'B'):
              $debitosVariosBolivaresMes = (float) $row['monto_debitado'];
            
            /* Si no es una Resta de Euros */
            else:
              $debitosVariosDolaresMes = (float) $row['monto_debitado'];
            endif; 
          else:
            /* Si el tipo_pago = B es una Resta de Bolivares */
            if($row['tipo_pago'] == 'B'):
              $debitosVariosBolivaresAcumulado += (float) $row['monto_debitado'];
            
            /* Si no es una Resta de Dolares */
            else:
              $debitosVariosDolaresAcumulado += (float) $row['monto_debitado'];
            endif; 
          endif;
          
        endforeach;
      endif;      
      /* DEBITOS */       
      
      
      /* Formula para los debitos y creditos del Mes en Bolivares 
       * (RESTAN - $pagoProveedoresBolivaresMes, $pagoVariosBolivaresMes) 
       * (SUMAN - $bolivaresGeneradosVentasDolaresMes,$abonosBolivaresMes)
       */
      $creditosBolivaresMes = ($bolivaresGeneradosVentasDolaresMes + $abonosBolivaresMes);
      $debitosBolivaresMes = ($pagoProveedoresBolivaresMes + $pagoVariosBolivaresMes);
      
      /* Formula para los debitos y creditos del Mes en Dolares 
       * (RESTAN - $pagoProveedoresDolaresMes, $pagoVariosDolaresMes,$dolaresVendidosMes,$transferenciasDolaresMes)
       * (SUMAN - $abonosDolaresMes,$dolaresRelacionEntregasCierresHcMes)
       */
      $creditosDolaresMes = ($abonosDolaresMes + $dolaresRelacionEntregasCierresHcMes);
      $debitosDolaresMes = ($pagoProveedoresDolaresMes + $pagoVariosDolaresMes + $dolaresVendidosMes + $transferenciasDolaresMes);      
      
      
      
      /* Formula para el Acumulado del Sistema en Bolivares
       * (RESTAN - 10k que balancean para que sea igual al de jenny, $pagoProveedoresBolivaresAcumulado,$pagoVariosBolivaresAcumulado)
       * (SUMAN - $bolivaresGeneradosVentasAcumulado,$abonosBolivaresAcumulado)
       */
      $balanceInicialCambioDolaresAEuros = -17564159.26; // Balance al 27 de Abril de 2015
      $balanceAcumuladoBolivares = ($balanceInicialCambioDolaresAEuros + (($abonosBolivaresAcumulado + $bolivaresGeneradosVentasAcumulado) - ($pagoProveedoresBolivaresAcumulado + $pagoVariosBolivaresAcumulado + $debitosVariosBolivaresAcumulado)));

      
      /* Formula para el Acumulado del Sistema en Dolares
       * (RESTAN - $pagoProveedoresDolaresAcumulado,$pagoVariosDolaresAcumulado,$dolaresVendidosAcumulado,$transferenciasDolaresAcumulado)
       * (SUMAN - $abonosDolaresAcumulado,$dolaresRelacionEntregasCierresHcAcumulado)
       */
      $balanceAcumuladoDolares = (($abonosDolaresAcumulado + $dolaresRelacionEntregasCierresHcAcumulado) - ($pagoProveedoresDolaresAcumulado + $pagoVariosDolaresAcumulado + $dolaresVendidosAcumulado + $transferenciasDolaresAcumulado));      
      
      $balanceTotalBolivares = ($balanceAcumuladoBolivares + ($creditosBolivaresMes - $debitosBolivaresMes));
      
      $data['balanceTotalBolivares'] = $balanceTotalBolivares;
      $data['balanceAcumuladoBolivares'] = $balanceAcumuladoBolivares;
      $data['creditosBolivaresMes'] = $creditosBolivaresMes;
      $data['debitosBolivaresMes'] = $debitosBolivaresMes;
      $data['balanceAcumuladoDolares'] = $balanceAcumuladoDolares;
      $data['creditosDolaresMes'] = $creditosDolaresMes;
      $data['debitosDolaresMes'] = $debitosDolaresMes;
    }    
    
}

