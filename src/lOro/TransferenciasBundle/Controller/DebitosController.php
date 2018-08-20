<?php

namespace lOro\TransferenciasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use lOro\EntityBundle\Entity\Debitos;
use lOro\TransferenciasBundle\Form\DebitosType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/* Serializadores de Symfony para convertir una entidad en JSON */
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;


/**
 * EntregasMinoristas controller.
 *
 */
class DebitosController extends Controller
{

    
    /**
     * Lists all Debitos entities.
     *
     */
    public function indexAction() {
      $em = $this->getDoctrine()->getManager();
      
      $entities = $em->getRepository('lOroEntityBundle:Debitos')->findAll();
      
      $data['entities'] = $entities;
      return $this->render('lOroTransferenciasBundle:Debitos:index.html.twig', $data);
    }
    
    
    /**
     * Displays a form to create a new Entregas entity.
     *
     */
    public function newAction(Request $request) {
      $em = $this->getDoctrine()->getManager();
      $entity = new Debitos();
      $form   = $this->createCreateForm($entity);

      if($request->isMethod('POST')):
        $form->handleRequest($request);
        if ($form->isValid()):
            
            $em->persist($entity);
            $em->flush();
            
            $this->get('session')->getFlashBag()->set('success', 'El debito N° '.$entity->getNroReferencia().' ha sido registrado satisfactoriamente.');    
            return $this->redirect($this->generateUrl('debitos'));        
        endif;
      endif;

      
        return $this->render('lOroTransferenciasBundle:Debitos:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }    

    /**
     * Displays a form to edit an existing EntregasMinoristas entity.
     */
    public function editAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('lOroEntityBundle:Debitos')->find($id);
      $editForm = $this->createEditForm($entity);
      
      if (!$entity) {
        throw $this->createNotFoundException('Unable to find Debitos entity.');
      }

      
      if($request->isMethod('POST')):
        $editForm->handleRequest($request);
            
        if ($editForm->isValid()):
          $em->persist($entity);
          $em->flush();
           
          $this->get('session')->getFlashBag()->set('success', 'El debito N° '.$entity->getNroReferencia().' ha sido editado satisfactoriamente.');    
          return $this->redirect($this->generateUrl('debitos'));        
        endif;
      endif;
      
      return $this->render('lOroTransferenciasBundle:Debitos:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
      ));
    }

    
    public function showAction($id)
    {
$id = $_POST['id'];
      $em = $this->getDoctrine()->getManager();

      $entity = $em->getRepository('lOroEntityBundle:Debitos')->find($id);

      if (!$entity):
       $dataResponse = 'vacio';
      else:
        $dataResponse['id'] = $entity->getId();
        $date = $entity->getFeDebito();
        $dataResponse['feDebito'] = $date->format('d-m-Y');
        $dataResponse['tipoTransaccion'] = $entity->getTipoTransaccion()->getNbTransaccion();
        
        if($entity->getTipoPago() == 'B'):
          $tipoPago = 'Bolivares';
          $simboloMoneda = 'Bs';
        elseif($entity->getTipoPago() == 'D'):
          $tipoPago = 'Dolares';
          $simboloMoneda = '$';
        else:
          $tipoPago = 'Euros';
          $simboloMoneda = '€';
        endif;
        $dataResponse['tipoPago'] = $tipoPago;
        $dataResponse['monto'] = number_format($entity->getMonto(),'2',',','.').' '.$simboloMoneda;
        $dataResponse['descripcion'] = $entity->getDescripcion();

      endif;


      return new JsonResponse($dataResponse);
    }    
    
    public function deleteAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('lOroEntityBundle:Debitos')->find($id);        

      if (!$entity) {
        throw $this->createNotFoundException('Unable to find Debitos entity.');
      }
            
      $em->remove($entity);
      $em->flush();

      $this->get('session')->getFlashBag()->set('success', 'El debito N° '.$id.' ha sido eliminado satisfactoriamente.');
      return $this->redirect($this->generateUrl('debitos'));
    }      
    
    
    /**
    * Creates a form to create a EntregasMinoristas entity.
    *
    * @param EntregasMinoristas $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Debitos $entity)
    {        
        $form = $this->createForm(new DebitosType($this->getDoctrine()->getManager(),FALSE), $entity, array(
            'action' => $this->generateUrl('debitos_new'),
            'method' => 'POST',
            'attr' => array('id' => 'debitos-form')
        ));

        
        $form->add('submit', 'submit', array('label' => 'Agregar',
                                             'attr' => array('class' =>'btn btn-success')));

        
        
        return $form;
    }
    
    
    /**
    * Creates a form to edit a Entregas entity.
    *
    * @param Entregas $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Debitos $entity)
    {
        $form = $this->createForm(new DebitosType($this->getDoctrine()->getManager(),TRUE), $entity, array(
            'action' => $this->generateUrl('debitos_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('id' => 'debitos-form')
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar',
                                             'attr' => array('class' =>'btn btn-lg btn-success')));

        return $form;
    }
    
}