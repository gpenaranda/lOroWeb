<?php

namespace lOro\EntregasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use lOro\EntityBundle\Entity\EntregasHc;
use lOro\EntityBundle\Entity\Piezas;

use lOro\EntregasBundle\Form\EntregasHcType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/* Serializadores de Symfony para convertir una entidad en JSON */
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;


/**
 * Entregas controller.
 *
 */
class EntregasHcController extends Controller
{

    
    /**
     * Entregas HC, no esta terminado
     */
    public function indexAction()
    {
      $em = $this->getDoctrine()->getManager();
      $form   = $this->createCreateForm();
      $arregloEntregasHc = array();
      
      
      $entregasHc = $em->getRepository('lOroEntityBundle:EntregasHc')->findAll();
      
      if($entregasHc):
        $datosEntregaHc = array();
      
      foreach($entregasHc as $row):
        $gramosEntregados = 0;
      
        $idPiezaInicial = $row->getPiezaInicial()->getId();
        $idPiezaFinal = $row->getPiezaFinal()->getId();
        
        $piezasEntregaHc = $em->getRepository('lOroEntityBundle:Piezas')->getPiezasEntre($idPiezaInicial,$idPiezaFinal);
        
        
        foreach($piezasEntregaHc as $rowPieza):
          $gramosEntregados += $rowPieza['peso_puro_pieza'];
        endforeach;
        
        $datosEntregaHc['codEntrega'] = $row->getCodEntrega();
        $datosEntregaHc['feEntrega'] = $row->getFeEntrega();
        $datosEntregaHc['piezaInicial'] = $row->getPiezaInicial();
        $datosEntregaHc['piezaFinal'] = $row->getPiezaFinal();
        $datosEntregaHc['cantPiezasEntregadas'] = $row->getCantPiezasEntregadas();
        $datosEntregaHc['gramosEntregaHc'] = $gramosEntregados;
        $arregloEntregasHc[] = $datosEntregaHc;
      endforeach;
          
      endif;
      
      
      
      $data['entities'] = $arregloEntregasHc;
      $data['form'] = $form->createView();
      return $this->render('lOroEntregasBundle:EntregasHc:index.html.twig',$data);
    }
    
/**
    * Creates a form to create a Entregas entity.
    *
    * @param Entregas $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm()
    {

        
        $form = $this->createForm(new EntregasHcType($this->getDoctrine()->getManager()),null, array(
            'action' => $this->generateUrl('entregas_hc'),
            'method' => 'POST',
            'attr' => array('id' => 'entregas-form')
        ));

        
        $form->add('submit', 'submit', array('label' => 'Agregar',
                                             'attr' => array('class' =>'btn btn-success')));

        
        
        return $form;
    }  
    
    
    public function agregarEntregaAction(Request $request){
      $entity = new EntregasHc();
      $form = $this->createCreateForm($entity);
      $form->handleRequest($request);
      
      $em = $this->getDoctrine()->getManager();
     
      
      
      if ($form->isValid()) {
        $feEntrega = $form->get('feEntrega')->getData();
        $anio = $feEntrega->format('Y');
        $codPiezaInicial = $form->get('codPiezaInicial')->getData();
        $codPiezaFinal = $form->get('codPiezaFinal')->getData();
        
        $cantPiezasEntregadas = ($codPiezaFinal - $codPiezaInicial) + 1;
        
        $entregasAnteriores = $em->getRepository('lOroEntityBundle:EntregasHc')->findBy(array(),array('feEntrega' => 'DESC'));
        
        $ultimaEntrega = reset($entregasAnteriores);
        
        $codUltimaEntrega = ($ultimaEntrega ? $ultimaEntrega->getCodEntrega() : null);
        $expCodUltEntrega = ($codUltimaEntrega ? explode('-',$codUltimaEntrega) : null);
        $correlativoUltEntrega = ($expCodUltEntrega ? $expCodUltEntrega[4] : 0);
         
        $correlativoEntregas = $correlativoUltEntrega + 1;
        
        
        $codEntrega = 'E-'.$feEntrega->format('y').'-'.$codPiezaInicial.'-'.$codPiezaFinal.'-'.$correlativoEntregas;
        
        $piezaInicial = $em->getRepository('lOroEntityBundle:Piezas')->findOneBy(array('anio' => $anio,'codPieza' => $codPiezaInicial));
        $piezaFinal = $em->getRepository('lOroEntityBundle:Piezas')->findOneBy(array('anio' => $anio,'codPieza' => $codPiezaFinal));
        
        
        
        $entity->setCodEntrega($codEntrega);
        $entity->setCantPiezasEntregadas($cantPiezasEntregadas);
        $entity->setFeEntrega($feEntrega);
        $entity->setAnio($feEntrega->format('y'));
         
         if($piezaInicial): 
           $entity->setPiezaInicial($piezaInicial);
         else:
           $return = 'Pieza Inicial no Encontrada';
         endif;
         
         if ($piezaFinal):
             $entity->setPiezaFinal($piezaFinal);
         else:
             $return = 'Pieza Final no Encontrada';
         endif;
        
          $em->persist($entity);
          $em->flush();
        
          $this->get('session')->getFlashBag()->set('success', 'La entrega Cod. '.$entity->getCodEntrega().' ha sido registrada satisfactoriamente.');
          
          $return = "Cargado";
        } else 
          {
            $return = "Formulario No Valido";  
          }
            
            
      return new Response($return);
    }    

}