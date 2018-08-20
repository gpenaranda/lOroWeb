<?php

namespace lOro\TransferenciasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use lOro\EntityBundle\Entity\PagosProveedores;
use lOro\TransferenciasBundle\Form\PagosProveedoresType;
use lOro\EntityBundle\Entity\Banco;
use lOro\EntityBundle\Entity\Balances;
use lOro\EntityBundle\Entity\EmpresasProveedores;

use lOro\TransferenciasBundle\Form\UploadFileType;


/**
 * Pagos a Proveedores controller.
 *
 */
class PagosProveedoresController extends Controller
{

    
    /**
     * Lists all Transferencias entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $arregloEntities = array();
        
        $entities = $em->getRepository('lOroEntityBundle:PagosProveedores')->findUltimoMesPagos();
        
        
        if($entities):
          foreach($entities as $row):
            $datosArreglo['id'] = $row['id'];
            $datosArreglo['fePago'] = $row['fe_pago'];
            $datosArreglo['nbTransaccion'] = $row['nb_transaccion'];
            $datosArreglo['tipoPago'] = $row['tipo_pago'];
            $datosArreglo['montoPagado'] = $row['monto_pagado'];
            $datosArreglo['nbProveedor'] = $row['nb_proveedor'];
            $datosArreglo['nombreEmpresa'] = $row['nombre_empresa'];
            
             $arregloEntities[] = $datosArreglo;
          endforeach;
          
           
        endif;
        
        return $this->render('lOroTransferenciasBundle:PagosProveedores:index.html.twig', array(
            'entities' => $arregloEntities
        ));
    }
    
    public function ajaxVerificarNroReferenciaAction() {
      $em = $this->getDoctrine()->getManager();
      
      $nroReferencia = $_POST['nroReferencia'];
      
      $pagoProveedor = $em->getRepository('lOroEntityBundle:PagosProveedores')->buscarPagosPorNroReferencia($nroReferencia);
      
      if($pagoProveedor):
        $encontrado = 'si';
      else:
        $encontrado = 'no';
      endif;
      
      return new JsonResponse($encontrado);  
    }
    
    public function agregarEmpresaProveedorAction(Request $request) {
      $entity = new EmpresasProveedores();
      $em = $this->getDoctrine()->getManager();
      $nombreEmpresa = $_POST['nombre-empresa'];
      $proveedorId = $_POST['proveedor-id'];
      
      $proveedor = $em->getRepository('lOroEntityBundle:Proveedores')->find($proveedorId);
      
      
     
      $entity->setEstatus('A');
      $entity->setNombreEmpresa($nombreEmpresa);
      $entity->setEsEmpresaCasa(false);
      $entity->setProveedor($proveedor);
      
      $em->persist($entity);
      $em->flush();
            
      
      return new JsonResponse(array('nombreEmpresa' => $nombreEmpresa,'empresaId' => $entity->getId()));
    }
    
    public function buscarPagosProveedoresAction() {
    $request = $this->getRequest();
    $em = $this->getDoctrine()->getManager();
    
    $valorBusqueda = $request->get('valorBusqueda');
    $tipoBusqueda = $request->get('tipoBusqueda');
    $fechaInicio = $request->get('fechaInicio');
    $fechaFinal = $request->get('fechaFinal');
    $idProveedor = $request->get('idProveedor');
    
   
    
    $jsonData = 'vacio';
    

      
   
    /* Si el tipo de busqueda es por ID */
    if($tipoBusqueda == 'id'):
      $entities = $em->getRepository('lOroEntityBundle:PagosProveedores')->buscarPorId($valorBusqueda);   
    elseif($tipoBusqueda == 'fecha'):
      $entities = $em->getRepository('lOroEntityBundle:PagosProveedores')->buscarPorFechas($fechaInicio,$fechaFinal);  
    elseif($tipoBusqueda == 'proveedor'):
      if($idProveedor != 'vacio'):
        $entities = $em->getRepository('lOroEntityBundle:PagosProveedores')->buscarPorProveedor($idProveedor);  
      endif;
    else:
      $entities = $em->getRepository('lOroEntityBundle:PagosProveedores')->findBy(array(),array('feRegistro' => 'DESC','fePago' => 'DESC'));   
    endif;
    
    $datosEntities = null;
    
    if($entities):
      $arregloEntities = array();
        foreach($entities as $entity):
          $feha = $entity->getFePago();

          
          $datosEntities['id'] = $entity->getId();
          $datosEntities['fecha'] = $feha->format('d-m-Y');
          $datosEntities['tipoTransaccion'] =  $entity->getTipoTransaccion()->getNbTransaccion();
          $datosEntities['montoPago'] = number_format($entity->getMontoPagado(),2,',','.');
          $datosEntities['proveedor'] = $entity->getEmpresaPago()->getProveedor()->getNbProveedor();
          $datosEntities['empresa'] = $entity->getEmpresaPago()->getNombreEmpresa();
            
          $arregloEntities[] = $datosEntities;
        endforeach;
          
          $jsonData = json_encode($arregloEntities);
          
        endif;
      
      return new Response($jsonData);      
        
    }
    
    /**
     * Creates a new Transferencias entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new PagosProveedores();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
            
        
        if ($form->isValid()) {
            
            $nroReferencia = $form->get('nroReferencia')->getData();
            $tipoTransaccion = $form->get('tipoTransaccion')->getData();
            
           if($tipoTransaccion->getId() != 3):
             $transferenciaRealizada = $em->getRepository('lOroEntityBundle:PagosProveedores')->findOneBy(array('nroReferencia' => $nroReferencia));
            
             if($transferenciaRealizada):
               $this->get('session')->getFlashBag()->set('error', 'El pago N° '.$nroReferencia.' ya ha sido registrado en el sistema.');
               return $this->redirect($this->generateUrl('pagos_proveedores_new')); 
             endif;
           endif;
            
            
            $feRegistro = new \DateTime('now');
            $usuarioRegistrador = $this->getUser();
            
            $entity->setFeRegistro($feRegistro);
            $entity->setUsuarioRegistrador($usuarioRegistrador);
            
            $balance = $this->buscarBalanceActivo();
            $empresaProveedor = $form->get('empresaPago')->getData();
            $tipoTransaccion = $form->get('tipoTransaccion')->getData();
            

            
            $entity->setEmpresaPago($empresaProveedor);
            $entity->setBalance($balance);
            $entity->setTipoTransaccion($tipoTransaccion);
            $em->persist($entity);
            $em->flush();
            
            //$this->grabarMovimientoEnBanco($entity,'pago-proveedor',$form->get('montoPagado')->getData(),'crear');

            return $this->redirect($this->generateUrl('pagos_proveedores_new'));
        }

        return $this->render('lOroTransferenciasBundle:PagosProveedores:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Busca el balance activo o crea uno nuevo
     *
     * @return lOro\EntityBundle\Entity\Balances Balance 
     */
    protected function buscarBalanceActivo() 
    {
     $em = $this->getDoctrine()->getManager();
     
     $balance = $em->getRepository('lOroEntityBundle:Balances')->findOneBy(array('estatus' => 'A'));
     
     if(!$balance):
       $balance = new Balances();
      
       $fechaActual = new \ DateTime('now');
       
       $balance->setFeInicioBalance($fechaActual);
       $balance->setEstatus('A');
       $em->persist($balance);
       $em->flush();
     endif;
     
     return $balance;
    }
    
    /**
     * Funcion que permite guardar el movimiento en el banco
     * 
     * @param entity $movimiento
     * @param string $tipoMovimiento
     * @param integer $montoDolaresTransferencia
     */
    protected function grabarMovimientoEnBanco($movimiento,$tipoMovimiento,$montoPagadoBolivares,$tipoAccion,$montoBolivaresPagoViejo = 0) {
    
    $em = $this->getDoctrine()->getManager();
    $feActual = new \DateTime('now');
    $montoDolaresUltimaColumnaBanco = 0;
    $montoBolivaresUltimaColumnaBanco = 0;
    
    if($tipoAccion == 'crear'):
      /** Se debe buscar en banco para sumar, luego realizar un nuevo registro **/
      $ultimaColumnaBanco = $em->getRepository('lOroEntityBundle:Banco')->findOneByLastRegistroBanco();
   
      if($ultimaColumnaBanco): // Se crea el nuevo Banco y se agrega el monto
        $montoDolaresUltimaColumnaBanco = $ultimaColumnaBanco->getMontoDolares();
        $montoBolivaresUltimaColumnaBanco = $ultimaColumnaBanco->getMontoBolivares();
      endif;
            
      $montoBolivaresBanco = $montoBolivaresUltimaColumnaBanco - $montoPagadoBolivares;
    
      $nuevaColumnaBanco = new Banco(); // Se crea nueva columna en el banco
            
            
      $nuevaColumnaBanco->setMontoDolares($montoDolaresUltimaColumnaBanco);
      $nuevaColumnaBanco->setMontoBolivares($montoBolivaresBanco);
      $nuevaColumnaBanco->setTipoMovimiento($tipoMovimiento);
      $nuevaColumnaBanco->setIdMovimiento($movimiento->getId());
      $nuevaColumnaBanco->setFeMovimientoBanco($feActual);
      $em->persist($nuevaColumnaBanco);
      $em->flush();        
    elseif($tipoAccion == 'editar'):
      /** Busco la Columna del banco que se genero por este movimiento **/
      $columnaBanco = $em->getRepository('lOroEntityBundle:Banco')->findOneBy(array(
        'tipoMovimiento' => 'pago-proveedor',
        'idMovimiento' => $movimiento->getId()));
    
      $difMontoViejoNuevo = $montoBolivaresPagoViejo - $montoPagadoBolivares;
      
      if($difMontoViejoNuevo != 0):
        $nuevoMontoDolaresColumnaBanco = $columnaBanco->getMontoBolivares() + $difMontoViejoNuevo;
          
        $columnaBanco->setMontoBolivares($nuevoMontoDolaresColumnaBanco);
        $em->persist($columnaBanco);
        $em->flush();
          
          
        /** Con la columna que se genero por el movimiento, busco las que fueron generadas despues de esta
            y ella misma para editarles el monto **/
        $columnasBancosParaEdicion = $em->getRepository('lOroEntityBundle:Banco')->findAllColumnasBancoMayoresA($columnaBanco->getId());
          
        foreach($columnasBancosParaEdicion as $row):
          $nuevoMontoBolivaresBanco = $row->getMontoBolivares() + $difMontoViejoNuevo;
            
           $row->setMontoBolivares($nuevoMontoBolivaresBanco);
           $em->persist($row);
           $em->flush();
        endforeach;
      endif;
    elseif($tipoAccion == 'eliminar'):
      /** Busco la Columna del banco que se genero por este movimiento **/
      $columnaBanco = $em->getRepository('lOroEntityBundle:Banco')->findOneBy(array(
        'tipoMovimiento' => 'pago-proveedor',
        'idMovimiento' => $movimiento->getId()));
          
      /** Con la columna que se genero por el movimiento, busco las que fueron 
          generadas despues de esta y ella misma para restarle el monto **/
      $columnasBancosParaEdicion = $em->getRepository('lOroEntityBundle:Banco')->findAllColumnasBancoMayoresA($columnaBanco->getId());
      
      if($columnasBancosParaEdicion):
        foreach($columnasBancosParaEdicion as $row):
          $nuevoMontoBolivaresBanco = $row->getMontoBolivares() + $movimiento->getMontoPagado();
         
          $row->setMontoBolivares($nuevoMontoBolivaresBanco);
          $em->persist($row);
          $em->flush();               
        endforeach;        
      endif;
      
      $em->remove($columnaBanco);
      $em->flush();
    endif;
  }
    
  
    private function createUploadForm() {
      $form = $this->createForm(new UploadFileType(), null, array(
            'action' => $this->generateUrl('pagos_proveedores_new'),
            'method' => 'POST',
            'attr' => array('id' => 'form-pagos-proveedores')
        ));

        $form->add('submit', 'submit', array('label' => 'Cargar Archivo',
                                             'attr' => array('class' => 'btn btn-sm btn-success')));

        return $form;        
    }
    
    /**
    * Creates a form to create a Transferencias entity.
    *
    * @param Transferencias $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(PagosProveedores $entity)
    {
        $form = $this->createForm(new PagosProveedoresType(), $entity, array(
            'action' => $this->generateUrl('pagos_proveedores_create'),
            'method' => 'POST',
            'attr' => array('id' => 'form-pagos-proveedores')
        ));

        $form->add('submit', 'submit', array('label' => 'Agregar',
                                             'attr' => array('class' => 'btn btn-success')));

        return $form;
    }

    public function buscarEmpresasPorProveedorAction()
    {
      $request = $this->getRequest();
      $em = $this->getDoctrine()->getManager();
      $resultado = 'vacio';
      
      $proveedor = $em->getRepository('lOroEntityBundle:Proveedores')->find($request->get('idProveedor'));
      
      $empresasProveedor = $em->getRepository('lOroEntityBundle:EmpresasProveedores')->findBy(array('proveedor' => $proveedor));
      
      if($empresasProveedor):
        foreach($empresasProveedor as $row):
          $data['id'] = $row->getId();
          $data['nbEmpresa'] = $row->getNombreEmpresa();
          
          $arreglo[$row->getId()] = $data;
        endforeach; 
        
        $resultado = json_encode($arreglo);
      endif;
      
      return new Response($resultado);
    }
    
    

    /**
     * Displays a form to create a new Transferencias entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new PagosProveedores();
        $form   = $this->createCreateForm($entity);
        $formUpload   = $this->createUploadForm();
        



        if($request->isMethod('POST')):

          /* Carga de Archivo Mayoristas TipoProveedor ID 1 */
          $datosArchivoPdf = $this->get('loro_datos_generales')->getUploadedFile($request,$form,1);
          
          if($datosArchivoPdf && $datosArchivoPdf['error'] == false):
              
            $montoPagado = str_replace(',','.',str_replace('.','',$datosArchivoPdf['montoPagado']));
          
            $form->get('nroReferencia')->setData($datosArchivoPdf['nroReferencia']);
            $form->get('tipoPago')->setData('B');
            $form->get('proveedor')->setData($datosArchivoPdf['proveedor']);
            $form->get('montoPagado')->setData($montoPagado);
            $form->get('fePago')->setData($datosArchivoPdf['fechaPago']);
            $form->get('tipoTransaccion')->setData($datosArchivoPdf['tipoTransaccion']);
            $form->get('empresaCasa')->setData($datosArchivoPdf['objEmpresaPago']);
          else:
            return $this->redirect($this->generateUrl('pagos_proveedores_new'));   
          endif;
          //var_dump($datosArchivoPdf);
        endif;
        
        return $this->render('lOroTransferenciasBundle:PagosProveedores:new.html.twig', array(
              'entity' => $entity,
              'form'   => $form->createView(),
              'formUpload' => $formUpload->createView(),
              'pdf' => (isset($datosArchivoPdf) ? $datosArchivoPdf['pdf'] : null),
              'nbArchivo' => (isset($datosArchivoPdf) ? $datosArchivoPdf['nbArchivo'] : null),
              'nbProveedor' => (isset($datosArchivoPdf) ? $datosArchivoPdf['nbProveedor'] : null)
        ));
    }

    /**
     * Finds and displays a Transferencias entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:PagosProveedores')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Transferencias entity.');
        }


        return $this->render('lOroTransferenciasBundle:PagosProveedores:show.html.twig', array(
            'entity'      => $entity,      ));
    }

    /**
     * Displays a form to edit an existing Transferencias entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:PagosProveedores')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Transferencias entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('lOroTransferenciasBundle:PagosProveedores:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ));
    }

    /**
    * Creates a form to edit a Transferencias entity.
    *
    * @param Transferencias $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(PagosProveedores $entity)
    {
        $form = $this->createForm(new PagosProveedoresType($entity->getEmpresaPago()->getProveedor()), $entity, array(
            'action' => $this->generateUrl('pagos_proveedores_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('id' => 'form-pago-proveedores')
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar',
                                             'attr' => array('class' => 'btn btn-success')));

        return $form;
    }
    /**
     * Edits an existing Transferencias entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:PagosProveedores')->find($id);
        $montoBolivaresPagoViejo = $entity->getMontoPagado();
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Transferencias entity.');
        }

        
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
         
          
          $this->grabarMovimientoEnBanco($entity,'pago-proveedor',$editForm->get('montoPagado')->getData(),'editar',$montoBolivaresPagoViejo);
          
          $empresaProveedor = $editForm->get('empresaPago')->getData(); 
          $entity->setEmpresaPago($empresaProveedor);
          $em->persist($entity);
          $em->flush();

            return $this->redirect($this->generateUrl('pagos_proveedores'));
        }

        return $this->render('lOroTransferenciasBundle:Transferencias:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ));
    }
    
    /**
     * Deletes a Transferencias entity.
     *
     */
    public function deleteAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('lOroEntityBundle:PagosProveedores')->find($id);

      if (!$entity) {
        throw $this->createNotFoundException('Unable to find Transferencias entity.');
      }

      
      $em->remove($entity);
      $em->flush();
            
      return $this->redirect($this->generateUrl('pagos_proveedores'));
    }
}

