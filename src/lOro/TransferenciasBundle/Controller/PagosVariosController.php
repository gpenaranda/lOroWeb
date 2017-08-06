<?php

namespace lOro\TransferenciasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use lOro\EntityBundle\Entity\PagosVarios;
use lOro\TransferenciasBundle\Form\PagosVariosType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use lOro\EntityBundle\Entity\Banco;
use Symfony\Component\HttpFoundation\Response;

/**
 * PagosVarios controller.
 *
 */
class PagosVariosController extends Controller
{

    /**
     * Lists all PagosVarios entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('lOroEntityBundle:PagosVarios')->findBy(array(),array('fePago' => 'DESC','feRegistro' => 'DESC'));

        $empresasCasa = $em->getRepository('lOroEntityBundle:EmpresasProveedores')->findBy(array('esEmpresaCasa' => true),array('id' => 'DESC'));
        
        
        return $this->render('lOroTransferenciasBundle:PagosVarios:index.html.twig', array(
            'entities' => $entities,
            'empresasCasa' => $empresasCasa
        ));
    }
    
    public function buscarPagosVariosAction() {
    $request = $this->getRequest();
    $em = $this->getDoctrine()->getManager();
    
    $valorBusqueda = $request->get('valorBusqueda');
    $tipoBusqueda = $request->get('tipoBusqueda');
    $fechaInicio = $request->get('fechaInicio');
    $fechaFinal = $request->get('fechaFinal');
    $idEmpresaCasa = $request->get('idEmpresaCasa');
    
   
    
    $jsonData = 'vacio';

      
   
    /* Si el tipo de busqueda es por ID */
    if($tipoBusqueda == 'id'):
      $entities = $em->getRepository('lOroEntityBundle:PagosVarios')->buscarPorId($valorBusqueda);   
    elseif($tipoBusqueda == 'fecha'):
      $entities = $em->getRepository('lOroEntityBundle:PagosVarios')->buscarPorFechas($fechaInicio,$fechaFinal);  
    elseif($tipoBusqueda == 'proveedor'):
      if($idEmpresaCasa != 'vacio'):
        $entities = $em->getRepository('lOroEntityBundle:PagosVarios')->buscarPorEmpresaCasa($idEmpresaCasa);  
      endif;
    else:
      $entities = $em->getRepository('lOroEntityBundle:PagosVarios')->findBy(array(),array('id' => 'DESC'));   
    endif;
    
    $datosEntities = null;
    
    if(isset($entities) and $entities):
      $arregloEntities = array();
        foreach($entities as $entity):
          $fecha = $entity->getFePago();

          
          $datosEntities['id'] = $entity->getId();
          $datosEntities['fecha'] = $fecha->format('d-m-Y');
          $datosEntities['montoPago'] =  number_format($entity->getMontoPago(),2,',','.');
          $datosEntities['pagadoPor'] = $entity->getEmpresaCasa()->getNombreEmpresa();
          $datosEntities['descripcionPago'] = $entity->getDescripcionPago();
            
          $arregloEntities[] = $datosEntities;
        endforeach;
          
          $jsonData = json_encode($arregloEntities);
          
        endif;
      
      return new Response($jsonData);
    }   
    
    /**
     * Creates a new PagosVarios entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new PagosVarios();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $feRegistro = new \DateTime('now');
            $usuarioRegistrador = $this->getUser();
            
            $entity->setFeRegistro($feRegistro);
            $entity->setUsuarioRegistrador($usuarioRegistrador);
            
            $em->persist($entity);
            $em->flush();
            
            
            $this->grabarMovimientoEnBanco($entity,'pago-vario',$form->get('montoPago')->getData(),'crear');

            
            return $this->redirect($this->generateUrl('pagos-varios_show', array('id' => $entity->getId())));
        }

        return $this->render('lOroTransferenciasBundle:PagosVarios:new.html.twig', array(
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
          $nuevoMontoBolivaresBanco = $row->getMontoBolivares() + $movimiento->getMontoPago();
         
          $row->setMontoBolivares($nuevoMontoBolivaresBanco);
          $em->persist($row);
          $em->flush();               
        endforeach;        
      endif;
      
      $em->remove($columnaBanco);
      $em->flush();
    endif;
  }
  
  
    /**
    * Creates a form to create a PagosVarios entity.
    *
    * @param PagosVarios $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(PagosVarios $entity)
    {
        $form = $this->createForm(PagosVariosType::class, $entity, array(
            'action' => $this->generateUrl('pagos-varios_create'),
            'method' => 'POST',
            'attr' => array('id' => 'form-pagos-varios')
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Agregar',
                                             'attr' => array('class' => 'btn btn-lg btn-success')));

        return $form;
    }

    /**
     * Displays a form to create a new PagosVarios entity.
     *
     */
    public function newAction()
    {
        $entity = new PagosVarios();
        $form   = $this->createCreateForm($entity);

        return $this->render('lOroTransferenciasBundle:PagosVarios:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a PagosVarios entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:PagosVarios')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PagosVarios entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('lOroTransferenciasBundle:PagosVarios:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing PagosVarios entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:PagosVarios')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PagosVarios entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('lOroTransferenciasBundle:PagosVarios:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a PagosVarios entity.
    *
    * @param PagosVarios $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(PagosVarios $entity)
    {
        $form = $this->createForm(new PagosVariosType(), $entity, array(
            'action' => $this->generateUrl('pagos-varios_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('id' => 'form-pagos-varios')
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar',
                                             'attr' => array('class' => 'btn btn-lg btn-success')));

        return $form;
    }
    /**
     * Edits an existing PagosVarios entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:PagosVarios')->find($id);
        $montoBolivaresPagoViejo = $entity->getMontoPago();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PagosVarios entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            
            $this->grabarMovimientoEnBanco($entity,'pago-vario',$editForm->get('montoPago')->getData(),'editar',$montoBolivaresPagoViejo);

            return $this->redirect($this->generateUrl('pagos-varios_edit', array('id' => $id)));
        }

        return $this->render('lOroTransferenciasBundle:PagosVarios:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a PagosVarios entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('lOroEntityBundle:PagosVarios')->find($id);        

      if (!$entity) {
        throw $this->createNotFoundException('Unable to find PagosVarios entity.');
      }
            
      //$this->grabarMovimientoEnBanco($entity,'pago-vario',0,'eliminar',0);
            
      $em->remove($entity);
      $em->flush();


      return $this->redirect($this->generateUrl('pagos-varios'));
    }

    /**
     * Creates a form to delete a PagosVarios entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('pagos-varios_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit',SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
