<?php

namespace lOro\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use lOro\EntityBundle\Entity\EmpresasProveedores;
use lOro\AdminBundle\Form\EmpresasProveedoresType;

/**
 * EmpresasProveedores controller.
 *
 */
class EmpresasCasaController extends Controller
{

    /**
     * Lists all EmpresasProveedores entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();


      
        $entities = $em->getRepository('lOroEntityBundle:EmpresasProveedores')->findBy(array('esEmpresaCasa' => true),array('id' => 'DESC'));

        
        return $this->render('lOroAdminBundle:EmpresasCasa:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new EmpresasProveedores entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new EmpresasProveedores();
        $form = $this->createCreateForm($entity,TRUE);
        $form->handleRequest($request);

        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            
            $entity->setEsEmpresaCasa(1);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('empresas-casa', array('id' => $entity->getId())));
        }

        return $this->render('lOroAdminBundle:EmpresasCasa:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a EmpresasProveedores entity.
    *
    * @param EmpresasProveedores $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(EmpresasProveedores $entity, $esEmpresaCasa = null)
    {
        $form = $this->createForm(new EmpresasProveedoresType($esEmpresaCasa), $entity, array(
            'attr' => array('id' => 'empresas-casa-form'),
            'action' => $this->generateUrl('empresas-casa_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Agregar',
                                             'attr' => array('class' => 'btn btn-success', 'style' => 'margin-top: 10px;')));

        return $form;
    }

    /**
     * Displays a form to create a new EmpresasProveedores entity.
     *
     */
    public function newAction()
    {
        $entity = new EmpresasProveedores();
        $form   = $this->createCreateForm($entity,TRUE);

        return $this->render('lOroAdminBundle:EmpresasCasa:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a EmpresasProveedores entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:EmpresasProveedores')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EmpresasProveedores entity.');
        }



        return $this->render('lOroAdminBundle:EmpresasCasa:show.html.twig', array(
            'entity'      => $entity,
            ));
    }

    /**
     * Displays a form to edit an existing EmpresasProveedores entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:EmpresasProveedores')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EmpresasProveedores entity.');
        }

        $editForm = $this->createEditForm($entity,TRUE);

        return $this->render('lOroAdminBundle:EmpresasCasa:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ));
    }

    /**
    * Creates a form to edit a EmpresasProveedores entity.
    *
    * @param EmpresasProveedores $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(EmpresasProveedores $entity,$esEmpresaCasa)
    {
        $form = $this->createForm(new EmpresasProveedoresType($esEmpresaCasa), $entity, array(
            'action' => $this->generateUrl('empresas-casa_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar',
                                             'attr' => array('class' => 'btn btn-lg btn-success',
                                                             'style' => 'margin-top:10px;')));

        return $form;
    }
    /**
     * Edits an existing EmpresasProveedores entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:EmpresasProveedores')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EmpresasProveedores entity.');
        }

        
        $editForm = $this->createEditForm($entity,TRUE);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('empresas-casa_edit', array('id' => $id)));
        }

        return $this->render('lOroAdminBundle:EmpresasCasa:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            
        ));
    }
    /**
     * Deletes a EmpresasProveedores entity.
     *
     */
    public function deleteAction($id)
    {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('lOroEntityBundle:EmpresasProveedores')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EmpresasProveedores entity.');
            }

            $em->remove($entity);
            $em->flush();
        
         return $this->redirect($this->generateUrl('empresas-casa'));
    }
}
