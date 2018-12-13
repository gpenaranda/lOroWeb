<?php

namespace lOro\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use lOro\EntityBundle\Entity\EmpresasProveedores;
use lOro\AdminBundle\Form\EmpresasProveedoresType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use lOro\EntityBundle\Entity\EmpresasBancos;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;


/* Serializadores */
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


/**
 * EmpresasProveedores controller.
 *
 */
class EmpresasProveedoresController extends Controller
{

    /**
     * Index para listar las Empresas por Proveedores.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new EmpresasProveedores();
        $form   = $this->createCreateForm($entity);
      
        $entities = $em->getRepository('lOroEntityBundle:EmpresasProveedores')->findBy(array('esEmpresaCasa' => false,'isWorker' => false),array('proveedor' => 'DESC'));

        
        $data['entity'] = $entity;
        $data['form'] = $form->createView();
        $data['entities'] = $entities;
        return $this->render('lOroAdminBundle:EmpresasProveedores:index.html.twig', $data);
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

            $this->get('session')->getFlashBag()->set('success', 'La Empresa '.$entity->getNombreEmpresa().' ha sido registrada satisfactoriamente.');    
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
        $form = $this->createForm(EmpresasProveedoresType::class, $entity, array(
            'attr' => array('id' => 'empresa-proveedor-form'),
            'action' => $this->generateUrl('empresas-proveedores_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Register',
                                             'attr' => array('class'    => 'btn btn-lg btn-success pull-right',
                                                             'disabled' => true)
                                            )
                );

        return $form;
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
        $form = $this->createForm(EmpresasProveedoresType::class, $entity, array(
            'action' => $this->generateUrl('empresas-proveedores_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update',
                                             'attr' => array('class' => 'btn btn-success pull-right',
                                                             'disabled' => true)
                                            )
                );

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
            $jsonContent = 'Unable to find EmpresasProveedores entity.';
        } else {
            /* SERIALIZADORES  */
            $normalizer = new ObjectNormalizer();
            $normalizer->setIgnoredAttributes(array('pagosRealizadosCasaMinoristas',
                                                    'pagosRealizadosMinoristas',
                                                    'pagosVariosRealizadosCasa',
                                                    'pagosRealizadosCasa',
                                                    'pagosRealizados',
                                                    'proveedor',
                                                    'tipoDocumento',
                                                    'esEmpresaCasa',
                                                    'isWorker'));
            $encoders = array(new JsonEncoder());
            
            /* Add Circular reference handler */
            $normalizer->setCircularReferenceHandler(function ($object) {
                    return $object->getId();
            });
            
            $normalizers = array($normalizer); 
            $serializer = new Serializer($normalizers, $encoders);
            /* SERIALIZADORES  */
            
            $jsonContent = $serializer->serialize($entity, 'json');
        }

        
        return new Response($jsonContent);
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
            'form'   => $editForm->createView(),
        ));
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
            'form'   => $editForm->createView(),
        ));
    }


    public function buscarBancosSelectNrosCuentaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('lOroEntityBundle:Bancos')->findAll();

        if (!$entity) {
            $jsonContent = 'Unable to find Bancos entity.';
        } else {
            /* SERIALIZADORES  */
            $normalizer = new ObjectNormalizer();
            $encoders = array(new JsonEncoder());
            
            $normalizers = array($normalizer); 
            $serializer = new Serializer($normalizers, $encoders);
            /* SERIALIZADORES  */
            
            $jsonContent = $serializer->serialize($entity, 'json');
        }

        
        return new Response($jsonContent);
    }

    /**  Ajax para agregar nuevas cuentas a una empresa indicada */
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
      
      /**  Ajax para eliminar una cuenta a una empresa indicada */
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
}
