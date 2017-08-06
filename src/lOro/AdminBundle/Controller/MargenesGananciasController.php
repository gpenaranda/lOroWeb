<?php

namespace lOro\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use lOro\EntityBundle\Entity\MargenesGanancias;
use lOro\AdminBundle\Form\MargenesGananciasType;

/**
 * MargenesGanancias controller.
 *
 */
class MargenesGananciasController extends Controller
{

    /**
     * Lists all MargenesGanancias entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();


      
        $entities = $em->getRepository('lOroEntityBundle:MargenesGanancias')->findAll();

        return $this->render('lOroAdminBundle:MargenesGanancias:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new MargenesGanancias entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new MargenesGanancias();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('margenes_ganancia_show', array('id' => $entity->getId())));
        }

        return $this->render('lOroAdminBundle:MargenesGanancias:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a MargenesGanancias entity.
    *
    * @param MargenesGanancias $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(MargenesGanancias $entity)
    {
        $form = $this->createForm(new MargenesGananciasType(), $entity, array(
            'action' => $this->generateUrl('margenes_ganancia_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new MargenesGanancias entity.
     *
     */
    public function newAction()
    {
        $entity = new MargenesGanancias();
        $form   = $this->createCreateForm($entity);

        return $this->render('lOroAdminBundle:MargenesGanancias:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a MargenesGanancias entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:MargenesGanancias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MargenesGanancias entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('lOroAdminBundle:MargenesGanancias:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing MargenesGanancias entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:MargenesGanancias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MargenesGanancias entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('lOroAdminBundle:MargenesGanancias:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a MargenesGanancias entity.
    *
    * @param MargenesGanancias $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(MargenesGanancias $entity)
    {
        $form = $this->createForm(new MargenesGananciasType(), $entity, array(
            'action' => $this->generateUrl('margenes_ganancia_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar',
                                             'attr' => array('class' => 'btn btn-lg btn-success')));

        return $form;
    }
    /**
     * Edits an existing MargenesGanancias entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:MargenesGanancias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MargenesGanancias entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('parametros'));
        }

        return $this->render('lOroAdminBundle:MargenesGanancias:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a MargenesGanancias entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('lOroEntityBundle:MargenesGanancias')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MargenesGanancias entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('margenes_ganancia'));
    }

    /**
     * Creates a form to delete a MargenesGanancias entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('margenes_ganancia_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
