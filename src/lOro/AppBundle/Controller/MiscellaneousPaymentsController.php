<?php

namespace lOro\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use lOro\AppBundle\Form\PagosCargaMasivaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use lOro\AppBundle\Entity\CargasMasivasEnviadas;
use lOro\EntityBundle\Entity\PagosVarios;

class MiscellaneousPaymentsController extends Controller
{

    public function MassiveSalaryPaymentsAction() {   
      $em = $this->getDoctrine()->getManager();
      
      $form = $this->createCreateForm();
      
      
      $data['form'] = $form->createView();
      $data['proveedores'] = $em->getRepository('lOroEntityBundle:Proveedores')->findBy(array('tipoProveedor' => 1));
      return $this->render('lOroAppBundle:MiscellaneousPayments:massive_salary_payments.html.twig', $data);
    }

  /**
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm()
    {
        $form = $this->createForm(new PagosCargaMasivaType(),null, array(
            'action' => $this->generateUrl('_generate_txt_for_salary_payments'),
            'method' => 'POST',
            'attr' => array('id' => 'form-pagos-carga-masiva')
        ));

        $form->add('submit', 'submit', array('label' => 'Generar TXT',
                                             'attr' => array('class' =>'btn btn-success')));

        return $form;
    } 

    /*
      Function that allows search and display the workers that we´re going to use in the Massive Salary Payment Module
    */
    public function SearchWorkersAction() {
      $em = $this->getDoctrine()->getManager();
      $response = array();
      
      $workers = $em->getRepository('lOroEntityBundle:EmpresasProveedores')->findBy(array('isWorker' => true));
      
      if($workers):
        foreach($workers as $row):
          $data['nbEmpresa'] = $row->getNombreEmpresa();
          $data['id'] = $row->getId();
          
          $response[] = $data;
        endforeach; 
      else: 
         $response = 'vacio';
      endif;
      
      
      return new JsonResponse($response);          
    }   

    public function SearchSalaryConceptsAction() {
      $em = $this->getDoctrine()->getManager();
      $response = array();

      $salaryConcepts = $em->getRepository('lOroEntityBundle:TiposPagosVarios')->findBy(array('id' => array(3,4,5)));

      if($salaryConcepts):
        foreach($salaryConcepts as $row):
          $data['salaryConcept'] = $row->getDescripcion();
          $data['id'] = $row->getId();
          
          $response[] = $data;
        endforeach; 
      else: 
         $response = 'vacio';
      endif;
      
      
      return new JsonResponse($response);        
    }    

    public function GenerateTxtForSalaryPaymentsAction(Request $request) {
      $em = $this->getDoctrine()->getManager();
      
      $form = $this->createCreateForm();
      
      if($request->isMethod('POST')):
        $form->handleRequest($request);
        $currentDate = new \DateTime('now');
     
        $formReq = $request->request->get('loro_pagos_carga_masiva');
        
        $ownCompany = $form->get('empresaCasa')->getData();
        $accNumber = $formReq['nrosCuenta'];
        $execDate = $form->get('feEjecucion')->getData();
        
        /* Buscamos si existen cargas masivas anteriores y generamos el CODIGO PARA CARGA MASIVA */
        $previousBulkLoadsByMonthYear = $em->getRepository('lOroAppBundle:CargasMasivasEnviadas')->buscarCargasMasivasPorMesAnioCurso($execDate);
        $correlativeBulkLoads = ($previousBulkLoadsByMonthYear ? $previousBulkLoadsByMonthYear['cantidadCargasMesCurso']+1 : 1);
        
        $currentMonth = $execDate->format('m');
        $currentYear = $execDate->format('Y');
        $bulkLoadCode = ($currentYear.$currentMonth.$correlativeBulkLoads);   
        /* FINAL CODIGO CARGA MASIVA */

        $bulkLoad = new CargasMasivasEnviadas();
        $bulkLoad->setFeEjecucion($execDate);
        $bulkLoad->setFeRegistro($currentDate);
        $bulkLoad->setCodCargaMasiva($bulkLoadCode);
        $em->persist($bulkLoad);
        $em->flush();



        //$empresaProveedor = $em->getRepository('lOroEntityBundle:EmpresasProveedores')->find($empresa->getId());
        
        $instructionWorkers = $request->request->get('workers');
        $transferTotal = 0.00;

        
        
        foreach($instructionWorkers as $arrayPos => $workerId):
          $transferTotal += str_replace(',','.',str_replace('.','',$_POST['cantidadAEnviar'][$arrayPos]));
        endforeach;
        
        $numberOfTransfers = count($instructionWorkers);
        
        echo $ownCompany->getRif().';'.$accNumber.';'.$numberOfTransfers.';'.str_replace('.','',number_format($transferTotal,2,".","")).';'.$execDate->format('Ymd').';'.$bulkLoadCode;

        $refNum = 1;
        foreach($instructionWorkers as $arrayPos => $workerId):
          $instructionWorker = $em->getRepository('lOroEntityBundle:EmpresasProveedores')->find($workerId);
          $splitRif = substr($instructionWorker->getRif(),1);
          $codNum = $instructionWorker->getTipoDocumento();
          $workerAccNumber = $_POST['nroCuenta'][$arrayPos];
          
          /* Add the Payment to Misc Payments */
          $workerBankAcc = $em->getRepository('lOroEntityBundle:EmpresasBancos')->findOneBy(array('empresa' => $instructionWorker, 'nroCuenta' => $workerAccNumber));
          
          $paymentRef = 'PD-'.$bulkLoadCode.'-'.$refNum;

          $this->addMiscPaymentByBulkLoad($execDate,$_POST['salaryPaymentConcept'][$arrayPos],str_replace(',','.',str_replace('.','',$_POST['cantidadAEnviar'][$arrayPos])),$paymentRef,$ownCompany,$currentDate);
          /* Add the Payment to Misc Payments */


          echo "\r\n".$codNum->getCodigoBanPlus().";".$splitRif.';'.$instructionWorker->getNombreEmpresa().";".$workerAccNumber.";".str_replace(',','',str_replace('.','', $_POST['cantidadAEnviar'][$arrayPos])).";;;SI";
          $refNum++;
        endforeach;
      endif;
      
      
      ob_start();
      $response = new Response();
      $filename = $bulkLoadCode.'.txt';
      $response->headers->set('Content-type', 'text/plain');
      $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($filename) . '";');
      return $response; 
    }

    protected function addMiscPaymentByBulkLoad($execDate,$workerPaymentConceptId,$paymentAmount,$paymentRef,$ownCompany,$currentDate) {
      $em = $this->getDoctrine()->getManager();
      $miscPaymentEntity = new PagosVarios();
      $user = $this->getUser();

      $workerPaymentConcept = $em->getRepository('lOroEntityBundle:TiposPagosVarios')->find($workerPaymentConceptId);
      $transactionType = $em->getRepository('lOroEntityBundle:TipoTransaccion')->find(2);

      
      $miscPaymentEntity->setTipoTransaccion($transactionType);
      $miscPaymentEntity->setFePago($execDate);
      $miscPaymentEntity->setDescripcionPago($workerPaymentConcept->getDescripcion());
      $miscPaymentEntity->setMontoPago($paymentAmount);
      $miscPaymentEntity->setNroReferencia($paymentRef);
      $miscPaymentEntity->setEmpresaCasa($ownCompany);
      $miscPaymentEntity->setUsuarioRegistrador($user);
      $miscPaymentEntity->setFeRegistro($currentDate);
      $miscPaymentEntity->setTipoPago('B');
      $miscPaymentEntity->setTipoPagoVario($workerPaymentConcept);
      $miscPaymentEntity->setConciliadoEnCaja(0);

      $em->persist($miscPaymentEntity);
      $em->flush();
    }

protected function agregarPagoProveedorCargaMasiva($empresaInstruccion,$empresaProveedor,$feEjecucion,$montoPagado,$refPagoProveedor,$banco) {
      $em = $this->getDoctrine()->getManager();
      $pagoProveedorEntity = new PagosProveedores();
      
      $tipoTransaccion = $em->getRepository('lOroEntityBundle:TipoTransaccion')->find(2);
      
      $pagoProveedorEntity->setEmpresaPago($empresaInstruccion);
      $pagoProveedorEntity->setFePago($feEjecucion);
      $pagoProveedorEntity->setMontoPagado($montoPagado);
      $pagoProveedorEntity->setTipoTransaccion($tipoTransaccion);
      $pagoProveedorEntity->setNroReferencia($refPagoProveedor);
      $pagoProveedorEntity->setTipoPago('B');
      $pagoProveedorEntity->setBanco($banco);
      $pagoProveedorEntity->setEmpresaCasa($empresaProveedor);
      $em->persist($pagoProveedorEntity);
      $em->flush();
      
    }    
}