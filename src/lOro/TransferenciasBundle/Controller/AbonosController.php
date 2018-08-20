<?php

namespace lOro\TransferenciasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use lOro\EntityBundle\Entity\Abonos;
use lOro\TransferenciasBundle\Form\AbonosType;



/**
 * Abonos controller.
 *
 */
class AbonosController extends Controller
{


    public function indexAction()
    {
      $em = $this->getDoctrine()->getManager();

      $entities = $em->getRepository('lOroEntityBundle:Abonos')->findBy(array(),array('feAbono' => 'DESC','feRegistro' => 'DESC'));
        
      return $this->render('lOroTransferenciasBundle:Abonos:index.html.twig', array(
                           'entities' => $entities
             ));
    }
    
    
    /**
     * Displays a form to create a new PagosVarios entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new Abonos();
        $form   = $this->createCreateForm($entity);
        $form->handleRequest($request);

        
        if ($form->isValid()) {
          $em = $this->getDoctrine()->getManager();
            
          $feRegistro = new \DateTime('now');
          $usuarioRegistrador = $this->getUser();
            
          $entity->setFeRegistro($feRegistro);
          $entity->setUsuarioRegistrador($usuarioRegistrador);
            
          $em->persist($entity);
          $em->flush();
            
            
          //$this->grabarMovimientoEnBanco($entity,'pago-vario',$form->get('montoPago')->getData(),'crear');

          $this->get('session')->getFlashBag()->set('success', 'El abono N° '.$entity->getId().' ha sido registrado satisfactoriamente.');
          return $this->redirect($this->generateUrl('abonos'));
        }        
        

        return $this->render('lOroTransferenciasBundle:Abonos:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }  
    
    

    private function createCreateForm(Abonos $entity)
    {
        $form = $this->createForm(new AbonosType(), $entity, array(
            'action' => $this->generateUrl('abono_new'),
            'method' => 'POST',
            'attr' => array('id' => 'form-abonos')
        ));

        $form->add('submit', 'submit', array('label' => 'Agregar',
                                             'attr' => array('class' => 'btn btn-lg btn-success')));

        return $form;
    }    
    
    private function createEditForm(Abonos $entity)
    {
        $form = $this->createForm(new AbonosType(), $entity, array(
            'action' => $this->generateUrl('abono_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('id' => 'form-abonos')
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar',
                                             'attr' => array('class' => 'btn btn-lg btn-success')));

        return $form;
    }    
    
    

    public function showAction($id)
    {
      $id = $_POST['id'];
      $em = $this->getDoctrine()->getManager();

      $entity = $em->getRepository('lOroEntityBundle:Abonos')->find($id);

      if (!$entity):
       $dataResponse = 'vacio';
      else:
        $dataResponse['id'] = $entity->getId();
        $date = $entity->getFeAbono();
        $dataResponse['feAbono'] = $date->format('d-m-Y');
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
      $entity = $em->getRepository('lOroEntityBundle:Abonos')->find($id);        

      if (!$entity) {
        throw $this->createNotFoundException('Unable to find Abonos entity.');
      }
            
      $em->remove($entity);
      $em->flush();

      $this->get('session')->getFlashBag()->set('success', 'El abono N° '.$id.' ha sido eliminado satisfactoriamente.');
      return $this->redirect($this->generateUrl('abonos'));
    }    
    
    
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:Abonos')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PagosVarios entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
          $em->flush();
            
          $this->get('session')->getFlashBag()->set('success', 'El abono N° '.$id.' ha sido editado satisfactoriamente.');
          return $this->redirect($this->generateUrl('abonos'));
        }

        return $this->render('lOroTransferenciasBundle:Abonos:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ));
    }    
}


