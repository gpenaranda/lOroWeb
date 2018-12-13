<?php

namespace lOro\VentasCierreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use lOro\EntityBundle\Entity\VentasCierres;
use lOro\EntityBundle\Entity\MargenesGanancias;
use lOro\VentasCierreBundle\Form\VentasCierresType;
use lOro\VentasCierreBundle\Form\CierresHCType;



/**
 * VentasCierres controller.
 *
 */
class VentasCierresController extends Controller
{

    public function __construct() {
    }

    /**
     * Lists all VentasCierres entities.
     *
     */
    public function indexAction(Request $request)
    {
      $entity = new VentasCierres();
      $em = $this->getDoctrine()->getManager();
      $lugar = $request->get('lugar');
        
      $arregloFiltrosGanancia = $this->getEarningsArrayFilter($lugar);

      $arregloFiltrosCierres['tipoCierre'] = ($lugar == 'proveedores' ? 'proveedor' : 'hc');
      
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

      $data['entities'] = $em->getRepository('lOroEntityBundle:VentasCierres')->getCierresPorMesAnioEnCurso($arregloFiltrosCierres,$arrayIdProvPermitidos);
      
      return $this->render('lOroVentasCierreBundle:VentasCierres:index.html.twig',$data);
    }
    
    

    
    /**
     * 
     *
     */
    public function newAction(Request $request)
    {
      $entity = new VentasCierres();
      $em = $this->getDoctrine()->getManager();
      $lugar = $request->get('lugar');
      $promDolarReferencia = $this->get('loro_datos_generales')->generarPromDolaresReferencia();
       
      $form = $this->createCreateForm($entity,$promDolarReferencia,$lugar);

      $arregloFiltrosGanancia = $this->getEarningsArrayFilter($lugar);
      


      $data['entity'] = $entity;
      $data['form'] = $form->createView();
      $data['ganancia'] = $em->getRepository('lOroEntityBundle:MargenesGanancias')->findOneBy($arregloFiltrosGanancia);
      $data['onzaTroyGramos'] = $em->getRepository('lOroEntityBundle:Parametros')->find(1);

      return $this->render('lOroVentasCierreBundle:VentasCierres:new.html.twig', $data);
    }
    
    
    /**
     * Creates a new VentasCierres entity.
     *
     */
    public function createAction(Request $request)
    {
      $entity = new VentasCierres();
      $em = $this->getDoctrine()->getManager();
      $lugar = $request->get('lugar');
      
      $arregloFiltrosGanancia = $this->getEarningsArrayFilter($lugar);
      $ganancia = $em->getRepository('lOroEntityBundle:MargenesGanancias')->findOneBy($arregloFiltrosGanancia);
      $onzaTroyGramos = $em->getRepository('lOroEntityBundle:Parametros')->find(1);
        
        
      $form = $this->createCreateForm($entity,null,$lugar);
      $arregloFiltrosCierres['tipoCierre'] = ($lugar == 'proveedores' ? 'proveedor' : 'hc');   
        
      
      $form->handleRequest($request);
        

        if ($form->isValid()):
            
         
          
          $arregloFiltrosCierresPrevios['estatus'] = 'A';
          $arregloFiltrosCierresPrevios['tipoCierre'] = ($lugar == 'proveedores' ? 'proveedor' : 'hc');


          $pesoTotalVenta = $form->get('cantidadTotalVenta')->getData();
          
          if($lugar == 'proveedores'):
            $tipoMonedaCierre = $form->get('tipoMonedaCierre')->getData();
            $descuentoOnzaCliente = $form->get('descuentoOnzaCliente')->getData();

            $arregloFiltrosCierresPrevios['proveedorCierre'] = $form->get('proveedorCierre')->getData();

            $valorOnza = $form->get('valorOnza')->getData();
            $montoBsCierrePorGramo = $form->get('montoBsCierrePorGramo')->getData();
            
            
            $dolarReferencia = ($montoBsCierrePorGramo / (($valorOnza /$onzaTroyGramos->getValorParametro()) * $descuentoOnzaCliente));
            $totalMontoDolar = ((($valorOnza /$onzaTroyGramos->getValorParametro()) * $descuentoOnzaCliente ) * $pesoTotalVenta);

            $entity->setDolarReferencia($dolarReferencia);
            $entity->setMontoTotalDolar($totalMontoDolar);
          else:         
            $entity->setDolarReferencia(0);
          endif;
         

          $tipoMonedaCierreHc = $form->get('tipoMonedaCierreHc')->getData();
          
          $entity->setMontoBsCierre(($lugar == 'proveedores' ? ($montoBsCierrePorGramo * $pesoTotalVenta) : 0));
          $entity->setMargenGanancia($ganancia);
          $entity->setTipoCierre(($lugar == 'proveedores' ? 'proveedor' : 'hc'));
          $entity->setTipoMonedaCierreHc($tipoMonedaCierreHc);
          $em->persist($entity);
          $em->flush();
          
          

          $this->get('session')->getFlashBag()->set('success', 'El cierre N° '.$entity->getId().' ha sido creado satisfactoriamente.'); 
          return $this->redirect($this->generateUrl(($lugar == 'proveedores' ? 'ventas_cierres_proveedores' : 'ventas_cierres_hc')));
        endif;

  
      $data['entity'] = $entity;
      $data['form'] = $form->createView();
      $data['ganancia'] = $em->getRepository('lOroEntityBundle:MargenesGanancias')->findOneBy($arregloFiltrosGanancia);
      $data['onzaTroyGramos'] = $em->getRepository('lOroEntityBundle:Parametros')->find(1);

      return $this->render('lOroVentasCierreBundle:VentasCierres:new.html.twig',$data);
    }
    

    
    /**
    * Creates a form to create a VentasCierres entity.
    *
    * @param VentasCierres $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(VentasCierres $entity,$promDolarReferencia = null,$lugar)
    {
      $type = ($lugar == 'proveedores' ? VentasCierresType::class : CierresHcType::class);

      $formOptions['action'] = $this->generateUrl(($lugar == 'proveedores' ? 'ventas_cierres_proveedores_create' : 'ventas_cierres_hc_create'));
      $formOptions['method'] = 'POST';
      $formOptions['attr'] = array('id' => 'proveedores-form');

      if($lugar == 'proveedores'):
        $formOptions['tipoAccion'] = null;
        $formOptions['promReferencia'] = $this->get('loro_datos_generales')->generarPromDolaresReferencia();
        $formOptions['em'] = $this->getDoctrine()->getManager();
      endif;

      $form = $this->createForm($type,$entity,$formOptions);
     


      $form->add('submit',SubmitType::class, array('label' => 'Agregar',
                                                   'attr' => array('class' =>'btn btn-success')));
      
      return $form;
    }




    /**
    * Creates a form to edit a VentasCierres entity.
    *
    * @param VentasCierres $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(VentasCierres $entity,$lugar)
    {
      $type = ($lugar == 'proveedores' ? VentasCierresType::class : CierresHcType::class);

      $formOptions['action'] = $this->generateUrl(($lugar == 'proveedores' ? 'ventas_cierres_proveedores_update' : 'ventas_cierres_hc_update'), array('id' => $entity->getId()));
      $formOptions['method'] = 'PUT';
      $formOptions['attr'] = array('id' => 'proveedores-form');

      if($lugar == 'proveedores'):
        $formOptions['tipoAccion'] = 'editar';
        $formOptions['promReferencia'] = null;
        $formOptions['em'] = $this->getDoctrine()->getManager();
      endif;

        $form = $this->createForm($type,$entity,$formOptions);



        $form->add('submit',SubmitType::class, array('label' => 'Editar',
                                             'attr' => array('class' =>'btn btn-success btn-lg')
                                            ));

        return $form;
    }  


    /**
     * Displays a form to edit an existing VentasCierres entity.
     *
     */
    public function editAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('lOroEntityBundle:VentasCierres')->find($id);
      $place = $request->get('lugar');
        
      
      if (!$entity) throw $this->createNotFoundException('No existe el cierre que se intenta modificar.');

      $editForm = $this->createEditForm($entity,$place);
      
        
      $data['entity'] = $entity;
      $data['form'] = $editForm->createView();
      $data['ganancia'] = $em->getRepository('lOroEntityBundle:MargenesGanancias')->findOneBy($this->getEarningsArrayFilter($place));
      $data['onzaTroyGramos'] = $em->getRepository('lOroEntityBundle:Parametros')->find(1);
      return $this->render('lOroVentasCierreBundle:VentasCierres:edit.html.twig', $data);
    }


    
    /**
     * Edits an existing VentasCierres entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('lOroEntityBundle:VentasCierres')->find($id);
      $lugar = $request->get('lugar');
      
      if (!$entity) throw $this->createNotFoundException('No existe el cierre que se intenta modificar.');

      $editForm = $this->createEditForm($entity,$lugar);
          
      $ganancia = $em->getRepository('lOroEntityBundle:MargenesGanancias')->findOneBy($this->getEarningsArrayFilter($lugar));
      $onzaTroyGramos = $em->getRepository('lOroEntityBundle:Parametros')->find(1);

        
      $editForm->handleRequest($request);

        
      if ($editForm->isValid()) {
        
        if($lugar == 'proveedores'):
          $pesoTotalVenta = $editForm->get('cantidadTotalVenta')->getData();
            
          $montoBsCierrePorGramo = $editForm->get('montoBsCierrePorGramo')->getData();

          $entity->setMontoBsCierre($montoBsCierrePorGramo * $pesoTotalVenta);

          $onzaTroyGramos = $em->getRepository('lOroEntityBundle:Parametros')->find(1);
          $valorOnza = $editForm->get('valorOnza')->getData();
          
          /* Dolares Totales del Cierre */
          $montoTotalDolares = (($valorOnza /$onzaTroyGramos->getValorParametro()) *  $ganancia->getTipoMargen() ) * $pesoTotalVenta;
            
          $entity->setMontoTotalDolar($montoTotalDolares);        
        endif;

        $em->persist($entity);
        $em->flush();            
          
        $this->get('session')->getFlashBag()->add('notice', 'Ha sido editado el Cierre con ID '.$entity->getId());
          
        return $this->redirect($this->generateUrl(($lugar == 'proveedores' ? 'ventas_cierres_proveedores' : 'ventas_cierres_hc')));
        }
        

      $data['entity'] = $entity;
      $data['form'] = $editForm->createView();
      $data['ganancia'] = $ganancia;
      $data['onzaTroyGramos'] = $onzaTroyGramos;        
        
      return $this->render('lOroVentasCierreBundle:VentasCierres:edit.html.twig', $data);
    }


    /**
     * Finds and displays a VentasCierres entity.
     *
     */
    public function showAction()
    {
      $id = $_POST['id'];
      $place = $_POST['place'];
      $em = $this->getDoctrine()->getManager();

      
      $entity = $em->getRepository('lOroEntityBundle:VentasCierres')->find($id);


      if (!$entity) {
        $dataResponse = 'vacio';
      } else {
        $dataResponse['id'] = $entity->getId();
        $date = $entity->getFeVenta();
        $dataResponse['date'] = $date->format('d-m-Y');
        $dataResponse['closedDealGrams'] = number_format($entity->getCantidadTotalVenta(),'2',',','.')." Grs.";
        $dataResponse['ozValue'] = number_format($entity->getValorOnza(),'2',',','.').' '.$entity->getTipoMonedaCierreHc()->getSimboloMoneda().'/Oz';
        $dataResponse['descuentoOnzaProveedor'] = ($entity->getDescuentoOnzaProveedor()*100).' %';
        
        

        if($place == 'hc') {
          $dataResponse['calcFCurrencyTotal'] = number_format($entity->getMontoTotalDolar(),'2',',','.').' '.$entity->getTipoMonedaCierreHc()->getSimboloMoneda();
          $dataResponse['cliente'] = $entity->getCliente()->getAlias ();
          $dataResponse['closedDealFCurrencyHc'] = $entity->getTipoMonedaCierreHc()->getNbMoneda();
        }

        if($place == 'proveedores') {
          $dataResponse['tipoMonedaClienteId'] = $entity->getTipoMonedaCierre()->getId();
          $dataResponse['descuentoOnzaCliente'] = ($entity->getDescuentoOnzaCliente()*100).' %';
          
          /* Solo se coloca la Referencia Bs / Divisa cuando el cierre es en Bs */
          if($entity->getTipoMonedaCierre()->getId() == 1) {
            $dataResponse['fCurrencyDailyRef'] = number_format($entity->getDolarReferenciaDia(),'2',',','.')." Bs./$";
          }
          
          $dataResponse['closedDealBsPayed'] = number_format($entity->getMontoBsCierre(),'2',',','.').' '.$entity->getTipoMonedaCierre()->getSimboloMoneda();
          $dataResponse['supplier'] = $entity->getProveedorCierre()->getNbProveedor();
        }
      }


      return new JsonResponse($dataResponse);
    }    

    /**
     * Deletes a VentasCierres entity.
     *
     */
    public function deleteAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('lOroEntityBundle:VentasCierres')->find($id);
      $lugar = $this->getRequest()->get('lugar');

            
      if (!$entity) {
        throw $this->createNotFoundException('Unable to find VentasCierres entity.');
      }

      $em->remove($entity);
      $em->flush();
        
      $this->get('session')->getFlashBag()->set('error', 'El cierre N° '.$id.' ha sido eliminado del sistema.');  
      return $this->redirect($this->generateUrl(($lugar == 'proveedores' ? 'ventas_cierres_proveedores' : 'ventas_cierres_hc')));
    }



    /**
     * Generates an Array with the filters for the Earnings Entity
     *
     * @param string $place - A string with the place where the Users is accesing
     *
     * @return array $earningsArrayFilter
     **/
    private function getEarningsArrayFilter($place) {
      
      $earningsArrayFilter['estatus'] = 'A';
      $earningsArrayFilter['nbMargenGanancia'] = ($place == 'proveedores' ? 'compra' : 'venta');

      return $earningsArrayFilter;
    }
}
