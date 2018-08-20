<?php

namespace lOro\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use lOro\AppBundle\Entity\EntregasMinoristas;
use lOro\AppBundle\Form\RetailSuppliers\EntregasMinoristasType;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


/**
 * EntregasMinoristas controller.
 *
 */
class EntregasMinoristasController extends Controller
{

    /**
     * Lists all EntregasMinoristas entities.
     *
     */
    public function indexAction()
    {
      $em = $this->getDoctrine()->getManager();
      
      $entities = $em->getRepository('lOroAppBundle:EntregasMinoristas')->findEntregasMinoristas();
      
      $data['entities'] = $entities;
      return $this->render('lOroAppBundle:RetailSuppliers\EntregasMinoristas:index.html.twig', $data);
    }

    public function buscarUbicacionFisicaActualAction() {
      $em = $this->getDoctrine()->getManager();
      $idEntregaMinorista = $_POST['idEntregaMinorista'];
      
      $entregaMinorista = $em->getRepository('lOroAppBundle:EntregasMinoristas')->find($idEntregaMinorista);
      
      
      if($entregaMinorista->getUbicacionFisicaEntrega() == 'P'):
          $ubicacionFisicaActual = 'Con el Provedor';
      elseif($entregaMinorista->getUbicacionFisicaEntrega() == 'O'):
          $ubicacionFisicaActual = 'En Oficina';
      elseif($entregaMinorista->getUbicacionFisicaEntrega() == 'W'):    
          $ubicacionFisicaActual = 'Entregado/Web';
      else:
          $ubicacionFisicaActual = '-';
      endif;
      
      $result['idEntregaMinorista'] = $idEntregaMinorista;
      $result['ubicacionFisicaActual'] = $ubicacionFisicaActual;
      $result['codUbicacionFisica'] = $entregaMinorista->getUbicacionFisicaEntrega();
      
      return new JsonResponse($result);    
    } 

    public function cambiarUbicacionFisicaEntregaAction() {
      $em = $this->getDoctrine()->getManager();
      $idEntregaMinorista = $_POST['idEntregaMinorista'];
      $nuevaUbicacionFisicaEntrega = $_POST["nuevaUbicacionFisicaEntrega"];
      
      
      $entregaMinorista = $em->getRepository('lOroAppBundle:EntregasMinoristas')->find($idEntregaMinorista);
      
      $entregaMinorista->setUbicacionFisicaEntrega($nuevaUbicacionFisicaEntrega);
      $em->persist($entregaMinorista);
      $em->flush();
      

      $result = "exito";
      
      $this->get('session')->getFlashBag()->set('success', 'El cambio de ubicación de la entrega N° '.$entregaMinorista->getId().' fue realizado de manera satisfactoria.');    
      return new JsonResponse($result);         
    }


    /**
     * Creates a form to create a EntregasMinoristas entity.
     *
     * @param EntregasMinoristas $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(EntregasMinoristas $entity)
    {
       $form = $this->createForm(EntregasMinoristasType::class, $entity, array(
            'action' => $this->generateUrl('retail_supplier_deliveries_new'),
            'method' => 'POST',
            'attr' => array('id' => 'entregas-form'),
            'em' => $this->getDoctrine()->getManager(),
            'esEdicion' => FALSE
        ));

        
        $form->add('submit',SubmitType::class, array( 'label' => 'Agregar',
                                                      'attr' => array('class' =>'btn btn-success')));

        
        return $form;
    }

    /**
     * Displays a form to create a new EntregasMinoristas entity.
     *
     */
    public function newAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $entity = new EntregasMinoristas();
      $form   = $this->createCreateForm($entity);

      if($request->isMethod('POST')):
        $form->handleRequest($request);
        if ($form->isValid()):
            
            $em->persist($entity);
            $em->flush();
            
            $this->get('session')->getFlashBag()->set('success', 'La entrega N° '.$entity->getId().' ha sido registrada de manera satisfactoria.');  
            return $this->redirect($this->generateUrl('retail_supplier_deliveries_index'));        
        endif;
      endif;

      
        return $this->render('lOroAppBundle:RetailSuppliers\EntregasMinoristas:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a EntregasMinoristas entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroAppBundle:EntregasMinoristas')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EntregasMinoristas entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('lOroAppBundle:EntregasMinoristas:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing EntregasMinoristas entity.
     *
     */
    public function editAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('lOroAppBundle:EntregasMinoristas')->find($id);
      $editForm = $this->createEditForm($entity);
      
      if (!$entity) {
        throw $this->createNotFoundException('Unable to find Entregas entity.');
      }

      if($request->isMethod('POST')):
           
        $editForm->handleRequest($request);
          
        if ($editForm->isValid()):
          $em->persist($entity);
          $em->flush();
            
          $this->get('session')->getFlashBag()->set('success', 'La entrega de Minoristas N° '.$entity->getId().' ha sido editada satisfactoriamente.');
          
          return $this->redirect($this->generateUrl('retail_supplier_deliveries_index'));        
        endif;
      endif;
      
      return $this->render('lOroAppBundle:RetailSuppliers/EntregasMinoristas:edit.html.twig', array(
            'entity'      => $entity,
            'form_edit'   => $editForm->createView(),
      ));
    }

    /**
    * Creates a form to edit a EntregasMinoristas entity.
    *
    * @param EntregasMinoristas $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(EntregasMinoristas $entity)
    {
        $form = $this->createForm(EntregasMinoristasType::class, $entity, array(
            'action' => $this->generateUrl('retail_supplier_deliveries_edit', array('id' => $entity->getId())),
            'method' => 'POST',
            'attr' => array('id' => 'entregas-form'),
            'em' => $this->getDoctrine()->getManager(),
            'esEdicion' => TRUE
        ));

        $form->add('submit',SubmitType::class, array( 'label' => 'Actualizar',
                                                      'attr' => array('class' =>'btn btn-success')));

        return $form;
    }

    
    /**
     * Deletes a EntregasMinoristas entity.
     *
     */
    public function deleteAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('lOroAppBundle:EntregasMinoristas')->find($id);

      if (!$entity) {
        throw $this->createNotFoundException('Unable to find EntregasMinoristas entity.');
      }


      $deletedEntityId = $entity->getId();

      $em->remove($entity);
      $em->flush();

      $this->get('session')->getFlashBag()->set('error', 'La entrega N° '.$deletedEntityId.' ha sido eliminado de manera satisfactoria.');  
      return $this->redirect($this->generateUrl('retail_supplier_deliveries_index'));
    }
}
