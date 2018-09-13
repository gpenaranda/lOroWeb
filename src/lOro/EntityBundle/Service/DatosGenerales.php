<?php

namespace lOro\EntityBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;


//include '../vendor/pdfparser/vendor/autoload.php';

class DatosGenerales {
    
    protected $em;
    protected $mailer;
    private $container;
    private $session;    
    private $router;

    public function __construct(EntityManager $em,$mailer,Container $container, Session $session, RouterInterface $router) {
      $this->em = $em;
      $this->mailer = $mailer;
      $this->container = $container;
      $this->session = $session;  
      $this->router = $router;    
    }
    
    
    public function generarPromDolaresReferencia() {
      $foreignCurrencySales = $this->em->getRepository('lOroEntityBundle:VentasDolares')->findBy(array(),array('fechaVenta' => 'ASC', 'id' => 'ASC'));
      $totalPayment = $this->em->getRepository('lOroEntityBundle:BalancesGanancia')->getSumOfRefAvgPayments();

      $arrayToUseForPromedy = array();
      $amountOfBsReceived = 0;
      $totalOfBs = 0;
      $finalCurrencyAverage = 0;
        
      foreach($foreignCurrencySales AS $fcSale):
        $amountOfBsReceived = $fcSale->getMontoVentaBolivares();

        if($totalPayment > 0):
          $amountOfBsReceived = $amountOfBsReceived - $totalPayment;
          $totalPayment = $totalPayment - $fcSale->getMontoVentaBolivares();

          if($totalPayment < 0 ):
            $totalOfBs += $amountOfBsReceived;
            $arrayData['amountOfBsReceived'] = $amountOfBsReceived;
            $arrayData['saleCurrencyReference'] = ($fcSale->getTipoMoneda()->getId() != 2 ? ($fcSale->getDolarReferencia()*$fcSale->getCotizacionReferencia()) : $fcSale->getDolarReferencia());
            $arrayData['weightedAvg'] = 0; 
            $arrayData['weightedReferenceResult'] = 0;
            $arrayToUseForPromedy[] = $arrayData;
          endif;
        else:
          $totalOfBs += $amountOfBsReceived;
          $arrayData['amountOfBsReceived'] = $amountOfBsReceived;
          $arrayData['saleCurrencyReference'] = ($fcSale->getTipoMoneda()->getId() != 2 ? ($fcSale->getDolarReferencia()*$fcSale->getCotizacionReferencia()) : $fcSale->getDolarReferencia()); 
          $arrayData['weightedAvg'] = 0;
          $arrayData['weightedReferenceResult'] = 0;     
          $arrayToUseForPromedy[] = $arrayData;
        endif;
      endforeach;

        
      foreach($arrayToUseForPromedy as $k => $r):
        $weightedAvg = ($r['amountOfBsReceived'] / $totalOfBs);
        $arrayToUseForPromedy[$k]['weightedAvg'] = $weightedAvg;
        $arrayToUseForPromedy[$k]['weightedReferenceResult'] = ($r['saleCurrencyReference']*$weightedAvg);
        $finalCurrencyAverage +=  $arrayToUseForPromedy[$k]['weightedReferenceResult'];
      endforeach;

    return $finalCurrencyAverage;
  }   
   
   
    /**
     * Servicio para realizar el envio de correos.
     * 
     * @author Gabriel E. Peñaranda G <gpenaranda@textileslolo.com>
     * @param string $asunto - Asunto del Correo
     * @param string $correosDestinatarios Correos de los destinatarios (Separados por coma)
     * @param object $textoMensaje Texto del Mensaje, el cual debe ser definido mediante $this->renderView()
     *                             en un template  
     **/   
   public function enviarCorreo($asunto,$correosDestinatarios,$textoMensaje) {
      $correos = explode(',',$correosDestinatarios);
      
      
      foreach($correos as $correo):        
        if($textoMensaje):
          $mailer = $this->mailer;//$this->get('mailer');
    
          $message = $mailer->createMessage()
                          ->setSubject($asunto)
                          ->setFrom(['orotex@textileslolo.com' => 'Administración - Orotex'])
                          ->setTo($correo)
                          ->setBody($textoMensaje,'text/html');

          $mailer->send($message);     
        endif;
      endforeach;  
    }
    
    public function getUploadedFile($request,$form,$tipoProveedorId) {
        $arregloDatosArchivo  = array();
        $datosArchivoPdf['error'] = false;



      if ($request->isMethod('POST')) 
      {
        $form->handleRequest($request);
            
        
        if (isset($_FILES['loro_entitybundle_updload_files']) && $_FILES['loro_entitybundle_updload_files']['type']['inputFile'] == 'application/pdf') {

          $content = file_get_contents($_FILES['loro_entitybundle_updload_files']['tmp_name']['inputFile']);
          $fileName = $_FILES['loro_entitybundle_updload_files']['name']['inputFile'];
          $rowFileName  = explode("-",$_FILES['loro_entitybundle_updload_files']['name']['inputFile']);
          
          
          $nbProveedor = explode(".",$rowFileName[1]);

          /* Se busca el Proveedor mediante el Nombre del PROVEEDOR y el Tipo de Proveedor  */
          $proveedor = $this->em->getRepository('lOroEntityBundle:Proveedores')->findOneBy(array('nbProveedor' => $nbProveedor, 'tipoProveedor' => $tipoProveedorId));

          if(!$proveedor):
            $this->session->getFlashBag()->add('error', 'El archivo no puede ser cargado ya que no existe el proveedor indicado.');
            $datosArchivoPdf['error'] = true;
          else:
                
            /* Nombre del Archivo y Nombre del Proveedor */
            $datosArchivoPdf['nbArchivo'] = $_FILES['loro_entitybundle_updload_files']['name']['inputFile'];
            $datosArchivoPdf['nbProveedor'] = $nbProveedor;


            try {
              if ($content) {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf    = $parser->parseContent($content);
                $pages  = $pdf->getPages();
      
                foreach ($pages as $page):
                  $texts[] = $page->getText();
                endforeach;
                
                $this->traerDatosArchivoPdf($texts,$rowFileName[0],$nbProveedor,$datosArchivoPdf);


                /******************************* SE GUARDA EL ARCHIVO EN EL SISTEMA ********************************/
                $transferenciaRealizada = $this->em->getRepository('lOroEntityBundle:PagosProveedores')->findOneBy(array('nroReferencia' => $datosArchivoPdf['nroReferencia']));


                if(!$transferenciaRealizada):
                  $folderPath = $this->container->get('kernel')->getRootDir().'/../web/bundles/lOroBundle/files/';
                  $nombreArchivo = $proveedor->getId().$datosArchivoPdf['nroReferencia'].'.pdf';
                  file_put_contents($folderPath.$nombreArchivo, $content);
                else:
                  $this->session->getFlashBag()->set('error', 'El pago N° '.$datosArchivoPdf['nroReferencia'].' ya ha sido registrado en el sistema.');
                  $datosArchivoPdf['error'] = true;
                endif;
                /******************************* SE GUARDA EL ARCHIVO EN EL SISTEMA ********************************/
           
            } else 
              {
                $this->session->getFlashBag()->add('error', 'El Archivo cargado no puede ser mostrado.');
                $datosArchivoPdf['error'] = true;
              }
            } catch(Exception $e) 
              {
                $message = $e->getMessage();
                throw new \Exception('Algo salio Mal! '.$message);
              }
          endif;
        }
      }
        
      return $datosArchivoPdf;        
    }
    
    public function traerDatosArchivoPdf($texts,$nroReferencia,$nombreArchivo,&$datosArchivoPdf) {
        $em = $this->em;

      



        foreach ($texts as $pos => $text) :

         
          $text = preg_replace('/\s/', '', $text  ); // PARA QUITARLE LOS ESPACIOS EN BLANCOS 


            $nbProveedor = (isset($nombreArchivo) ? trim($nombreArchivo[0]) : null);
            $proveedor = $em->getRepository('lOroEntityBundle:Proveedores')->getProveedorPorNombre($nbProveedor);
            $objProveedor = ($proveedor ? $em->getRepository('lOroEntityBundle:Proveedores')->find($proveedor['id']) : null);
            $tipoTransaccion = $em->getRepository('lOroEntityBundle:TipoTransaccion')->find(2);



            preg_match("/F e ch a:(.*)/", $text, $a);
            

            if(!array_key_exists(1, $a)):
              

              preg_match("/Fecha:(.*)/", $text, $a);

              if(!array_key_exists(1, $a)):
                
                preg_match("/F ech a:(.*)/", $text, $a);

                if(!array_key_exists(1, $a)):
                  
                  preg_match("/F ech a:(.*)/", $text, $a);
                endif;
              endif;
            endif;

            
            
            

            

            

            $fechaPago = str_replace(" ", "", str_replace('/', '-', preg_replace('/\s+/', '', substr($a[1], 0, 12)  ) ) );
            


            $objFePago = ($fechaPago && $fechaPago != " \n" ? new \DateTime(substr($fechaPago, -10)) : null);


            
           


            preg_match('!M on to :\s*(\d.+)!i', $text, $b);

            if(!array_key_exists(1, $b)):
              preg_match('!Monto:\s*(\d.+)!i', $text, $b);
            endif;


            $montoPagado = str_replace(" ","",$b[1]);

            
            /*
            preg_match("/Ben efic ia rio :(.*)/", $text, $c);
            $beneficiario = $c[1];
            */


            /* Inversiones Cometa */
            if (strpos($text, '1085938') !== false):
                $idEmpresaPago = 5;

            /* Inv Kitco */
            elseif (strpos($text, '1071075') !== false):
                $idEmpresaPago = 4;

            /* Marle */
            elseif (strpos($text, '3001865') !== false):
                $idEmpresaPago = 6;

            /* Constructora Muros */
            elseif (strpos($text, '1001996') !== false):
                $idEmpresaPago = 236;

            /* ABC */
            elseif (strpos($text, '3002155') !== false):
                $idEmpresaPago = 322;

            /* SAC */
            elseif (strpos($text, '3002154') !== false):
                $idEmpresaPago = 221;

            /* M & E */
            elseif (strpos($text, '3002133') !== false):
                $idEmpresaPago = 231;
            else:
                $idEmpresaPago = null;
            endif;

            $objEmpresaPago = ($idEmpresaPago ? $em->getRepository('lOroEntityBundle:EmpresasProveedores')->find($idEmpresaPago) : null);


            $datosArchivoPdf['tipoTransaccion'] = $tipoTransaccion;
            $datosArchivoPdf['nroReferencia'] = $nroReferencia;
            $datosArchivoPdf['nbProveedor'] = $nbProveedor;
            $datosArchivoPdf['idProveedor'] = ($proveedor ? $proveedor['id'] : null);
            $datosArchivoPdf['proveedor'] = $objProveedor;
            $datosArchivoPdf['fechaPago'] = $objFePago;
            $datosArchivoPdf['montoPagado'] = $montoPagado;
            //$datosArchivoPdf['beneficiario'] = $beneficiario;
            $datosArchivoPdf['objEmpresaPago'] = $objEmpresaPago;

        endforeach;


        $datosArchivoPdf['pdf'] = $texts;
    }    
    
    public function generarArregloSumatoriasPorProveedores($balanceActivo,$cierresProveedor,$entregas)
    {
      $em = $this->em;
      $arregloProveedor = array();
      
      $proveedores = $em->getRepository('lOroEntityBundle:Proveedores')->findAll();
      
      $pagosProveedores = $em->getRepository('lOroEntityBundle:PagosProveedores')->findBy(array('balance' => $balanceActivo));
      
      foreach($proveedores as $proveedor):
          
        
        /** Busqueda de la Data en Bolivares **/
        $bolivaresProveedor = 0;
        $totalOro = 0;
        
        $oroCierres = 0;
        $bolivaresCierre = 0;
        $bolivaresTransferencias = 0;
        
        /* Saldos en Bolivares */
        $saldosCierresPorProveedor = $em->getRepository('lOroEntityBundle:Proveedores')->buscarSaldosMensualesCierres($proveedor->getId());
        $pagosMensualesPorProveedor = $em->getRepository('lOroEntityBundle:Proveedores')->buscarPagosMensualesPorProveedor($proveedor->getId());
        
        
        
        foreach($saldosCierresPorProveedor as $row):
          $bolivaresCierre += $row['saldo_total_mensual_cierre'];
        endforeach;
        
        foreach($pagosMensualesPorProveedor as $row):
          $bolivaresTransferencias += $row['saldo_mensual_pagado'];    
        endforeach;
        /* Saldos en Bolivares */
        
        
        /* Saldos en VERDES */
        $saldosVerdesCierresPorProveedor = $em->getRepository('lOroEntityBundle:Proveedores')->buscarSaldosMensualesVerdesCierres($proveedor->getId());
        $pagosVerdesMensualesPorProveedor = $em->getRepository('lOroEntityBundle:Proveedores')->buscarPagosMensualesVerdesPorProveedor($proveedor->getId());
        $dolaresTransferencias = 0;
        $dolaresCierre = 0;
        
        foreach($saldosVerdesCierresPorProveedor as $row):
          $dolaresCierre += $row['saldo_total_mensual_cierre'];
        endforeach;
        
        foreach($pagosVerdesMensualesPorProveedor as $row):
          $dolaresTransferencias += $row['saldo_mensual_pagado'];    
        endforeach;
        /* Saldos en VERDES */
        
        /* Saldos en MORADOS */
        $saldosMoradosCierresPorProveedor = $em->getRepository('lOroEntityBundle:Proveedores')->buscarSaldosMensualesMoradosCierres($proveedor->getId());
        $pagosMoradosMensualesPorProveedor = $em->getRepository('lOroEntityBundle:Proveedores')->buscarPagosMensualesMoradosPorProveedor($proveedor->getId());
        $eurosCierre = 0;
        $eurosTransferencias = 0;
        
        foreach($saldosMoradosCierresPorProveedor as $row):
          $eurosCierre += $row['saldo_total_mensual_cierre'];
        endforeach;
        
        foreach($pagosMoradosMensualesPorProveedor as $row):
          $eurosTransferencias += $row['saldo_mensual_pagado'];    
        endforeach;
        /* Saldos en MORADOS */        
        
        
        
        foreach($cierresProveedor as $cierreProveedor):
            
          if($cierreProveedor->getProveedorCierre()->getId() == $proveedor->getId()):
            $oroCierres += $cierreProveedor->getCantidadTotalVenta();
          endif;
          
           
        endforeach;
          
        
            
        
        
        $oroEntregas = 0;
        foreach($entregas as $entrega):
          if($entrega->getProveedor()->getId() == $proveedor->getId()):
            $oroEntregas += $entrega->getPesoPuroEntrega();
          endif;            
        endforeach;
        
        $bolivaresProveedor = $bolivaresCierre - $bolivaresTransferencias;
        $dolaresProveedor = $dolaresCierre - $dolaresTransferencias;
        $eurosProveedor = $eurosCierre - $eurosTransferencias;
        
        $totalOro = $oroCierres - $oroEntregas;
        
        $datosArreglo['proveedor'] = $proveedor;
        $datosArreglo['bolivares'] = $bolivaresProveedor;
        $datosArreglo['dolares'] = $dolaresProveedor;
        $datosArreglo['euros'] = $eurosProveedor;
        $datosArreglo['totalOro'] = $totalOro;
        /** Busqueda de la Data en Bolivares **/
        
        if($bolivaresProveedor != 0 || $totalOro != 0):
         $arregloProveedor[$proveedor->getId()] = $datosArreglo;   
        endif;
        
      endforeach;
      
      return $arregloProveedor;
    }    





    public function getBalancePorProveedor($proveedorId) {
     
      return $this->em->getRepository('lOroEntityBundle:Balances')->balancePorProveedorGeneral($proveedorId);;
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
    public function relacionBolivaresDolares() {
      
      return $this->em->getRepository('lOroEntityBundle:Balances')->balanceBolivaresGeneral(); 
    }

   
   /**
     * @author Gabriel E. Peñaranda G. <gabriel.e.p.gonzalez@gmail.com>
     * 
     * @param object $em  - Objeto utilizado por Symfony para el manejo de las Entidades
     * @param array $data - Arreglo que posee los parametros que seran pasados a la vista
     */   
    public function cantCierresDelDia() {
      return count($this->em->getRepository('lOroEntityBundle:VentasCierres')->traerCierresDelDiaProveedores());
    } 


    /**
     * @author Gabriel E. Peñaranda G. <gabriel.e.p.gonzalez@gmail.com>
     * 
     * @param object $proveedoresPorUsuario  - Objeto que posee cuales proveedores puede ver el usuario que ingreso al sistema
     * @param array $data - Arreglo que posee los parametros que seran pasados a la vista
     */   
    public function getBalanceProveedoresGeneral($proveedoresPorUsuario = null) {
      $em = $this->em;
      $arregloProveedor = array();


      if($proveedoresPorUsuario != NULL):
        $stringBalanceProveedores = $em->getRepository('lOroEntityBundle:Balances')->balanceProveedoresGeneral();

        $expListadoBProv = explode('>',$stringBalanceProveedores['listado_proveedores']);

        
        foreach($expListadoBProv as $row):
          $expCantProv = explode('/',$row);
          if($expCantProv[0] != null):
            foreach($proveedoresPorUsuario as $provUser):
              if($provUser->getProveedor()->getId() == $expCantProv[0]):
                $datosProv['idProv'] = $expCantProv[0];
                $datosProv['nbProveedor'] = $expCantProv[1];
                $datosProv['rawDebtMat'] = $expCantProv[2];
                $datosProv['rawDebtBs'] = $expCantProv[3];
                $datosProv['rawDebtDol'] = $expCantProv[4];
                $datosProv['rawDebtEu'] = $expCantProv[5];
                $datosProv['matAdeudado'] = number_format($expCantProv[2],'2',',','.')." Grs.";
                $datosProv['deudaTotalBs'] = number_format($expCantProv[3],'2',',','.')." Bs.";
                $datosProv['deudaTotalDol'] = number_format($expCantProv[4],'2',',','.')." $";
                $datosProv['deudaTotalEu'] = number_format($expCantProv[5],'2',',','.')." €";

                $arregloProveedor[] = $datosProv;
              endif;
            endforeach;
          endif;
         endforeach;
      else:
        $stringBalanceProveedores = $em->getRepository('lOroEntityBundle:Balances')->balanceProveedoresGeneral();

        $expListadoBProv = explode('>',$stringBalanceProveedores['listado_proveedores']);

        
        foreach($expListadoBProv as $row):
          $expCantProv = explode('/',$row);
          if($expCantProv[0] != null):
                $datosProv['idProv'] = $expCantProv[0];
                $datosProv['nbProveedor'] = $expCantProv[1];
                $datosProv['rawDebtMat'] = $expCantProv[2];
                $datosProv['rawDebtBs'] = $expCantProv[3];
                $datosProv['rawDebtDol'] = $expCantProv[4];
                $datosProv['rawDebtEu'] = $expCantProv[5];
                $datosProv['matAdeudado'] = number_format($expCantProv[2],'2',',','.')." Grs.";
                $datosProv['deudaTotalBs'] = number_format($expCantProv[3],'2',',','.')." Bs.";
                $datosProv['deudaTotalDol'] = number_format($expCantProv[4],'2',',','.')." $";
                $datosProv['deudaTotalEu'] = number_format($expCantProv[5],'2',',','.')." €";

                $arregloProveedor[] = $datosProv;
          endif;
         endforeach;
      endif;

      return $arregloProveedor;
    }    


    /**
     * @author Gabriel E. Peñaranda G. <gabriel.e.p.gonzalez@gmail.com>
     * 
     * @param object $em  - Objeto utilizado por Symfony para el manejo de las Entidades
     * @param array $data - Arreglo que posee los parametros que seran pasados a la vista
     */
    public function getBalanceMinoristasGeneral() {
      $em = $this->em;
      $arregloMinoristas = array();


      $listadoBalanceMinoristas = $em->getRepository('lOroEntityBundle:Balances')->balanceMinoristasGeneral();
      
      $expListadoBMino = explode('>',$listadoBalanceMinoristas['listado_minoristas']);

       
      foreach($expListadoBMino as $row):
        $expCantMinoristas = explode('/',$row);

        if($expCantMinoristas[0] != null):
          $datosMinoristas['nbMinorista'] = $expCantMinoristas[1];
          $datosMinoristas['rawDebtMat'] = $expCantMinoristas[2];
          $datosMinoristas['rawDebtBs'] = $expCantMinoristas[3];
          $datosMinoristas['matAdeudado'] = number_format($expCantMinoristas[2],'2',',','.')." Grs.";
          $datosMinoristas['deudaTotalBs'] = number_format($expCantMinoristas[3],'2',',','.')." Bs.";

          $arregloMinoristas[] = $datosMinoristas;
        endif;
       endforeach;

     
      return $arregloMinoristas;
    }       
}
