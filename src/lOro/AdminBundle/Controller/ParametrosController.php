<?php

namespace lOro\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use lOro\EntityBundle\Entity\Parametros;
use lOro\AdminBundle\Form\ParametrosType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Parametros controller.
 *
 */
class ParametrosController extends Controller
{
    
    public function relacionEntregasCierresIndexAction()
    {
        $em = $this->getDoctrine()->getManager();


      
        //$entities = $em->getRepository('lOroEntityBundle:Parametros')->findAll();

        return $this->render('lOroAdminBundle:RelacionEntregasCierres:index.html.twig');
    }
    

    public function relacionEntregasCierresAupanasAction() {
      $em = $this->getDoctrine()->getManager();
      $resultado = 'no-realizada';
      
      //$em->getRepository('lOroEntityBundle:Entregas')->borrarTodosCierresHcEntregas();
      //$em->getRepository('lOroEntityBundle:Entregas')->setearVentasCierres();
      //$em->getRepository('lOroEntityBundle:Entregas')->setearEntregas();
      
      //$entregas = $em->getRepository('lOroEntityBundle:Entregas')->findAll();
      
      
      /* Prueba de Relacion por Piezas y Cierres */
      $piezas = $em->getRepository('lOroEntityBundle:Piezas')->findBy(array(),array('anio' => 'ASC','codPieza' => 'ASC'));
      $em->getRepository('lOroEntityBundle:Piezas')->borrarTodosCierresHcPiezas();
      $em->getRepository('lOroEntityBundle:Piezas')->setearVentasCierres();
      $em->getRepository('lOroEntityBundle:Piezas')->setearPiezas();
      
      $i = 0;
          
      if($piezas):
        foreach($piezas as $pieza):
          //if($pieza->getGramosRestantesRelacion() != 0):
          $this->get('loro_relaciones_registros')->relacionarPiezasConCierresHc($pieza);
          $em->flush();
          //endif;
          
        endforeach;    
        $resultado = 'exito';
      else:
        $resultado = 'error';
      endif;
      
      /*
      if($entregas):
        foreach($entregas as $row):
          //$this->get('loro_relaciones_registros')->relacionarEntregasConCierresHc($row);
        endforeach;
        
        $resultado = 'exito';
      else:
        $resultado = 'error';
      endif;
      */
      
      
      return new JsonResponse($resultado);
    }
    
    public function relacionEntregasCierresProveedoresAction() {
      $em = $this->getDoctrine()->getManager();
      $resultado = 'no-realizada';
      

      
      $proveedores = $em->getRepository('lOroEntityBundle:Proveedores')->findAll();
      
      
      if($proveedores):
        foreach($proveedores as $proveedor):
          
          $em->getRepository('lOroEntityBundle:Entregas')->borrarTodosCierresProveedoresEntregas($proveedor);
          $em->getRepository('lOroEntityBundle:Entregas')->setearVentasCierresProveedor($proveedor);
          $em->getRepository('lOroEntityBundle:Entregas')->setearEntregasRestantePorRelacionProveedor($proveedor);
      
          
          $entregasProveedor = $em->getRepository('lOroEntityBundle:Entregas')->findBy(array('proveedor' => $proveedor),array('feEntrega' => 'ASC'));
          
          if($entregasProveedor):
            foreach($entregasProveedor as $entregaProveedor):
              $this->get('loro_relaciones_registros')->relacionarEntregasConCierresProveedor($entregaProveedor);
            endforeach;
          endif;
          
        endforeach;
        $resultado = 'exito';
      else:
        $resultado = 'error';          
      endif;
          
      
      return new JsonResponse($resultado);
    }
    
    /**
     * Lists all Parametros entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();


      
        $entities = $em->getRepository('lOroEntityBundle:Parametros')->findAll();

        return $this->render('lOroAdminBundle:Parametros:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    
    
    /**
     * Creates a new Parametros entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Parametros();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('parametros_show', array('id' => $entity->getId())));
        }

        return $this->render('lOroAdminBundle:Parametros:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Parametros entity.
    *
    * @param Parametros $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Parametros $entity)
    {
        $form = $this->createForm(new ParametrosType(), $entity, array(
            'action' => $this->generateUrl('parametros_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Parametros entity.
     *
     */
    public function newAction()
    {
        $entity = new Parametros();
        $form   = $this->createCreateForm($entity);

        return $this->render('lOroAdminBundle:Parametros:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Parametros entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:Parametros')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Parametros entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('lOroAdminBundle:Parametros:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Parametros entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:Parametros')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Parametros entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('lOroAdminBundle:Parametros:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Parametros entity.
    *
    * @param Parametros $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Parametros $entity)
    {
        $form = $this->createForm(new ParametrosType(), $entity, array(
            'action' => $this->generateUrl('parametros_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar',
                                             'attr' => array('class' => 'btn btn-lg btn-success')));

        return $form;
    }
    /**
     * Edits an existing Parametros entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:Parametros')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Parametros entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('parametros'));
        }

        return $this->render('lOroAdminBundle:Parametros:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Parametros entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('lOroEntityBundle:Parametros')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Parametros entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('parametros'));
    }

    /**
     * Creates a form to delete a Parametros entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('parametros_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
