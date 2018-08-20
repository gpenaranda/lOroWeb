<?php

namespace lOro\EntregasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use lOro\EntregasBundle\Form\AsociarEntregasMinoristasPiezasType;
use lOro\EntityBundle\Entity\EntregasMinoristas;
use lOro\EntregasBundle\Form\EntregasMinoristasType;

//use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
class EntregasMinoristasController extends Controller
{

    
    /**
     * Lists all Entregas entities.
     *
         public function indexAction() {
      $em = $this->getDoctrine()->getManager();
      
      $entities = $em->getRepository('lOroEntityBundle:EntregasMinoristas')->findEntregasMinoristas();
      
      $data['entities'] = $entities;
      return $this->render('lOroEntregasBundle:EntregasMinoristas:index.html.twig', $data);
    }*/
    
    
    /**
     * Displays a form to create a new Entregas entity.
     *
     
    public function newAction(Request $request) {
      $em = $this->getDoctrine()->getManager();
      $entity = new EntregasMinoristas();
      $form   = $this->createCreateForm($entity);

      if($request->isMethod('POST')):
        $form->handleRequest($request);
        if ($form->isValid()):
            
            $em->persist($entity);
            $em->flush();
            
            return $this->redirect($this->generateUrl('entregas_minoristas'));        
        endif;
      endif;

      
        return $this->render('lOroEntregasBundle:EntregasMinoristas:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }  

    */
      

    /**
     * Displays a form to edit an existing EntregasMinoristas entity.
     
    public function editAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('lOroEntityBundle:EntregasMinoristas')->find($id);
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
          
          return $this->redirect($this->generateUrl('entregas_minoristas'));        
        endif;
      endif;
      
      return $this->render('lOroEntregasBundle:EntregasMinoristas:edit.html.twig', array(
            'entity'      => $entity,
            'form_edit'   => $editForm->createView(),
      ));
    }

    */

    
    public function asociarEntregasMinoristasPiezaAction() {
      $em = $this->getDoctrine()->getManager();
      $data = array();
      
      $entregasMinoristas = $em->getRepository('lOroEntityBundle:EntregasMinoristas')->findAll();
      
      
      $data['entities'] = $entregasMinoristas;
      return $this->render('lOroEntregasBundle:EntregasMinoristas:asociar_entregas_minoristas_pieza.html.twig',$data);
    }
    
    public function asociarEntregasMinoristasPiezaNewAction(Request $request) {
      $em = $this->getDoctrine()->getManager();
      $form = $this->createAsociarForm();
      $entregasMinoristas = $em->getRepository('lOroEntityBundle:EntregasMinoristas')->findBy(array('pieza' => NULL),array('feEntrega' => 'DESC'));

      
      $data['form'] = $form->createView();
      $data['entregasMinoristas'] = $entregasMinoristas;

      return $this->render('lOroEntregasBundle:EntregasMinoristas:asociar_entregas_minoristas_pieza_new.html.twig',$data);        
    }
    
    public function asociarChatarraAPiezasAction() {
      $piezaId = $_POST['piezaId'];
      $entregasChatarraSeleccionada = $_POST['chatarraSeleccionada'];
      $em = $this->getDoctrine()->getManager();
      $arregloEntregas = array();
      
      $pieza = $em->getRepository('lOroEntityBundle:Piezas')->find($piezaId);
      $entregasChatarra = $em->getRepository('lOroEntityBundle:EntregasMinoristas')->findBy(array('id' => $entregasChatarraSeleccionada));
      
      
      foreach($entregasChatarra as $row):
        $row->setPieza($pieza);
        $em->persist($row);
        $em->flush();
      endforeach;
      
      $pieza->setPiezaAsociada(TRUE);
      $em->persist($pieza);
      $em->flush();
      
      $this->get('session')->getFlashBag()->set('success', 'Las Entregas Seleccionadas han sido asociadas a la Pieza de Código '.$pieza->getCodPieza());    
      return new JsonResponse("exito");
    }
    
    /*
    public function buscarUbicacionFisicaActualAction() {
      $em = $this->getDoctrine()->getManager();
      $idEntregaMinorista = $_POST['idEntregaMinorista'];
      
      $entregaMinorista = $em->getRepository('lOroEntityBundle:EntregasMinoristas')->find($idEntregaMinorista);
      
      
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
    */   
 
 /*   
    public function cambiarUbicacionFisicaEntregaAction() {
      $em = $this->getDoctrine()->getManager();
      $idEntregaMinorista = $_POST['idEntregaMinorista'];
      $nuevaUbicacionFisicaEntrega = $_POST["nuevaUbicacionFisicaEntrega"];
      
      
      $entregaMinorista = $em->getRepository('lOroEntityBundle:EntregasMinoristas')->find($idEntregaMinorista);
      
      $entregaMinorista->setUbicacionFisicaEntrega($nuevaUbicacionFisicaEntrega);
      $em->persist($entregaMinorista);
      $em->flush();
      
      $result = "exito";
      
      $this->get('session')->getFlashBag()->set('success', 'El cambio de ubicación de la entrega fue realizado de manera satisfactoria.');    
      return new JsonResponse($result);         
    }
   */


    private function createAsociarForm() {
        $form = $this->createForm(new AsociarEntregasMinoristasPiezasType($this->getDoctrine()->getManager(),FALSE), NULL, array(
            'action' => $this->generateUrl('asociacion_chatarra_piezas_new'),
            'method' => 'POST',
            'attr' => array('id' => 'entregas-form')
        ));        
        
        return $form;        
        
    }
    
    /**
    * Creates a form to create a EntregasMinoristas entity.
    *
    * @param EntregasMinoristas $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    
    private function createCreateForm(EntregasMinoristas $entity)
    {        
        $form = $this->createForm(EntregasMinoristasType::class, $entity, array(
            'action' => $this->generateUrl('entregas_minoristas_new'),
            'method' => 'POST',
            'attr' => array('id' => 'entregas-form'),
            'em' => $this->getDoctrine()->getManager(),
            'esEdicion' => FALSE
        ));

        
        $form->add('submit',SubmitType::class, array( 'label' => 'Agregar',
                                                      'attr' => array('class' =>'btn btn-success')));

        
        return $form;
    }
    */
    
    /**
    * Creates a form to edit a Entregas entity.
    *
    * @param Entregas $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    
    private function createEditForm(EntregasMinoristas $entity)
    {
        $form = $this->createForm(EntregasMinoristasType::class, $entity, array(
            'action' => $this->generateUrl('entregas_minoristas_edit', array('id' => $entity->getId())),
            'method' => 'POST',
            'attr' => array('id' => 'entregas-form'),
            'em' => $this->getDoctrine()->getManager(),
            'esEdicion' => TRUE
        ));

        $form->add('submit',SubmitType::class, array( 'label' => 'Actualizar',
                                                      'attr' => array('class' =>'btn btn-lg btn-success')));

        return $form;
    }
    */
    
}