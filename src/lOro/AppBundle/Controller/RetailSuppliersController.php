<?php

namespace lOro\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use lOro\AppBundle\Form\RetailSuppliers\ClosedDealsType;
use lOro\AppBundle\Entity\RetailSupplierClosedDeals;


class RetailSuppliersController extends Controller
{
	public function indexAction() {   
      $em = $this->getDoctrine()->getManager();
      
      $data['retailSuppliersClosedDeals'] = $em->getRepository(RetailSupplierClosedDeals::class)->findAll();
      return $this->render('lOroAppBundle:RetailSuppliers/ClosedDeals:index.html.twig', $data);
    }

    /**
     * Displays a form to create a new Closed Deal for the retail supplier.
     *
     */
    public function newAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
        $newClosedDeal = new RetailSupplierClosedDeals();
        $form   = $this->createNewClosedDealsForm($newClosedDeal);
        



        if($request->isMethod('POST')):
        	$form->handleRequest($request);
        	if($form->isValid()):
            $em->persist($newClosedDeal);
        		$em->flush();

            $flashMessage = 'El cierre de minorista N° '.$newClosedDeal->getId().' ha sido registrado satisfactoriamente.';
            $this->get('session')->getFlashBag()->set('success', $flashMessage);    
            return $this->redirect($this->generateUrl('retail_supplier_closed_deals_index'));
		      endif;
        endif;
        

        $data['closedDeal'] = $newClosedDeal;
        $data['form'] = $form->createView();

        return $this->render('lOroAppBundle:RetailSuppliers\ClosedDeals:new.html.twig', $data);
    }

    public function editAction(Request $request, $id) {
      $em = $this->getDoctrine()->getManager();
      $closedDeal = $em->getRepository(RetailSupplierClosedDeals::class)->find($id);
        
      
      if (!$closedDeal) throw $this->createNotFoundException('No existe el cierre que se intenta modificar.');

      $editForm = $this->createEditForm($closedDeal);
      
      if($request->isMethod('POST')):
        $editForm->handleRequest($request);
        if($editForm->isValid()):
          $em->persist($closedDeal);
          $em->flush();

          $flashMessage = 'El cierre de minorista N° '.$closedDeal->getId().' ha sido editado satisfactoriamente.';
          $this->get('session')->getFlashBag()->set('info', $flashMessage);    
          return $this->redirect($this->generateUrl('retail_supplier_closed_deals_index'));
        endif;
      endif;

        
      $data['closedDeal'] = $closedDeal;
      $data['form'] = $editForm->createView();
      return $this->render('lOroAppBundle:RetailSuppliers\ClosedDeals:edit.html.twig', $data);
    }

    public function showAction(Request $request) {
      $id = $_POST['id'];
      $em = $this->getDoctrine()->getManager();

      
      $closedDeal = $em->getRepository('lOroAppBundle:RetailSupplierClosedDeals')->find($id);


      if (!$closedDeal):
       $dataResponse = 'vacio';
      else:
        $dataResponse['id'] = $closedDeal->getId();
        $date = $closedDeal->getFeCierre();
        $dataResponse['date'] = $date->format('d-m-Y');
        $dataResponse['fCurrencyPromRef'] = number_format($closedDeal->getDolarReferenciaDia(),'2',',','.');
        $dataResponse['ozValue'] = number_format($closedDeal->getValorOnzaReferencia(),'2',',','.');
        $dataResponse['currencyTypeClosedDeal'] = $closedDeal->getTipoMonedaCierre()->getNbMoneda();
        $dataResponse['rawMassWeight'] = number_format($closedDeal->getPesoBrutoCierre(),'2',',','.')." Grs.";
        $dataResponse['fineness'] = number_format($closedDeal->getLey(),'2',',','.');
        $dataResponse['pureMassWeight'] = number_format($closedDeal->getPesoPuroCierre(),'2',',','.')." Grs.";
        $dataResponse['payedByGram'] = number_format($closedDeal->getMontoBsPorGramo(),'2',',','.').' Bs.';
        $dataResponse['totalPayed'] = number_format($closedDeal->getTotalMontoBs(),'2',',','.').' Bs.';
        $dataResponse['retailSupplier'] = $closedDeal->getMinorista()->getNbProveedor();
      endif;
      


      return new JsonResponse($dataResponse);
    }

    public function deleteAction(Request $request) {
      $em = $this->getDoctrine()->getManager();

      $closedDeal = $em->getRepository(RetailSupplierClosedDeals::class)->find($_POST['id']);
        
      
      if ($closedDeal):
        $deletedClosedDealId = $closedDeal->getId();
        $em->remove($closedDeal);
        $em->flush();
        
        $response = "El cierre de minorista N° $deletedClosedDealId ha sido eliminado satisfactoriamente.";
        $this->get('session')->getFlashBag()->set('danger', $response);
      else:
        $respose = 'vacio';
      endif;

      return new JsonResponse($response);
    } 

  	/**
      * @return \Symfony\Component\Form\Form The form
      */
    private function  createNewClosedDealsForm($closedDealEntity,$isEdit = null) {
    	$form = $this->createForm(ClosedDealsType::class,$closedDealEntity, array(
            'action' => $this->generateUrl('retail_supplier_closed_deals_new'),
            'method' => 'POST',
            'attr' => array('id' => 'rsupplier-closed-deal-form'),
            'fCurrencyPromedyDay' => $this->get('loro_datos_generales')->generarPromDolaresReferencia(),
            'isEdit' => $isEdit
        ));

        $form->add('submit',SubmitType::class, array('label' => 'Agregar',
                                             'attr' => array('class' =>'btn btn-success')));

        return $form;
    }

    /**
      * @return \Symfony\Component\Form\Form The form
      */
    private function  createEditForm($closedDealEntity) {
      $form = $this->createForm(ClosedDealsType::class,$closedDealEntity, array(
            'action' => $this->generateUrl('retail_supplier_closed_deals_edit',array('id' => $closedDealEntity->getId())),
            'method' => 'POST',
            'attr' => array('id' => 'rsupplier-closed-deal-form'),
            'fCurrencyPromedyDay' => $this->get('loro_datos_generales')->generarPromDolaresReferencia()
        ));

        $form->add('submit',SubmitType::class, array('label' => 'Editar',
                                             'attr' => array('class' =>'btn btn-success')));

        return $form;
    }    
}