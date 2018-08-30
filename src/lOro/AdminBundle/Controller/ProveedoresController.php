<?php

namespace lOro\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use lOro\EntityBundle\Entity\Proveedores;
use lOro\AdminBundle\Form\ProveedoresType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Proveedores controller.
 *
 */
class ProveedoresController extends Controller
{

    /**
     * Lists all Proveedores entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();


      
        $entities = $em->getRepository('lOroEntityBundle:Proveedores')->findAll();

        return $this->render('lOroAdminBundle:Proveedores:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Proveedores entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Proveedores();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('proveedores'));
        }

        return $this->render('lOroAdminBundle:Proveedores:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Proveedores entity.
    *
    * @param Proveedores $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Proveedores $entity)
    {
        $form = $this->createForm(ProveedoresType::Class, $entity, array(
            'attr' => array('id' => 'proveedores-form'),
            'action' => $this->generateUrl('proveedores_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Agregar',
                                             'attr' => array('class' => 'btn btn-lg btn-success pull-right')));

        return $form;
    }

    /**
     * Displays a form to create a new Proveedores entity.
     *
     */
    public function newAction()
    {
        $entity = new Proveedores();
        $form   = $this->createCreateForm($entity);

        return $this->render('lOroAdminBundle:Proveedores:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Proveedores entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $_POST['id'];
      
      
        $entity = $em->getRepository('lOroEntityBundle:Proveedores')->find($id);


      if (!$entity):
       $dataResponse = 'vacio';
      else:
        $dataResponse['id'] = $entity->getId();
        $dataResponse['nbProveedor'] = $entity->getNbProveedor();
        $dataResponse['tipoProveedor'] = $entity->getTipoProveedor()->getNbTipoProveedor();
      endif;


      return new JsonResponse($dataResponse);
    }

    /**
     * Displays a form to edit an existing Proveedores entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:Proveedores')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Proveedores entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('lOroAdminBundle:Proveedores:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Proveedores entity.
    *
    * @param Proveedores $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Proveedores $entity)
    {
        $form = $this->createForm(ProveedoresType::Class, $entity, array(
            'action' => $this->generateUrl('proveedores_update', array('id' => $entity->getId())),
            'method' => 'PUT'
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Actualizar',
                                             'attr' => array('class' => 'btn btn-lg btn-success pull-right')));

        return $form;
    }
    /**
     * Edits an existing Proveedores entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:Proveedores')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Proveedores entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('proveedores'));
        }

        return $this->render('lOroAdminBundle:Proveedores:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ));
    }
    /**
     * Deletes a Proveedores entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
       
            $em = $this->getDoctrine()->getManager();
            $proveedor = $em->getRepository('lOroEntityBundle:Proveedores')->find($id);

            if (!$proveedor) {
                throw $this->createNotFoundException('Unable to find Proveedores entity.');
            }
          
       $empresasProveedores = $em->getRepository('lOroEntityBundle:EmpresasProveedores')->findBy(array('proveedor' => $proveedor));
       
       if($empresasProveedores):
         $this->get('session')->getFlashBag()->set('error', 'El proveedor no puede ser eliminado porque posee empresas asociadas.');
         
         return $this->redirect($this->generateUrl('proveedores_show', array('id' => $id)));
       endif;
       
         $em->remove($proveedor);
         $em->flush();    
           
        $this->get('session')->getFlashBag()->set('success', 'El proveedor ha sido eliminado exitosamente.');
        return $this->redirect($this->generateUrl('proveedores'));
    }

    /**
     * Creates a form to delete a Proveedores entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('proveedores_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
