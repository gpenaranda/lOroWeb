<?php

namespace lOro\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use lOro\AppBundle\Form\PagosCargaMasivaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use lOro\AppBundle\Entity\CargasMasivasEnviadas;
use lOro\EntityBundle\Entity\PagosProveedores;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PagosProveedoresController extends Controller
{
    
    public function newCargaMasivaAction()
    {
      $em = $this->getDoctrine()->getManager();
      
      $form = $this->createCreateForm();
      
      
      $data['form'] = $form->createView();
      $data['proveedores'] = $em->getRepository('lOroEntityBundle:Proveedores')->findBy(array('tipoProveedor' => 1, 'status' => 'A'));
      return $this->render('lOroAppBundle:PagosProveedores:new_carga_masiva.html.twig', $data);
    }  
    
  /**
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm()
    {
        $form = $this->createForm(PagosCargaMasivaType::class,null, array(
            'action' => $this->generateUrl('generar_txt_carga_masiva'),
            'method' => 'POST',
            'attr' => array('id' => 'form-pagos-carga-masiva')
        ));

        $form->add('submit',SubmitType::class, array('label' => 'Generar TXT',
                                                     'attr' => array('class' =>'btn btn-success')));

        return $form;
    }     
    
    /*
     * Our Own Company Accounts
     */
    public function buscarCuentasPorEmpresaAction() {
      $idEmpresa = $_POST['idEmpresa'];
      $em = $this->getDoctrine()->getManager();
      $response = array();
      
      $cuentasPorEmpresa = $em->getRepository('lOroEntityBundle:EmpresasBancos')->findBy(array('empresa' => $idEmpresa));
      
      if($cuentasPorEmpresa):
        foreach($cuentasPorEmpresa as $row):
          $data['nroCuenta'] = $row->getNroCuenta();
          $data['banco'] = $row->getBanco()->getNbBanco();
          
          $response[] = $data;
        endforeach; 
      else: 
         $response = 'vacio';
      endif;
      
      
      return new JsonResponse($response);
    }
    
    
    public function buscarPorNroCuentaPorEmpresaAction() {
      $idEmpresa = $_POST['idEmpresa'];
      
      
      $em = $this->getDoctrine()->getManager();
      $response = array();
      
      $cuentasPorEmpresa = $em->getRepository('lOroEntityBundle:EmpresasBancos')->findBy(array('empresa' => $idEmpresa));
      
      if($cuentasPorEmpresa):
        foreach($cuentasPorEmpresa as $row):
          $data['id'] = $row->getNroCuenta();
          $data['value'] = $row->getNroCuenta();
          $data['label'] = $row->getBanco()->getNbBanco().' - '.$row->getNroCuenta();
                  
          $response[] = $data;
        endforeach; 
      else: 
         $response = 'vacio';
      endif;
      
      
      return new JsonResponse($response);
    }    
    

    public function buscarEmpresasPorProveedorAction() {
      $idProveedor = $_POST['idProveedor'];
      $em = $this->getDoctrine()->getManager();
      $response = 'vacio';

      $empresasPorProveedor = $em->getRepository('lOroEntityBundle:EmpresasProveedores')->findBy(array('proveedor' => $idProveedor));
      
      if($empresasPorProveedor):
        $response = array();
        foreach($empresasPorProveedor as $row):

          if($em->getRepository('lOroEntityBundle:EmpresasBancos')->findBy(array('empresa' => $row->getId()))):
            $data['nbEmpresa'] = $row->getNombreEmpresa();
            $data['id'] = $row->getId();
            
            $response[] = $data;
          endif;
        endforeach; 
      endif;
      
      
      return new JsonResponse($response);        
    }
    
    public function generarTxtCargaMasivaAction(Request $request) {
      $em = $this->getDoctrine()->getManager();
      
      $form = $this->createCreateForm();
      
      
      
      if($request->isMethod('POST')):
        $form->handleRequest($request);
        $feActual = new \DateTime('now');
     
        $formReq = $request->request->get('loro_pagos_carga_masiva');
        
        $empresa = $form->get('empresaCasa')->getData();
        $nroCuenta = $formReq['nrosCuenta'];
        $feEjecucion = $form->get('feEjecucion')->getData();
        

         
        /* Buscamos si existen cargas masivas anteriores y generamos el CODIGO PARA CARGA MASIVA */
        $cargasMasivasAnterioresPorMesAnio = $em->getRepository('lOroAppBundle:CargasMasivasEnviadas')->buscarCargasMasivasPorMesAnioCurso($feEjecucion);
        $correlativoCargasMasivas = ($cargasMasivasAnterioresPorMesAnio ? $cargasMasivasAnterioresPorMesAnio['cantidadCargasMesCurso']+1 : 1);
        
        $mesCurso = $feEjecucion->format('m');
        $anioCurso = $feEjecucion->format('Y');
        $codCargaMasiva = ($anioCurso.$mesCurso.$correlativoCargasMasivas);   
        /* FINAL CODIGO CARGA MASIVA */
        
       
        $cargaMasiva = new CargasMasivasEnviadas();
        $cargaMasiva->setFeEjecucion($feEjecucion);
        $cargaMasiva->setFeRegistro($feActual);
        $cargaMasiva->setCodCargaMasiva($codCargaMasiva);
        $em->persist($cargaMasiva);
        $em->flush();
        


        $empresaProveedor = $em->getRepository('lOroEntityBundle:EmpresasProveedores')->find($empresa->getId());
        
        $empresasInstrucciones = $formReq['empresasProveedor'];
        $totalATransferir = 0.00;
        

        foreach($empresasInstrucciones as $posArreglo => $idEmpresa):
          $totalATransferir += str_replace(',','.',str_replace('.','',$formReq['cantidadAEnviar'][$posArreglo]));
        endforeach;        
          

        
        $cantidadTransferencias = count($empresasInstrucciones);
        
        //$totalFinalATransferir = str_replace('.','', $totalATransferir);
        
        echo $empresaProveedor->getRif().';'.$nroCuenta.';'.$cantidadTransferencias.';'.str_replace('.','',number_format($totalATransferir,2,".","")).';'.$feEjecucion->format('Ymd').';'.$codCargaMasiva;

        

        $numRef = 1;
        foreach($empresasInstrucciones as $posArreglo => $idEmpresa):
          $empresaInstruccion = $em->getRepository('lOroEntityBundle:EmpresasProveedores')->find($idEmpresa);
          $rifCortado = substr($empresaInstruccion->getRif(),1);
          $numCod = $empresaInstruccion->getTipoDocumento();
          $nroCuentaEmpIns = $formReq['nroCuenta'][$posArreglo];
          
          /* Agregar el Pago a los Pagos a Proveedor */
          $empresasBancos = $em->getRepository('lOroEntityBundle:EmpresasBancos')->findOneBy(array('empresa' => $empresaInstruccion, 'nroCuenta' => $nroCuentaEmpIns));
          
          $refPagoProveedor = 'PD-'.$codCargaMasiva.'-'.$numRef;
          $this->agregarPagoProveedorCargaMasiva($empresaInstruccion,$empresaProveedor,$feEjecucion,str_replace(',','.',str_replace('.','', $formReq['cantidadAEnviar'][$posArreglo])),$refPagoProveedor,$empresasBancos->getBanco());
          
          echo "\r\n".$numCod->getCodigoBanPlus().";".$rifCortado.';'.$empresaInstruccion->getNombreEmpresa().";".$nroCuentaEmpIns.";".str_replace(',','',str_replace('.','', $formReq['cantidadAEnviar'][$posArreglo])).";;;SI";
          $numRef++;
        endforeach;
      endif;

      
      ob_start();
      $response = new Response();
      $filename = $codCargaMasiva.'.txt';
      $response->headers->set('Content-type', 'text/plain');
      $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($filename) . '";');
      return $response;
    }
    
    protected function agregarPagoProveedorCargaMasiva($empresaInstruccion,$empresaProveedor,$feEjecucion,$montoPagado,$refPagoProveedor,$banco) {
      $em = $this->getDoctrine()->getManager();
      $pagoProveedorEntity = new PagosProveedores();
      
      $tipoTransaccion = $em->getRepository('lOroEntityBundle:TipoTransaccion')->find(2);
      $tipoMoneda = $em->getRepository('lOroEntityBundle:TiposMoneda')->find(1);
      
      $pagoProveedorEntity->setEmpresaPago($empresaInstruccion);
      $pagoProveedorEntity->setFePago($feEjecucion);
      $pagoProveedorEntity->setMontoPagado($montoPagado);
      $pagoProveedorEntity->setTipoTransaccion($tipoTransaccion);
      $pagoProveedorEntity->setNroReferencia($refPagoProveedor);
      $pagoProveedorEntity->setTipoPago('B');
      $pagoProveedorEntity->setTipoMoneda($tipoMoneda);      
      $pagoProveedorEntity->setBanco($banco);
      $pagoProveedorEntity->setEmpresaCasa($empresaProveedor);
      $em->persist($pagoProveedorEntity);
      $em->flush();
      
    }
}

