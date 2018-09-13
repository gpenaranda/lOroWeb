<?php

namespace lOro\EntregasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use lOro\EntityBundle\Entity\Entregas;
use lOro\EntityBundle\Entity\Piezas;
use lOro\EntityBundle\Entity\VentasEntregas;
use lOro\EntregasBundle\Form\EntregasType;

use lOro\EntregasBundle\Form\EntregaInicialType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
class EntregasController extends Controller
{

    
    /**
     * Lists all Entregas entities.
     *
     */
    public function indexAction()
    {
      $em = $this->getDoctrine()->getManager();
      $piezasInicialesForm = $this->createEntregaInicialForm(new Entregas());

      if($this->get('security.authorization_checker')->isGranted('ROLE_REGISTRADOR_CIERRES')):
        $proveedoresPorUsuario = $em->getRepository('lOroEntityBundle:ProveedoresUsuarios')->findBy(array('user' => $this->getUser()->getId()));

        $arrayIdProvPermitidos = '';
        $cantItems = count($proveedoresPorUsuario);
        $i = 0;
        foreach($proveedoresPorUsuario as $k => $provUser):
          if(++$i === $cantItems):
            $arrayIdProvPermitidos .= $provUser->getProveedor()->getId();
          else:
            $arrayIdProvPermitidos .= $provUser->getProveedor()->getId().',';
          endif;
        endforeach;
      else:
        $arrayIdProvPermitidos = NULL;
      endif;

      
      $entities = $em->getRepository('lOroEntityBundle:Entregas')->buscarPorMesIdMensual($arrayIdProvPermitidos);
      
      
      return $this->render('lOroEntregasBundle:Entregas:index.html.twig', array(
            'entities' => $entities,
            'piezasInicialesForm' => $piezasInicialesForm->createView()
      ));
    }


    /**
    * Creates a form to create the inicial Registry of the Pieces in a Delivery.
    *
    * @param Entregas $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEntregaInicialForm(Entregas $entity)
    {

        
        $form = $this->createForm(EntregaInicialType::class, $entity, array(
            'action' => '#',
            'method' => 'POST',
            'attr' => array('id' => 'initial-delivery-form'),
            'em' => $this->getDoctrine()->getManager()
        ));

        
        $form->add('submit',SubmitType::class, array('label' => 'Agregar',
                                             'attr' => array('class' =>'btn btn-success')));

        
        
        return $form;
    }

    /**
     * NUEVO ESQUEMA PARA AGREGAR LAS ENTREGAS DE PROVEEDORES, INICIALES
     **/
    public function agregarEntregaInicialAction(Request $request) {
      $em = $this->getDoctrine()->getManager();

      

      $formInicial = $_POST['loro_entrega_inicial'];

      if($request->isMethod('POST')):
        $feEntrega = new \DateTime($formInicial['feEntrega']);
        $tipoMoneda = $em->getRepository('lOroEntityBundle:TiposMoneda')->find($formInicial['tipoMonedaEntrega']);
        
        
        $proveedores = $formInicial['proveedor'];
        $piezasIniciales = $formInicial['piezaInicial'];
        $piezasFinales = $formInicial['piezaFinal'];

       
     

        foreach($proveedores as $k => $provId):
 
          /* Entidad Principal de Entregas */
          $entityEntrega = new Entregas();

          $proveedor = $em->getRepository('lOroEntityBundle:Proveedores')->findOneBy(array('id' => $provId));

          

          $entityEntrega->setId($this->generarIdNuevoRegistro('Entregas'));
          $entityEntrega->setProveedor($proveedor);
          $entityEntrega->setFeEntrega($feEntrega);
          $entityEntrega->setTipoMonedaEntrega($tipoMoneda);
          $entityEntrega->setUsuarioId($this->getUser()->getId());
          $entityEntrega->setPesoBrutoEntrega(0);
          $entityEntrega->setLey(900);
          $entityEntrega->setPesoPuroEntrega(0);
          $entityEntrega->setRestantePorRelacion(0);
          $entityEntrega->setRestantePorRelacionProveedor(0);

          $piezaInicialProveedor = $piezasIniciales[$k];
          
          $piezaFinalProveedor = $piezasFinales[$k];

         

          $cantidadPiezas = ($piezaFinalProveedor - $piezaInicialProveedor) + 1;
          //var_dump($cantidadPiezas); die();
           
          for($x = 0;$x < $cantidadPiezas;$x++):
            
            $codPieza = ($x == 0 ? $piezaInicialProveedor : $piezaInicialProveedor+$x);
           
            
            $pieza = new Piezas();

            $pieza->setEntrega($entityEntrega);
          
            /* Año de Registro de la Pieza */
            $pieza->setAnio($feEntrega->format('Y'));
            
            $pieza->setCodPieza($codPieza);
            $pieza->setLeyPieza(900);
            $pieza->setPesoBrutoPieza(0);
            $pieza->setPesoPuroPieza(0);
            $pieza->setTipoMonedaPieza($tipoMoneda);
            
          
            $entityEntrega->addPiezasEntregada($pieza);

          endfor;

          $em->persist($entityEntrega);
          $em->flush();

          /* Mediante la siguiente funcion de la Entidad, se calcula el Nuevo ID Mensual, luego se agrega a la entidad */
          $nuevoIdMensual = $em->getRepository('lOroEntityBundle:Entregas')->buscarMaxIdMensual($entityEntrega->getId(),$entityEntrega->getFeEntrega());
          $entityEntrega->setIdMensual((!$nuevoIdMensual['id_mensual'] ? 1 : $nuevoIdMensual['id_mensual']));
          $em->persist($entityEntrega);
          $em->flush();
        endforeach;

        return new JsonResponse('cargado'); 
      else:
        return new JsonResponse('Error Inesperado'); 
      endif;

      return new JsonResponse('NULL'); 
    }







    

    protected function generarIdNuevoRegistro($repositorio) {
      $usuario = $this->getUser();
      $em = $this->getDoctrine()->getManager();
      $arregloId = array();
      
      /** Buscar el Ultimo registro por el Usuario **/
      $ultimoRegistroUsuario = $em->getRepository('lOroEntityBundle:'.$repositorio)->findAll();
      
      if($ultimoRegistroUsuario):
        foreach($ultimoRegistroUsuario as $row):
          $arregloId[] = $row->getId();
        endforeach;
      
        $id = max($arregloId) + 1;
      else:
        $id = 1;
      endif;

      return $id;
    }
    

    /**
     * Finds and displays a Entregas entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:Entregas')->find($id);
        $arregloPiezas = 'vacio';
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entregas entity.');
        }

        
        $entrega['id'] = $entity->getId();
        $entrega['feEntrega'] = $entity->getFeEntrega()->format('d-m-Y');
        $entrega['pesoBrutoEntrega'] = $entity->getPesoBrutoEntrega();
        $entrega['promLeyEntrega'] = $entity->getLey();
        $entrega['pesoPuroEntrega'] = $entity->getPesoPuroEntrega();
        $entrega['proveedorEntrega'] = $entity->getProveedor()->getNbProveedor();
        $entrega['cantPiezasEntregadas'] = count($entity->getPiezasEntregadas());
        
        if(count($entity->getPiezasEntregadas()) > 0):
          $arregloPiezas = array();
        
          foreach($entity->getPiezasEntregadas() as $piezaEntregada):
            $pieza['id'] = $piezaEntregada->getId();
            $pieza['codPieza'] = $piezaEntregada->getCodPieza();
            $pieza['pesoBrutoPieza'] = $piezaEntregada->getPesoBrutoPieza();
            $pieza['leyPieza'] = $piezaEntregada->getLeyPieza();
            $pieza['pesoPuroPieza'] = $piezaEntregada->getPesoPuroPieza();
          
            $arregloPiezas[$piezaEntregada->getCodPieza()] = $pieza;
          endforeach;
        endif;
        
        $entrega['piezasEntregadas'] = $arregloPiezas;
        
        
        return new JsonResponse($entrega);
    }

    /**
     * Displays a form to edit an existing Entregas entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:Entregas')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entregas entity.');
        }

        $editForm = $this->createEditForm($entity);
        

      
        return $this->render('lOroEntregasBundle:Entregas:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Entregas entity.
    *
    * @param Entregas $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Entregas $entity)
    {
      
        $form = $this->createForm(EntregasType::class, $entity, array(
            'action' => $this->generateUrl('entregas_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('id' => 'entregas-form', 'class' => "form-horizontal"),
            'em' => $this->getDoctrine()->getManager(),
            'esEdicion' => TRUE
        ));

        $form->add('submit',SubmitType::class, array('label' => 'Actualizar',
                                             'attr' => array('class' =>'btn btn-lg btn-success')));

        return $form;
    }


    /**
     * Crear el formato del correo para el envio de los registros de leyes y pesos por proveedor
     */
    public function formatoCorreoActualizacionDeEntrega($proveedor,$entrega){
      $em = $this->getDoctrine()->getManager();
      $correoDestinatario = $proveedor->getEmail();
      $asunto = 'Recepción de Entrega';
      
      $textoMensaje = $this->renderView('/Emails/Proveedores/actualizacion_datos_entrega.html.twig',
                        array('entrega' => $entrega));

       $this->get('loro_datos_generales')->enviarCorreo($asunto, $correoDestinatario, $textoMensaje); 
    }


    /**
     * Edits an existing Entregas entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        
      
        $entity = $em->getRepository('lOroEntityBundle:Entregas')->find($id);
        

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entregas entity.');
        }

        $proveedor = $entity->getProveedor();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()):
            
          $piezasEntregadas = $editForm->get('piezasEntregadas')->getData();
        
          $cantPiezasEntregadas = count($piezasEntregadas);
        
          $pesoBrutoEntrega = 0;
          $totalLeyPiezas = 0;
          $pesoPuroEntrega = 0;
            
          

          /* Actualizacion de los datos de las Piezas asociadas a la Entrega que se esta Editando */
          foreach($piezasEntregadas as $pieza):
            $pieza->setEntrega($entity);
           
            
            $pesoPuroPieza = (((double)$pieza->getPesoBrutoPieza()* (double)$pieza->getLeyPieza()) / 1000);
            $pieza->setPesoPuroPieza((double) $pesoPuroPieza);
            $pieza->setTipoMonedaPieza($editForm->get('tipoMonedaEntrega')->getData());
            
            $pesoBrutoEntrega += $pieza->getPesoBrutoPieza();
            $totalLeyPiezas += $pieza->getLeyPieza();
            $pesoPuroEntrega += $pesoPuroPieza;
          endforeach;
          

          $entity->setPesoBrutoEntrega($pesoBrutoEntrega);
          $promedioLeyEntrega = $totalLeyPiezas/$cantPiezasEntregadas;
          $entity->setLey($promedioLeyEntrega);
          $entity->setPesoPuroEntrega($pesoPuroEntrega);
        
          $em->persist($entity);
          $em->flush();

          /* Si el proveedor posee email asociado */
          if($proveedor->getEmail()):
            $this->formatoCorreoActualizacionDeEntrega($proveedor,$entity);
          endif;

          $this->get('session')->getFlashBag()->set('success', 'La entrega N° '.$entity->getId().' ha sido actualizada satisfactoriamente.'); 
          return $this->redirect($this->generateUrl('entregas'));
        endif;

        return $this->render('lOroEntregasBundle:Entregas:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }


    /**
     * Borra una Entrega
     */
    public function deleteAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('lOroEntityBundle:Entregas')->find($id);

      if (!$entity) {
        throw $this->createNotFoundException('Unable to find Entregas entity.');
      }
        

          
          
            
            /** Busco todas las entregas que esten en la tabla ventas_entregas para eliminarlas **/
            $ventasEntregas = $em->getRepository('lOroEntityBundle:VentasEntregas')->findBy(array('entregas' => $entity));
            
            foreach($ventasEntregas as $row):
              $em->remove($row);
            endforeach;
           
            $piezasEntregadas = $entity->getPiezasEntregadas();
            
            if($piezasEntregadas):
              foreach($piezasEntregadas as $pieza):
                $em->remove($pieza);
                $em->flush();
              endforeach;
            endif;

          
            
        $this->get('session')->getFlashBag()->set('error', 'La entrega N° '.$entity->getId().' ha sido eliminada satisfactoriamente.');    
        $em->remove($entity);
        $em->flush();
        
        return $this->redirect($this->generateUrl('entregas'));
    }
    
    public function deletePiezaAction($id) {
      $em = $this->getDoctrine()->getManager();
      $pieza = $em->getRepository('lOroEntityBundle:Piezas')->find($id);
      

      if (!$pieza):
        $accion = 'No se puede borrar';
      else:
        $accion = 'La pieza N° '.$pieza->getCodPieza().' ha sido borrada satisfactoriamente.';   
      
        /* Antes de borrar la pieza, se debe eliminar todo rastro en la entrega */
        $entrega = $pieza->getEntrega();
        
        $em->remove($pieza);
        $em->flush();   
        
        
        $piezasEntregadas = $entrega->getPiezasEntregadas();
        $cantPiezasNueva = count($piezasEntregadas);
        if($cantPiezasNueva > 0 ):
          $pesoBrutoNuevo = $entrega->getPesoBrutoEntrega() - $pieza->getPesoBrutoPieza();
          $pesoPuroNuevo = $entrega->getPesoPuroEntrega() - $pieza->getPesoPuroPieza();
          $promLey = 0;
          foreach($piezasEntregadas as $row):
            $promLey += (float) $row->getLeyPieza();    
          endforeach;
          $leyNueva = ($promLey / $cantPiezasNueva);
        else:
          $pesoBrutoNuevo = 0;
          $pesoPuroNuevo = 0;
          $promLey = 0;          
        endif;
        
        
        $entrega->setPesoBrutoEntrega($pesoBrutoNuevo);
        $entrega->setPesoPuroEntrega($pesoPuroNuevo);
        $entrega->setLey($promLey);
        $em->persist($entrega);
        $em->flush();
      endif;
        
      return new JsonResponse($accion);        
    }

   
    
    
}
