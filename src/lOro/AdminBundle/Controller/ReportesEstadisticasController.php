<?php

namespace lOro\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use lOro\AdminBundle\Form\SeleccionarProveedorType;
use lOro\AdminBundle\Form\SeleccionarFechasPorProveedorType;
use lOro\AdminBundle\Form\PagosProveedoresType;
use lOro\AdminBundle\Form\VentasCierresProveedorType;
use lOro\AdminBundle\Form\PagosVariosPorMesAnioType;
use lOro\AdminBundle\Form\TiempoCierresEntregasProveedorType;
use Symfony\Component\HttpFoundation\Response;
use PHPExcel;
use PHPExcel_IOFactory;

class ReportesEstadisticasController extends Controller
{
    public function indexAction() {
      return $this->render('lOroAdminBundle:ReportesEstadisticas:index.html.twig');
    }
    
    public function balancesGeneradosAction() {
      $em = $this->getDoctrine()->getManager();
        
      $balancesGenerados = $em->getRepository('lOroEntityBundle:BalancesGanancia')->findBy(array(),array('id' => 'DESC'));
      
      $data['balancesGenerados'] = $balancesGenerados;
      return $this->render('lOroAdminBundle:ReportesEstadisticas:balances_generados.html.twig',$data);        
    }
    
    public function verBalanceAction($balanceId) {
      $em = $this->getDoctrine()->getManager();
        
      $balance = $em->getRepository('lOroEntityBundle:BalancesGanancia')->find($balanceId);
      $arregloMatPendiente = $balance->getMaterialPorEntregarHc();
      
      
      
      $sumCierres = 0;
      $dolaresMatPendienteAupanas = 0;
      foreach($arregloMatPendiente as $row):
        $sumCierres += (float) $row['cantidadTotalVenta'];
        $dolaresMatPendienteAupanas += (float) $row['montoTotalDolar'];
      endforeach;  
      
      $arregloProveedores = $balance->getDeboProveedores();
      $totalBolivaresDebo = 0;
      
      foreach($arregloProveedores as $row):
        $totalBolivaresDebo += (float) $row['bolivares'];
      endforeach;
              
      
      $data['creditoHc'] = $balance->getCreditoHc();
      $data['totalTransferenciasPendientes'] = $balance->getTransferenciasPendientes();
      $data['balanceTotalBolivares'] = $balance->getBolivaresCaja();
      $data['balanceCajaDolares'] = ($balance->getBolivaresCaja() / $balance->getDolarReferencia());
      $data['totalBolivaresDebo'] = $totalBolivaresDebo;
      $data['bolivaresEnDolaresDebo'] = ($totalBolivaresDebo / $balance->getDolarReferencia());
      $data['arregloProveedores'] = $arregloProveedores;
      $data['materialNoCerrado'] = $balance->getMaterialNoCerrado();
      $data['gramosAdeudadosAupanas'] = $sumCierres;
      $data['dolaresMatPendienteAupanas'] = $dolaresMatPendienteAupanas;
      $data['arregloMatPendiente'] = $arregloMatPendiente;
      $data['balance'] = $balance;
      $data['valorOnza'] = $balance->getValorOnza();
      $data['promDolarReferencia'] = $balance->getDolarReferencia();
      $data['mostrar'] = false;
      return $this->render('lOroAdminBundle:ReportesEstadisticas:ver_balance.html.twig',$data);         
    }
    
    public function pagosVariosPorMesAnioAction(Request $request) {
      $em = $this->getDoctrine()->getManager();
      
      $form = $this->pagosVariosPorMesAnioForm('reporte_pagos_varios_por_mes_anio');
      
      $arregloEntity = array();
      $datosEntity = array();
      $mes = new \DateTime('now');
      $anio = new \DateTime('now');
      
      
      if($request->getMethod() === 'POST'):
          
        $form->handleRequest($request);
        
        if($form->isValid()):
          
          $mes = $form->get('mes')->getData();
          $anio = $form->get('anio')->getData();

          $pagosVarios = $em->getRepository('lOroEntityBundle:PagosVarios')->findPagosVariosPorMeseAnio($anio,$mes);
          
         
          if($pagosVarios):
           foreach($pagosVarios as $row):
              $datosEntity['totalPagado'] = $row['total_pagado'];
              $datosEntity['descripcion'] = $row['descripcion'];
              $datosEntity['anio'] = $row['anio'];
              $datosEntity['mes'] = $row['mes'];
              
              
              $arregloEntity[] = $datosEntity;
           endforeach;
          else:
            $arregloEntity = 'vacio';
          endif;

        endif;
          
      endif;
       
              
      
      return $this->render('lOroAdminBundle:ReportesEstadisticas:pagos_varios_por_mes_anio.html.twig',
             array('form' => $form->createView(),
                 'arregloEntity' => $arregloEntity
              ));
    }
    
    public function cierresPorFechaProveedorAction(Request $request) {
      $em = $this->getDoctrine()->getManager();
      $form = $this->ventasCierresProveedorForm();
      
      $arregloEntity = array();
      $datosEntity = array();
      $idProveedor = 0;
      $feDesde = new \DateTime('now');
      $feHasta = new \DateTime('now');
      
      
      if($request->getMethod() === 'POST'):
          
        $form->handleRequest($request);
        
        if($form->isValid()):
          
          $proveedorSeleccionado = $form->get('proveedor')->getData();
          $idProveedor = $proveedorSeleccionado->getId();
          $feDesde = $form->get('feDesde')->getData();
          $feHasta = $form->get('feHasta')->getData();
          
          
          
          $pagosProveedoresEntity = $em->getRepository('lOroEntityBundle:VentasCierres')->findVentasCierresPorFecha($idProveedor,
                  $feDesde->format('Y-m-d'),
                  $feHasta->format('Y-m-d'));
          

              
          if($pagosProveedoresEntity):
              
           foreach($pagosProveedoresEntity as $row):
              
              $datosEntity['feVenta'] = $row['fe_venta'];
              $datosEntity['nbProveedor'] = $row['nb_proveedor'];
              $datosEntity['cantidadTotalVenta'] = $row['cantidad_total_venta'];
              $datosEntity['montoCierrePorGramo'] = $row['monto_bs_cierre_por_gramo'];
              $datosEntity['montoCierre'] = $row['monto_bs_cierre'];
              
              
              $arregloEntity[] = $datosEntity;
           endforeach;
          else:
            $arregloEntity = 'vacio';
          endif;

        endif;
          
      endif;
      
      
      return $this->render('lOroAdminBundle:ReportesEstadisticas:cierres_proveedores_por_fecha.html.twig',
             array('form' => $form->createView(),
                 'arregloEntity' => $arregloEntity,
                 'proveedorId' => $idProveedor,
                 'feDesde' => $feDesde->format('Y-m-d'),
                 'feHasta' => $feHasta->format('Y-m-d')
              ));       
    }
    
    public function pagosProveedorPorFechaAction(Request $request) {
      $em = $this->getDoctrine()->getManager();
      
      $form = $this->pagosProveedorForm();
      
      $arregloEntity = array();
      $datosEntity = array();
      $idProveedor = 0;
      $feDesde = new \DateTime('now');
      $feHasta = new \DateTime('now');
      
      
      if($request->getMethod() === 'POST'):
          
        $form->handleRequest($request);
        
        if($form->isValid()):
          
          $proveedorSeleccionado = $form->get('proveedor')->getData();
          $idProveedor = $proveedorSeleccionado->getId();
          $feDesde = $form->get('feDesde')->getData();
          $feHasta = $form->get('feHasta')->getData();
          
          
          $pagosProveedoresEntity = $em->getRepository('lOroEntityBundle:Proveedores')->findPagosProveedoresPorFecha($proveedorSeleccionado->getId(),
                  $feDesde->format('Y-m-d'),
                  $feHasta->format('Y-m-d'));
          

              
          if($pagosProveedoresEntity):
           foreach($pagosProveedoresEntity as $row):
              $datosEntity['nombreEmpresa'] = $row['nombre_empresa'];
              $datosEntity['nombreProveedor'] = $row['nb_proveedor'];
              $datosEntity['fePago'] = $row['fe_pago'];
              $datosEntity['montoPagado'] = $row['monto_pagado'];
              $datosEntity['tipoTransaccion'] = $row['nb_transaccion'];
              $datosEntity['nbBanco'] = $row['nb_banco'];
              $datosEntity['nroReferencia'] = $row['nro_referencia'];
              $datosEntity['nombreEmpresaCasa'] = $row['nombre_empresa_casa'];
              
              
              $arregloEntity[] = $datosEntity;
           endforeach;
          else:
            $arregloEntity = 'vacio';
          endif;

        endif;
          
      endif;
      
      
      return $this->render('lOroAdminBundle:ReportesEstadisticas:pagos_proveedores_por_fechas.html.twig',
             array('form' => $form->createView(),
                 'arregloEntity' => $arregloEntity,
                 'proveedorId' => $idProveedor,
                 'feDesde' => $feDesde->format('Y-m-d'),
                 'feHasta' => $feHasta->format('Y-m-d')
              ));        
    }
    
    public function entregasPorFechaProveedorAction(Request $request) {
      $em = $this->getDoctrine()->getManager();
      
      $form = $this->seleccionarFechasProveedorForm('reporte_pagos_proveedores_empresas');
      
      $poseePagos = 'vacio';
      $viewPagosProveedores = null;
      $idProveedor = 0;
      
      /* Se inicializa el arreglo de entregas por proveedor */
      $arregloEntregas = array();      

      if($request->getMethod() === 'POST'):
          
        $form->handleRequest($request);
        
        if($form->isValid()):
          
          $proveedorSeleccionado = $form->get('proveedor')->getData();
          $idProveedor = $form->get('proveedor')->getData();
          $feDesde = $form->get('feDesde')->getData();
          $feHasta = $form->get('feHasta')->getData();
          
          
          $viewEntregasFechasProveedor = $em->getRepository('lOroEntityBundle:Proveedores')->findEntregasPorFechasProveedor($idProveedor,
                  $feDesde->format('Y-m-d'),
                  $feHasta->format('Y-m-d'));
          

              
          if($viewEntregasFechasProveedor):
           foreach($viewEntregasFechasProveedor as $row):
              $datosEntrega['fe_entrega'] = $row['fe_entrega'];
              $datosEntrega['peso_puro_entrega'] = $row['peso_puro_entrega'];
              $datosEntrega['nb_proveedor'] = $row['nb_proveedor'];
              
              $piezasPorEntrega = $em->getRepository('lOroEntityBundle:Piezas')->findBy(array('entrega' => $row['entrega_id']));
              
              $datosEntrega['cantidad_piezas_entregadas'] = count($piezasPorEntrega);
              
              /* Se inicializa el arreglo de piezas para cada entrega */
              $arregloPiezas = array();
              
              if($piezasPorEntrega):
                foreach($piezasPorEntrega as $col):
                  $datosPiezas['cod_pieza'] = $col->getCodPieza();
                  $datosPiezas['peso_bruto_pieza'] = $col->getPesoBrutoPieza();
                  $datosPiezas['ley_pieza'] = $col->getLeyPieza();
                  $datosPiezas['peso_puro_pieza'] = $col->getPesoPuroPieza();
                    
                  $arregloPiezas[] = $datosPiezas;
                endforeach;    
              endif;
              
              $datosEntrega['piezas_entregadas'] = $arregloPiezas;
              
              $arregloEntregas[] = $datosEntrega;
           endforeach;
          else:
            $arregloEntregas = 'vacio';
          endif;

        endif;
          
      endif;
      
      
      return $this->render('lOroAdminBundle:ReportesEstadisticas:entregas_por_fecha_proveedor.html.twig',
             array('form' => $form->createView(),
                   'poseePagos' => $poseePagos,
                   'viewPagosProveedores' => $viewPagosProveedores,
                   'idProveedor' => $idProveedor,
                   'arregloEntregas' => $arregloEntregas
              ));   
    }
    
    public function excelPagosProveedorPorFechasAction($proveedorId,$feDesde,$feHasta) {
       $response = new Response();
       $em = $this->getDoctrine()->getManager();
       
       $pagosProveedoresEntity = $em->getRepository('lOroEntityBundle:Proveedores')->findPagosProveedoresPorFecha($proveedorId,
                  $feDesde,
                  $feHasta);
          
       

          
       // Create new PHPExcel object
       $objPHPExcel = new PHPExcel();



       // Set document properties
       $objPHPExcel->getProperties()->setCreator("Nicte")
                   ->setTitle("Pagos a Proveedor por Fechas")
                   ->setSubject("Pagos a Proveedor por Fechas");
       
       
       // Add some data
       $objPHPExcel->setActiveSheetIndex(0);
       
       $obj = $objPHPExcel->getActiveSheet();
       
              $obj->setCellValue('A1', 'Fecha del Pago')
           ->setCellValue('B1', 'Pagado a')
           ->setCellValue('C1', 'Tipo de Transacción')
           ->setCellValue('D1', 'Monto (Bs)')
           ->setCellValue('E1', 'Banco')
           ->setCellValue('F1', 'Nro de Referencia');
           //->setCellValue('G1', 'Pagado Por');

       

         if($pagosProveedoresEntity):
           $col = 2;
           foreach($pagosProveedoresEntity as $row):
             
              $obj->setCellValue('A'.$col,$row['fe_pago']);
              $obj->setCellValue('B'.$col,$row['nombre_empresa']);
              $obj->setCellValue('C'.$col,$row['nb_transaccion']);
              $obj->setCellValue('D'.$col,$row['monto_pagado']);
              $obj->setCellValue('E'.$col,($row['nb_banco'] ? $row['nb_banco'] : 'No Encontrado'));
              $obj->setCellValue('F'.$col,$row['nro_referencia']);
              //$obj->setCellValue('G'.$col,$row['nombre_empresa_casa']);

              $col++;
              
              $nombreArchivo = "pago_proveedor_por_fecha_".strtolower($row['nb_proveedor']);
           endforeach;
           
          $cantidadCeldas = count($pagosProveedoresEntity) + 1;
          
       
          $styleArray = array('borders' => array(
                           'allborders' => array(
                           'style' => \PHPExcel_Style_Border::BORDER_THIN
                              )
                            )
                          );
       
          $objPHPExcel->getActiveSheet()->getStyle('A1:F'.$cantidadCeldas)->applyFromArray($styleArray);  
          $objPHPExcel->getActiveSheet()->getStyle('A1:F'.$cantidadCeldas)->getAlignment()->setWrapText(true);
          
          foreach(range('A','G') as $columnID)
          {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
          }
        endif;
          
       // Set active sheet index to the first sheet
       $objPHPExcel->setActiveSheetIndex(0);

       // Redirect output to a client’s web browser (Excel5)
       $response->headers->set('Content-Type', 'application/vnd.ms-excel');
       $response->headers->set('Content-Disposition', 'attachment;filename="'.$nombreArchivo.'".xls"');
       $response->headers->set('Cache-Control', 'max-age=0');
       //$response->prepare();
       $response->sendHeaders();
       $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
       $objWriter->save('php://output');
       exit();        
    }
    
    
    
    public function pagosProveedoresPorEmpresaAction(Request $request) {
    
      $em = $this->getDoctrine()->getManager();
      
      $form = $this->seleccionarProveedorForm('reporte_pagos_proveedores_empresas');
      
      $poseePagos = 'vacio';
      $viewPagosProveedores = null;
      $idProveedor = 0;
      

      if($request->getMethod() === 'POST'):
          
        $form->handleRequest($request);
        
        if($form->isValid()):
          
          $proveedorSeleccionado = $form->get('proveedor')->getData();
          $idProveedor = $proveedorSeleccionado->getId();
          
          $viewPagosProveedores = $em->getRepository('lOroEntityBundle:Proveedores')->findPagosProveedoresPorEmpresa($proveedorSeleccionado->getId());
          
          if($viewPagosProveedores):
            $poseePagos = 'lleno';
          else:
             $poseePagos = false;
          endif;

        endif;
          
      endif;
      
      
      return $this->render('lOroAdminBundle:ReportesEstadisticas:proveedores_empresa.html.twig',
             array('form' => $form->createView(),
                   'poseePagos' => $poseePagos,
                   'viewPagosProveedores' => $viewPagosProveedores,
                   'idProveedor' => $idProveedor
              ));
    }
    
    public function cierresPorProveedoresAction(Request $request) {
      $em = $this->getDoctrine()->getManager();
      
      $form = $this->seleccionarProveedorForm('reporte_cierres_por_proveedores');
      
      $poseePagos = 'vacio';
      $viewCierresProveedores = null;
      $idProveedor = 0;
      

      if($request->getMethod() === 'POST'):
          
        $form->handleRequest($request);
        
        if($form->isValid()):
          
          $proveedorSeleccionado = $form->get('proveedor')->getData();
          $idProveedor = $proveedorSeleccionado->getId();
          
          $viewCierresProveedores = $em->getRepository('lOroEntityBundle:Proveedores')->findCierresPorProveedor($proveedorSeleccionado->getId());
          
          if($viewCierresProveedores):
            $poseePagos = 'lleno';
          else:
             $poseePagos = false;
          endif;

        endif;
          
      endif;
      
      
      return $this->render('lOroAdminBundle:ReportesEstadisticas:cierres_proveedores.html.twig',
             array('form' => $form->createView(),
                   'poseePagos' => $poseePagos,
                   'viewCierresProveedores' => $viewCierresProveedores,
                   'idProveedor' => $idProveedor
              ));        
    }
    
    
    public function ventaDolaresPorCompradorAction(Request $request) {
    
      $em = $this->getDoctrine()->getManager();
      
      $form = $this->seleccionarProveedorForm('venta_dolares_comprador',TRUE);
      
      $poseePagos = 'vacio';
      $viewPagosProveedores = null;
      $idProveedor = 0;
      $comprasRealizadas = null;

      if($request->getMethod() === 'POST'):
          
        $form->handleRequest($request);
        
        if($form->isValid()):
          
          $compradorSeleccionado = $form->get('proveedor')->getData();
          $idProveedor = $compradorSeleccionado->getId();
          
          $comprasRealizadas = $em->getRepository('lOroEntityBundle:VentasDolares')->findBy(array('comprador' => $compradorSeleccionado),array('fechaVenta' => 'DESC'));
          
          if($comprasRealizadas):
            $poseePagos = 'lleno';
          else:
             $poseePagos = false;
          endif;

        endif;
          
      endif;
      
      
      return $this->render('lOroAdminBundle:ReportesEstadisticas:venta_dolares_comprador.html.twig',
             array('form' => $form->createView(),
                   'poseePagos' => $poseePagos,
                   'comprasRealizadas' => $comprasRealizadas,
                   'idProveedor' => $idProveedor
              ));
    }
    
    protected function pagosProveedorForm() {
      $form = $this->createForm(new PagosProveedoresType(),null, array(
              'action' => $this->generateUrl('reporte_pagos_proveedores_por_fecha'),
              'method' => 'POST',
      ));

      $form->add('submit', 'submit', array('label' => 'Generar Reporte',
                                             'attr' => array('class' => 'btn btn-success', 'style' => 'margin-top: 10px;')));

      return $form;       
    }   
    
    protected function ventasCierresProveedorForm(){
      $form = $this->createForm(new VentasCierresProveedorType(),null, array(
              'action' => $this->generateUrl('reporte_cierres_por_fecha_proveedor'),
              'method' => 'POST',
      ));

      $form->add('submit', 'submit', array('label' => 'Generar Reporte',
                                             'attr' => array('class' => 'btn btn-success', 'style' => 'margin-top: 10px;')));

      return $form;         
    }
    
    protected function pagosVariosPorMesAnioForm($url) {
      $form = $this->createForm(new PagosVariosPorMesAnioType(),null, array(
              'action' => $this->generateUrl($url),
              'method' => 'POST',
      ));

      $form->add('submit', 'submit', array('label' => 'Generar Reporte',
                                             'attr' => array('class' => 'btn btn-success', 'style' => 'margin-top: 10px;')));

      return $form;        
    }
    
    protected function tiempoCierresEntregasProveedorForm($url) {
      $form = $this->createForm(new TiempoCierresEntregasProveedorType($this->getDoctrine()->getManager()),null, array(
              'action' => $this->generateUrl($url),
              'method' => 'POST',
      ));

      $form->add('submit', 'submit', array('label' => 'Generar Reporte',
                                             'attr' => array('class' => 'btn btn-success', 'style' => 'margin-top: 10px;')));

      return $form;        
    }    
    
    protected function seleccionarFechasProveedorCierresForm($url) {
      $form = $this->createForm(new SeleccionarFechasPorProveedorType($this->getDoctrine()->getManager()),null, array(
              'action' => $this->generateUrl($url),
              'method' => 'POST',
      ));

      $form->add('submit', 'submit', array('label' => 'Generar Reporte',
                                             'attr' => array('class' => 'btn btn-success', 'style' => 'margin-top: 10px;')));

      return $form;          
    }
    
    protected function seleccionarFechasProveedorForm() {
      
      $form = $this->createForm(new SeleccionarFechasPorProveedorType($this->getDoctrine()->getManager()),null, array(
              'action' => $this->generateUrl('reporte_entregas_por_fecha_proveedor'),
              'method' => 'POST',
      ));

      $form->add('submit', 'submit', array('label' => 'Generar Reporte',
                                             'attr' => array('class' => 'btn btn-success', 'style' => 'margin-top: 10px;')));

      return $form;          
    }
    
    protected function seleccionarProveedorForm($urlSubmit,$compradorDolares = FALSE) {
      $form = $this->createForm(new SeleccionarProveedorType($compradorDolares),null, array(
              'action' => $this->generateUrl($urlSubmit),
              'method' => 'POST',
      ));

      $form->add('submit', 'submit', array('label' => 'Generar Reporte',
                                             'attr' => array('class' => 'btn btn-success', 'style' => 'margin-top: 10px;')));

      return $form;        
    }
       
    public function gananciasPorCierreProveedorAction(Request $request) {
      $em = $this->getDoctrine()->getManager();
      
      $form = $this->seleccionarFechasProveedorCierresForm('ganancias_por_cierre_proveedor');
      
      $poseePagos = 'vacio';
      $viewPagosProveedores = null;
      $idProveedor = 0;
      
      
      
      /* Se inicializa el arreglo de entregas por proveedor */
      $arregloEntregas = array();      
      $arregloCierresProveedor = array();

      if($request->getMethod() === 'POST'):
          
        $form->handleRequest($request);
        
        if($form->isValid()):
          
          $idProveedor = ($form->get('proveedor')->getData() != 9999 ? $form->get('proveedor')->getData() : null);
          $feDesde = $form->get('feDesde')->getData();
          $feHasta = $form->get('feHasta')->getData();
          
         
          /******************************************************************/
          $cierres = $em->getRepository('lOroEntityBundle:VentasCierres')->traerCierresPorFechasProveedor($idProveedor,$feDesde,$feHasta);
        
        if($cierres):
        foreach($cierres as $row):
          $dolarReferenciaDia = ($row['dolar_referencia_dia'] ? $row['dolar_referencia_dia'] : 0);
        
          $dolarCierreHc = ($row['cantidad_total_venta'] *(($row['valor_onza']/31.1035)*0.97));
          $dolarCierreProveedor = ($row['cantidad_total_venta'] *(($row['valor_onza']/31.1035)*0.95));
         
          $dolarBruto = (($dolarReferenciaDia * (($row['valor_onza']/31.1035)*0.97)));
          $bsPorGramoDados = $row['monto_bs_cierre_por_gramo'];
         
          $diferenciaBrutoGramosDados = (($dolarBruto - $bsPorGramoDados));
         
          $calculoLineal = ($dolarCierreHc - $dolarCierreProveedor);
          
          if($dolarReferenciaDia != 0):
          $totalDiferenciaBrutoGramosDados = ($diferenciaBrutoGramosDados * $row['cantidad_total_venta']);
          $gananciaBruta = (($totalDiferenciaBrutoGramosDados/$dolarReferenciaDia) - $calculoLineal);
          else:
            $gananciaBruta = 0;   
          endif;
          
          $totalGanancia = ($calculoLineal + $gananciaBruta);
            
          $datosArreglo['feVenta'] = $row['fe_venta'];
          $datosArreglo['nbProveedor'] = $row['nb_proveedor'];
          $datosArreglo['gramosCerrados'] = $row['cantidad_total_venta'];
          $datosArreglo['valorOnza'] = $row['valor_onza'];
          $datosArreglo['tipoMonedaCierreHc'] = $em->getRepository('lOroEntityBundle:TiposMoneda')->find($row['tipo_moneda_cierre_hc_id']);
          $datosArreglo['tipoMonedaCierre'] = $em->getRepository('lOroEntityBundle:TiposMoneda')->find($row['tipo_moneda_cierre_id']);
          $datosArreglo['dolarReferenciaDia'] = ($dolarReferenciaDia != 0 ? $dolarReferenciaDia : 'N/A');
          $datosArreglo['montoBsCierrePorGramo'] = $row['monto_bs_cierre_por_gramo'];            
          $datosArreglo['calculoLineal'] = $calculoLineal;
          $datosArreglo['gananciaBruta'] = $gananciaBruta;
          $datosArreglo['totalGanancia'] = $totalGanancia;

          $arregloCierresProveedor[] = $datosArreglo;
        endforeach;
        else:
            $arregloCierresProveedor = 'vacio';
        endif;
        /********************************************************************/
          
          $viewEntregasFechasProveedor = $em->getRepository('lOroEntityBundle:Proveedores')->findEntregasPorFechasProveedor($idProveedor,
                  $feDesde->format('Y-m-d'),
                  $feHasta->format('Y-m-d'));
          

              
          if($viewEntregasFechasProveedor):
           foreach($viewEntregasFechasProveedor as $row):
              $datosEntrega['fe_entrega'] = $row['fe_entrega'];
              $datosEntrega['peso_puro_entrega'] = $row['peso_puro_entrega'];
              $datosEntrega['nb_proveedor'] = $row['nb_proveedor'];
              
              $piezasPorEntrega = $em->getRepository('lOroEntityBundle:Piezas')->findBy(array('entrega' => $row['entrega_id']));
              
              $datosEntrega['cantidad_piezas_entregadas'] = count($piezasPorEntrega);
              
              /* Se inicializa el arreglo de piezas para cada entrega */
              $arregloPiezas = array();
              
              if($piezasPorEntrega):
                foreach($piezasPorEntrega as $col):
                  $datosPiezas['cod_pieza'] = $col->getCodPieza();
                  $datosPiezas['peso_bruto_pieza'] = $col->getPesoBrutoPieza();
                  $datosPiezas['ley_pieza'] = $col->getLeyPieza();
                  $datosPiezas['peso_puro_pieza'] = $col->getPesoPuroPieza();
                    
                  $arregloPiezas[] = $datosPiezas;
                endforeach;    
              endif;
              
              $datosEntrega['piezas_entregadas'] = $arregloPiezas;
              
              $arregloEntregas[] = $datosEntrega;
           endforeach;
          else:
            $arregloEntregas = 'vacio';
          endif;

        endif;
          
      endif;
      
      $data = array('form' => $form->createView(),
                   'poseePagos' => $poseePagos,
                   'viewPagosProveedores' => $viewPagosProveedores,
                   'idProveedor' => $idProveedor,
                   'arregloEntregas' => $arregloEntregas
              );
      
      $data['arregloCierresProveedor'] = $arregloCierresProveedor;
      return $this->render('lOroAdminBundle:ReportesEstadisticas:ganancias_cierres_por_proveedor.html.twig',$data);   
    }    
    
    public function enviarCorreoEntregasProveedorAction() {
      $em = $this->getDoctrine()->getManager();
      $correoDestinatario = $_POST['correoDestinatario'];
      $asunto = $_POST['asunto'];
      $texto = $_POST['texto'];
      $feDesde = new \ DateTime($_POST['feDesde']);
      $feHasta = new \ DateTime($_POST['feHasta']);
      $proveedorId = $_POST['proveedorId'];
      
      
     $viewEntregasFechasProveedor = $em->getRepository('lOroEntityBundle:Proveedores')->findEntregasPorFechasProveedor($proveedorId,
                  $feDesde->format('Y-m-d'),
                  $feHasta->format('Y-m-d'));
          

              
          if($viewEntregasFechasProveedor):
           foreach($viewEntregasFechasProveedor as $row):
              $datosEntrega['fe_entrega'] = $row['fe_entrega'];
              $datosEntrega['peso_puro_entrega'] = $row['peso_puro_entrega'];
              $datosEntrega['nb_proveedor'] = $row['nb_proveedor'];
              
              $piezasPorEntrega = $em->getRepository('lOroEntityBundle:Piezas')->findBy(array('entrega' => $row['entrega_id']));
              
              $datosEntrega['cantidad_piezas_entregadas'] = count($piezasPorEntrega);
              
              /* Se inicializa el arreglo de piezas para cada entrega */
              $arregloPiezas = array();
              
              if($piezasPorEntrega):
                foreach($piezasPorEntrega as $col):
                  $datosPiezas['cod_pieza'] = $col->getCodPieza();
                  $datosPiezas['peso_bruto_pieza'] = $col->getPesoBrutoPieza();
                  $datosPiezas['ley_pieza'] = $col->getLeyPieza();
                  $datosPiezas['peso_puro_pieza'] = $col->getPesoPuroPieza();
                    
                  $arregloPiezas[] = $datosPiezas;
                endforeach;    
              endif;
              
              $datosEntrega['piezas_entregadas'] = $arregloPiezas;
              
              $arregloEntregas[] = $datosEntrega;
           endforeach;
           
           
           $textoMensaje = $this->renderView('/Emails/mensaje_prueba.html.twig',
                                  array('texto' => $texto,
                                        'arregloEntregas' => $arregloEntregas));
        
          else:
            $arregloEntregas = 'vacio';
            $textoMensaje = null;
          endif;
          
          

      $this->get('loro_datos_generales')->enviarCorreo($asunto, $correoDestinatario, $textoMensaje);   
   
      return new Response(var_dump($feHasta));    
    }
    
    public function crearCorreoPagosProveedorPorFechaAction() {
      $em = $this->getDoctrine()->getManager();
      $correoDestinatario = $_POST['correoDestinatario'];
      $asunto = $_POST['asunto'];
      $texto = $_POST['texto'];
      $feDesde = new \ DateTime($_POST['feDesde']);
      $feHasta = new \ DateTime($_POST['feHasta']);
      $proveedorId = $_POST['proveedorId'];
      
      
      
      $pagosProveedoresEntity = $em->getRepository('lOroEntityBundle:Proveedores')->findPagosProveedoresPorFecha($proveedorId,
                  $feDesde->format('Y-m-d'),
                  $feHasta->format('Y-m-d'));
          

              
      if($pagosProveedoresEntity):
        foreach($pagosProveedoresEntity as $row):
          $datosEntity['nombreEmpresa'] = $row['nombre_empresa'];
          $datosEntity['fePago'] = $row['fe_pago'];
          $datosEntity['montoPagado'] = $row['monto_pagado'];
          $datosEntity['tipoTransaccion'] = $row['nb_transaccion'];
          $datosEntity['nroReferencia'] = $row['nro_referencia'];
          $datosEntity['nombreEmpresaCasa'] = $row['nombre_empresa_casa'];
              
          $nombreProveedor = $row['nb_proveedor'];
          
          $arregloEntity[] = $datosEntity;
        endforeach;
        
        $textoMensaje = $this->renderView('/Emails/mensaje_pagos_proveedor_por_fecha.html.twig',
                        array('texto' => $texto,
                              'nombreProveedor' => $nombreProveedor,
                              'arregloEntity' => $arregloEntity));
      else:
        $arregloEntity = 'vacio';
        $textoMensaje = null;
      endif;
      
    
      
      $this->get('loro_datos_generales')->enviarCorreo($asunto, $correoDestinatario, $textoMensaje);

      
      return new Response(var_dump($feHasta));   
    }
    
    public function crearCorreoCierresProveedorPorFechaAction(){
      $em = $this->getDoctrine()->getManager();
      $correoDestinatario = $_POST['correoDestinatario'];
      $asunto = $_POST['asunto'];
      $texto = $_POST['texto'];
      $feDesde = new \ DateTime($_POST['feDesde']);
      $feHasta = new \ DateTime($_POST['feHasta']);
      $proveedorId = $_POST['proveedorId'];
      
      
      
      $pagosProveedoresEntity = $em->getRepository('lOroEntityBundle:VentasCierres')->findVentasCierresPorFecha($proveedorId,
                  $feDesde->format('Y-m-d'),
                  $feHasta->format('Y-m-d'));
          
      
              
          if($pagosProveedoresEntity):
              
           foreach($pagosProveedoresEntity as $row):
              
              $datosEntity['feVenta'] = $row['fe_venta'];
              $datosEntity['nbProveedor'] = $row['nb_proveedor'];
              $datosEntity['cantidadTotalVenta'] = $row['cantidad_total_venta'];
              $datosEntity['montoCierrePorGramo'] = $row['monto_bs_cierre_por_gramo'];
              $datosEntity['montoCierre'] = $row['monto_bs_cierre'];
              
              $nombreProveedor = $row['nb_proveedor'];
              
              $arregloEntity[] = $datosEntity;
        endforeach;
        
        $textoMensaje = $this->renderView('/Emails/mensaje_cierres_proveedor_por_fecha.html.twig',
                        array('texto' => $texto,
                              'nombreProveedor' => $nombreProveedor,
                              'arregloEntity' => $arregloEntity));
      else:
        $arregloEntity = 'vacio';
        $textoMensaje = null;
      endif;
      
       $this->get('loro_datos_generales')->enviarCorreo($asunto, $correoDestinatario, $textoMensaje);
       
       return new Response(var_dump($feHasta));        
    }
    
    /**
     *  Controlador para el Reporte del Balance General por Meses
     * 
     * @param object $request - Peticion del formulario de seleccion de mes y año
     * 
     * @return object $renderView - Renderizacion de la Plantilla (index_balance_general_por_meses.html.twig)
     ***/
    public function balanceGeneralPorMesesAction(Request $request) {
      $em = $this->getDoctrine()->getManager();
      $arregloEntity = null;
      
      $form = $this->pagosVariosPorMesAnioForm('reporte_balance_general_por_meses');
      
      if($request->getMethod() === 'POST'):
          
        $form->handleRequest($request);
        
        if($form->isValid()):
          $mes = $form->get('mes')->getData();
          $anio = $form->get('anio')->getData();
          
          
          //$this->entregasCierresConRespectoAlTiempo();
          
          
          $vCierresProveedoresMesAnio = $em->getRepository('lOroEntityBundle:BalancesGanancia')->getVCierresEntregasParaComparacionTiempo(9);
          
          $arregloPorCierres = array();
          foreach($vCierresProveedoresMesAnio as $row):
            
            $datosCierre['fe_venta'] = $row['fe_venta'];
            $datosCierre['id_cierre'] = $row['id_cierre'];
            $datosCierre['cantidad_total_venta'] = $row['cantidad_total_venta'];
            
            $arregloPorCierres[$row['id_cierre']] = $datosCierre;
          endforeach;
          
          
          foreach($vCierresProveedoresMesAnio as $rowEntrega):
            $fechaCierre = new \DateTime($rowEntrega['fe_venta']);
            $feEntrega = new \DateTime($rowEntrega['fe_entrega']);
            
            $difFechas = $fechaCierre->diff($feEntrega)->days;
            
            $datosEntrega['fe_entrega'] = $rowEntrega['fe_entrega'];
            $datosEntrega['id_pieza'] = $rowEntrega['id_pieza'];
            $datosEntrega['id_cierre'] = $rowEntrega['id_cierre'];
            $datosEntrega['material_entregado'] = $rowEntrega['material_entregado'];
            $datosEntrega['dias_diferencia'] = $difFechas;
              
            $arregloEntregas[] = $datosEntrega;
          endforeach;
          
          
                     
          
          foreach($arregloPorCierres as $key => $row):
             $idCierre = $row['id_cierre'];
             $fechaCierre = new \DateTime($row['fe_venta']);
             $totalCerrado = $row['cantidad_total_venta'];
             
              echo 'Cierre: '.$idCierre.' - '.$fechaCierre->format('d-m-Y').' - Cerrado: '.number_format($totalCerrado,2,',','.').'<hr>';
              foreach($arregloEntregas as $key => $rowEntrega):
                if($idCierre == $rowEntrega['id_cierre']):
                  $idPieza = $rowEntrega['id_pieza'];
                  $feEntrega = new \DateTime($rowEntrega['fe_entrega']);
                  $materialEntregado = $rowEntrega['material_entregado'];
                  
                  
                echo $idPieza.' - '.$feEntrega->format('d-m-Y').' - '.$materialEntregado.' - '.$rowEntrega['dias_diferencia'].' Dias de Diferencia<br>';
                endif;
              endforeach;
              /*
              foreach($row['arreglo_entregas'] as $rowEntrega):

                
              endforeach;
 */
              echo '<hr>';
          endforeach;
          
          //$vCierresProveedoresMesAnio = $em->getRepository('lOroEntityBundle:BalancesGanancia')->getVCierresProveedorPorMesAnioAction($mes,$anio);
          
          $arregloEntity = ($vCierresProveedoresMesAnio ? $vCierresProveedoresMesAnio : "vacio");
        endif;
      endif;
      
      $data['form'] = $form->createView();
      $data['arregloEntity'] = $arregloEntity;
      return $this->render('lOroAdminBundle:ReportesEstadisticas:index_balance_general_por_meses.html.twig',$data); 
    }
    
    public function indexTiempoCierresEntregasProveedorAction(Request $request) {
      $em = $this->getDoctrine()->getManager();
      $arregloEntity = null;
      $arregloPorCierres = array();
      $arregloEntregas = array();
      
      $form = $this->tiempoCierresEntregasProveedorForm('reporte_tiempo_cierres_entregas_proveedor');
      
      if($request->getMethod() === 'POST'):
          
        $form->handleRequest($request);
        
        if($form->isValid()):
          $idProveedor = ($form->get('proveedor')->getData() != 9999 ? $form->get('proveedor')->getData() : null);
          $feDesde = $form->get('feDesde')->getData();
          $feHasta = $form->get('feHasta')->getData();
          
          
          $vCierresProveedoresMesAnio = $em->getRepository('lOroEntityBundle:BalancesGanancia')->getVCierresEntregasParaComparacionTiempo($feDesde->format('Y-m-d'),$feHasta->format('Y-m-d'),$idProveedor);
          
          $arregloPorCierres = array();
          foreach($vCierresProveedoresMesAnio as $row):
            
            $datosCierre['fe_venta'] = $row['fe_venta'];
            $datosCierre['nb_proveedor'] = $row['nb_proveedor'];
            $datosCierre['monto_bs_cierre_por_gramo'] = $row['monto_bs_cierre_por_gramo'];
            $datosCierre['valor_onza'] = $row['valor_onza'];
            $datosCierre['id_cierre'] = $row['id_cierre'];
            $datosCierre['estatus'] = $row['estatus'];
            $datosCierre['dolar_referencia_dia'] = $row['dolar_referencia_dia'];
            $datosCierre['cantidad_total_venta'] = $row['cantidad_total_venta'];
            
            $arregloPorCierres[$row['id_cierre']] = $datosCierre;
          endforeach;
          
          
          foreach($vCierresProveedoresMesAnio as $rowEntrega):
            $fechaCierre = new \DateTime($rowEntrega['fe_venta']);
            $feEntrega = new \DateTime($rowEntrega['fe_entrega']);
            
            $difFechas = $fechaCierre->diff($feEntrega)->days;
            
            $datosEntrega['fe_entrega'] = $rowEntrega['fe_entrega'];
            $datosEntrega['cod_pieza'] = $rowEntrega['cod_pieza'];
            $datosEntrega['id_cierre'] = $rowEntrega['id_cierre'];
            $datosEntrega['ganancia_pieza'] = $rowEntrega['ganancia_pieza'];
            $datosEntrega['material_entregado'] = $rowEntrega['material_entregado'];
            $datosEntrega['dias_diferencia'] = $difFechas;
              
            $arregloEntregas[] = $datosEntrega;
          endforeach;
          
          
                     
          
          
          

          
          $arregloEntity = ($vCierresProveedoresMesAnio ? $vCierresProveedoresMesAnio : "vacio");
        endif;
      endif;
      
      $data['arregloPorCierres'] = $arregloPorCierres;
      $data['arregloEntregas'] = $arregloEntregas;
      $data['form'] = $form->createView();
      return $this->render('lOroAdminBundle:ReportesEstadisticas:index_tiempo_cierres_entregas_proveedores.html.twig',$data);        
    }
    
    public function entregasCierresConRespectoAlTiempo() {
        $em = $this->getDoctrine()->getManager();
        $mes = null;
        $anio = null;
        
        $proveedores = $em->getRepository('lOroEntityBundle:Proveedores')->getProveedoresConCierres();
        
        foreach($proveedores as $rowProveedor):
          
          $VCierresParaComparacionTiempo = $em->getRepository('lOroEntityBundle:BalancesGanancia')->getVCierresEntregasParaComparacionTiempo($mes,$anio,'Cierre',$rowProveedor['id_proveedor']);
          $VEntregasParaComparacionTiempo = $em->getRepository('lOroEntityBundle:BalancesGanancia')->getVCierresEntregasParaComparacionTiempo($mes,$anio,'Entrega',$rowProveedor['id_proveedor']);
          
          
          foreach($VCierresParaComparacionTiempo as $rowCierre):
            if($rowCierre['operacion_relacionada'] == 0):
            $fechaCierre = $rowCierre['fecha'];
            $montoCierre = $rowCierre['total_operacion'];
            $proveedor = $rowCierre['nb_proveedor'];
            
            echo $rowCierre['tipo_operacion']." - ";
            echo $fechaCierre." - ";
            echo $montoCierre." - ";
            echo $proveedor;
            echo '<br>';
            
            
            foreach($VEntregasParaComparacionTiempo as $rowEntrega):
                if($rowEntrega['operacion_relacionada'] == 0):
                $fechaEntrega = $rowEntrega['fecha'];
                $montoEntrega = $rowEntrega['total_operacion'];
                $montoCierre = $rowCierre['total_operacion'];
                $proveedorE = $rowCierre['nb_proveedor'];
                
                if($montoCierre > 0):
                  $dif =  $montoCierre - $rowEntrega['total_operacion'];
                  
                  if($dif > 0):
                    $entregadoParaElCierre =  $rowEntrega['total_operacion'];   
                    $rowEntrega['operacion_relacionada'] = "1";
                  elseif($dif < 0):
                    $entregadoParaElCierre =  $montoCierre;
                    $rowCierre['operacion_relacionada'] = "1";
                  else:
                      $entregadoParaElCierre =  $rowEntrega['total_operacion'];
                      $rowEntrega['operacion_relacionada'] = "1";
                      $rowCierre['operacion_relacionada'] = "1";
                  endif;
                 
                  
                  $montoCierre = $dif;
                  

                else:
                  break;    
                endif;
                
                
              
                                              echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
              echo $rowEntrega['tipo_operacion']." - ";
              echo $fechaEntrega." - ";
            echo $montoEntrega. " - ";
            
            echo $proveedorE." - ";
            echo "Entregado: ".$entregadoParaElCierre;
            echo '<br>'; 
            
              endif;
            endforeach;
            
            endif;
          endforeach; 
        endforeach;
    }
}
