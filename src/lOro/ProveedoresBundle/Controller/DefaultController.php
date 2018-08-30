<?php

namespace lOro\ProveedoresBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    public function indexAction()
    {
      $em = $this->getDoctrine()->getManager();
      $usuario = $usr= $this->get('security.token_storage')->getToken()->getUser();

      $ultimoCierreProveedor = $em->getRepository('lOroEntityBundle:VentasCierres')->findBy(array('proveedorCierre' => $usuario->getProveedor()->getId()),array('id' => 'DESC', 'feVenta'=> 'DESC'));


      $entregasProveedor = $em->getRepository('lOroEntityBundle:Entregas')->findBy(array('proveedor' => $usuario->getProveedor()->getId()),array('id' => 'DESC', 'feEntrega'=> 'DESC'));
      
      

      $data['ultimaEntregaProveedor'] = $entregasProveedor[0];
      $data['ultimoCierreProveedor'] = ($ultimoCierreProveedor ? $ultimoCierreProveedor[0] : null);
      $data['balanceProveedor'] = $this->get('loro_datos_generales')->getBalancePorProveedor($usuario->getProveedor()->getId());
      return $this->render('lOroProveedoresBundle:AppProveedores:index.html.twig',$data);
    }


    public function listadoCierresAction() {
    	$em = $this->getDoctrine()->getManager();

    	//$request = $this->getRequest();
		//$session = $request->getSession();
    	$usuario = $usr= $this->get('security.token_storage')->getToken()->getUser();

    	$cierresProveedor = $em->getRepository('lOroEntityBundle:VentasCierres')->findBy(array('proveedorCierre'=>$usuario->getProveedor()->getId()));
    	

    	$data['cierresProveedor'] = ($cierresProveedor ? $cierresProveedor : null);
    	return $this->render('lOroProveedoresBundle:AppProveedores:listado_cierres.html.twig',$data);
    }



    /* LISTADO DE LAS ENTREGAS
     */
    public function listadoEntregasAction() {
        $em = $this->getDoctrine()->getManager();
        $usuario = $usr= $this->get('security.token_storage')->getToken()->getUser();

        $entregas = $em->getRepository('lOroEntityBundle:Entregas')->findBy(array('proveedor' => $usuario->getProveedor()->getId()),array('feEntrega'=> 'DESC'));




        $data['entregas'] = $entregas;
    	return $this->render('lOroProveedoresBundle:AppProveedores:listado_entregas.html.twig',$data);
    }




    public function listadoPagosAction() {
        $em = $this->getDoctrine()->getManager();
        $usuario = $usr= $this->get('security.token_storage')->getToken()->getUser();


        $arregloEntities = array();
        
        $entities = $em->getRepository('lOroEntityBundle:PagosProveedores')->findUltimoMesPagos($usuario->getProveedor()->getId());
        
        
        if($entities):
          foreach($entities as $row):
            $datosArreglo['id'] = $row['id'];
            $datosArreglo['fePago'] = $row['fe_pago'];
            $datosArreglo['nbTransaccion'] = $row['nb_transaccion'];
            $datosArreglo['tipoPago'] = $row['tipo_pago'];
            $datosArreglo['montoPagado'] = $row['monto_pagado'];
            $datosArreglo['nroReferencia'] = $row['nro_referencia'];
            $datosArreglo['nombreEmpresa'] = $row['nombre_empresa'];
            $datosArreglo['fileExt'] = $row['nro_referencia'].'-'.$row['nb_proveedor'].'.pdf';
            
             $arregloEntities[] = $datosArreglo;
          endforeach;
          
           
        endif;
        
        return $this->render('lOroProveedoresBundle:AppProveedores:listado_pagos.html.twig', array(
            'entities' => $arregloEntities
        ));

    	return $this->render('lOroProveedoresBundle:AppProveedores:listado_pagos.html.twig');
    }

    public function descargarPdfPagoAction($pagoId) {
      $em = $this->getDoctrine()->getManager();
      $folderPath = $this->container->get('kernel')->getRootDir().'/../web/bundles/lOroBundle/files/';


      $usuario = $usr= $this->get('security.token_storage')->getToken()->getUser();
      $proveedor = $usuario->getProveedor();

      $entity = $em->getRepository('lOroEntityBundle:PagosProveedores')->find($pagoId);

      if(!$entity):
        $this->get('session')->getFlashBag()->set('error', 'El pago indicado no existe.');
        return $this->redirect($this->generateUrl('app_prov_pagos'));
      endif;


      $nombreArchivo = $proveedor->getId().$entity->getNroReferencia().'.pdf';
      $nombreFichero = $folderPath.$nombreArchivo;


      if (file_exists($nombreFichero)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header("Content-Type: application/force-download");
        header('Content-Disposition: attachment; filename=' . urlencode(basename($nombreFichero)));
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($nombreFichero));
        ob_clean();
        flush();
        readfile($nombreFichero);
        exit;
      } else {
        $this->get('session')->getFlashBag()->set('error', 'El Archivo PDF para el pago N° '.$entity->getNroReferencia().' no se encuentra disponible en estos momentos.');
        return $this->redirect($this->generateUrl('app_prov_pagos'));
      }

    }

    public function renamePdfAction($tipoProveedores) {
      $dir = $this->container->get('kernel')->getRootDir().'/../web/bundles/lOroBundle/files/052015';
      return new Response($this->listFolderFiles($dir));
    }


    protected function listFolderFiles($dir){
      $em = $this->getDoctrine()->getManager();
      $tipoProveedores = 1;
      $folderPathNew = $this->container->get('kernel')->getRootDir().'/../web/bundles/lOroBundle/files/';
      $folderPathOld = $this->container->get('kernel')->getRootDir().'/../web/bundles/lOroBundle/files/OldFiles/';



    $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));

     
    $cantidadElementos = 1;

    
      foreach ($rii as $file) {

        if ($file->isDir()){ 
          continue;
        }


        $oldFilename = basename($file);
        $rowFileName  = explode("-",$oldFilename);
         echo $file.'<br>';
        //$string = ;

        $arrayNbExt = explode(".",$rowFileName[1]);
        $nbProveedor = $arrayNbExt[0];
        $ext = $arrayNbExt[1];
        
        // Se busca el Proveedor mediante el Nombre del PROVEEDOR y el Tipo de Proveedor  
        $proveedor = $em->getRepository('lOroEntityBundle:Proveedores')->findOneBy(array('nbProveedor' => $nbProveedor, 'tipoProveedor' => $tipoProveedores));
          

        // NRO DE REFERENCIA DEL PDF 
        $nroReferencia = $rowFileName[0];
            

          

        $newFileName = $proveedor->getId().$nroReferencia.'.'.$ext;
        $nombre_fichero = $file->getPathname();



        if (!copy($nombre_fichero, $folderPathNew.$newFileName)) {
            echo "failed to copy $nombre_fichero...<br>";
          } else {
            
            rename($nombre_fichero, $folderPathOld.$oldFilename);
          }



          echo $oldFilename.' >> '.$newFileName.'<br>';

          $cantidadElementos++;

      }


    return '<hr>Cantidad de Elementos Cambiados: '.$cantidadElementos;
  }
}
