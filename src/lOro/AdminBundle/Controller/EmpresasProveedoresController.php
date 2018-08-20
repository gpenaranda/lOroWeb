<?php

namespace lOro\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use lOro\EntityBundle\Entity\EmpresasProveedores;
use lOro\AdminBundle\Form\EmpresasProveedoresType;
use Symfony\Component\HttpFoundation\JsonResponse;
use lOro\EntityBundle\Entity\EmpresasBancos;

/**
 * EmpresasProveedores controller.
 *
 */
class EmpresasProveedoresController extends Controller
{

    /**
     * Lists all EmpresasProveedores entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();


      
        $entities = $em->getRepository('lOroEntityBundle:EmpresasProveedores')->findBy(array('esEmpresaCasa' => false,'isWorker' => false),array('proveedor' => 'DESC'));

        return $this->render('lOroAdminBundle:EmpresasProveedores:index.html.twig', array(
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
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $entity->setEsEmpresaCasa(0);
            $entity->setIsWorker(0);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('empresas-proveedores', array('id' => $entity->getId())));
        }

        return $this->render('lOroAdminBundle:EmpresasProveedores:new.html.twig', array(
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
    private function createCreateForm(EmpresasProveedores $entity)
    {
        $form = $this->createForm(new EmpresasProveedoresType(), $entity, array(
            'attr' => array('id' => 'empresa-proveedor-form'),
            'action' => $this->generateUrl('empresas-proveedores_create'),
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
        $form   = $this->createCreateForm($entity);

        return $this->render('lOroAdminBundle:EmpresasProveedores:new.html.twig', array(
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
        
        $cuentasEmpresa = $em->getRepository('lOroEntityBundle:EmpresasBancos')->findBy(array('empresa'=>$entity));
        
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('lOroAdminBundle:EmpresasProveedores:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        
            'bancos' => $em->getRepository('lOroEntityBundle:Bancos')->findAll(),
            'cuentasEmpresa' => $cuentasEmpresa
            ));
    }
    
    public function agregarNroCuentaEmpresaAction() {
      $em = $this->getDoctrine()->getManager();
      
      $nroCuenta = str_replace('-','',$_POST['nroCuenta']);
      $empresa = ($em->getRepository('lOroEntityBundle:EmpresasProveedores')->find($_POST['idEmpresa']) ? $em->getRepository('lOroEntityBundle:EmpresasProveedores')->find($_POST['idEmpresa']) : null);
      $banco = ($em->getRepository('lOroEntityBundle:Bancos')->find($_POST['banco']) ? $em->getRepository('lOroEntityBundle:Bancos')->find($_POST['banco']) : null);
            
      if(!$em->getRepository('lOroEntityBundle:EmpresasBancos')->findOneBy(array('empresa'=>$empresa->getId(),'banco'=>$banco->getId(),'nroCuenta' => $nroCuenta))):
        $entity = new EmpresasBancos();
      
        $entity->setEmpresa($empresa);
        $entity->setBanco($banco);
        $entity->setNroCuenta($nroCuenta);
        $em->persist($entity);
        $em->flush();
      
        $response['idBanco'] = $banco->getId();
        $response['banco'] = $banco->getNbBanco();
        $response['nroCuenta'] = $nroCuenta;
        $response['idEmpresa'] = $empresa->getId();
      else:
        $response = 'registrado';   
      endif;
      
      
      return new JsonResponse($response);
    }
    
    public function eliminarNroCuentaEmpresaAction() {
        $em = $this->getDoctrine()->getManager();    
        $idEmpresa = $_POST['idEmpresa'];
        $idBanco = $_POST['idBanco'];
        $nroCuenta = $_POST['nroCuenta'];
        /*busco el numero de cuenta a eliminar*/
        $cuenta=$em->getRepository('lOroEntityBundle:EmpresasBancos')->findOneBy(array('empresa'=>$idEmpresa,'banco'=>$idBanco,'nroCuenta' => $nroCuenta));
      
        if($cuenta):
          $em->remove($cuenta);
          $em->flush();
        endif;
       return new JsonResponse('exito');
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

        $editForm = $this->createEditForm($entity);

        return $this->render('lOroAdminBundle:EmpresasProveedores:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a EmpresasProveedores entity.
    *
    * @param EmpresasProveedores $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(EmpresasProveedores $entity)
    {
        $form = $this->createForm(new EmpresasProveedoresType(), $entity, array(
            'action' => $this->generateUrl('empresas-proveedores_update', array('id' => $entity->getId())),
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

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

          $this->get('session')->getFlashBag()->set('success', 'La Empresa '.$entity->getNombreEmpresa().' ha sido editada satisfactoriamente.');    
          return $this->redirect($this->generateUrl('empresas-proveedores'));
        }

        return $this->render('lOroAdminBundle:EmpresasProveedores:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a EmpresasProveedores entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('lOroEntityBundle:EmpresasProveedores')->find($id);

      if(!$entity->getPagosRealizados()):
        if (!$entity) {
          throw $this->createNotFoundException('Unable to find EmpresasProveedores entity.');
        }
            
        $em->remove($entity);
        $em->flush();
        
        $this->get('session')->getFlashBag()->set('success', 'La empresa ha sido eliminada satisfactoriamente.');
        $redireccion = $this->redirect($this->generateUrl('empresas-proveedores'));
      else:
        $this->get('session')->getFlashBag()->set('error', 'La empresa no puede ser eliminada ya que se posee pagos asociados.');
        $redireccion = $this->redirect($this->generateUrl('empresas-proveedores_show', array('id' => $id)));
      endif;
        
        
        return $redireccion;
    }

    /**
     * Creates a form to delete a EmpresasProveedores entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('empresas-proveedores_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
