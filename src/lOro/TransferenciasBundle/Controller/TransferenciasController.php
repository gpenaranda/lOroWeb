<?php

namespace lOro\TransferenciasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use lOro\EntityBundle\Entity\Transferencias;
use lOro\TransferenciasBundle\Form\TransferenciasType;
use lOro\EntityBundle\Entity\Banco;
use lOro\EntityBundle\Entity\Balances;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Transferencias controller.
 *
 */
class TransferenciasController extends Controller
{

    /**
     * Lists all Transferencias entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('lOroEntityBundle:Transferencias')->findBy(array('estatus' => array('C','P','D','N')), array('feTransferencia' => 'DESC'));
        
        
        return $this->render('lOroTransferenciasBundle:Transferencias:index.html.twig', array(
            'entities' => $entities,
            'transferenciasHc' => $entities
        ));
    }
    
    /**
     * Creates a new Transferencias entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Transferencias();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
            
        
        if ($form->isValid()) {
            $feActual = new \DateTime();
            
            $tipoTransaccion = $form->get('tipoTransaccion')->getData();
            
            // Si el Estatus es Igual a "Confirmada" se coloca de una vez la Fecha de Confirmacion
            if($form->get('estatus')->getData() == 'C'):
              $entity->setFeConfirmacion($feActual);   
            endif;
            
            // Si NO "Es Conversion"
            if($form->get('esConversion')->getData() == 0):
              $entity->setTipoMonedaConversion($form->get('tipoMonedaTransf')->getData());
              $entity->setMontoAConvertir($form->get('montoTransferencia')->getData());
            endif;
            
            $entity->setTipoTransaccion($tipoTransaccion);
            $em->persist($entity);
            $em->flush();

            $this->grabarMovimientoEnBanco($entity,'transferencia-hc',$form->get('montoTransferencia')->getData(),'crear');

            return $this->redirect($this->generateUrl('transferencias_list'));
        }

        return $this->render('lOroTransferenciasBundle:Transferencias:new.html.twig', array(
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
    protected function grabarMovimientoEnBanco($movimiento,$tipoMovimiento,$montoDolaresTransferencia,$tipoAccion,$montoDolaresTransfVieja = 0) {
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

            $montoDolaresBanco = $montoDolaresUltimaColumnaBanco + $montoDolaresTransferencia;

            $nuevaColumnaBanco = new Banco(); // Se crea nueva columna en el banco


            $nuevaColumnaBanco->setMontoDolares($montoDolaresBanco);
            $nuevaColumnaBanco->setMontoBolivares($montoBolivaresUltimaColumnaBanco);
            $nuevaColumnaBanco->setTipoMovimiento($tipoMovimiento);
            $nuevaColumnaBanco->setIdMovimiento($movimiento->getId());
            $nuevaColumnaBanco->setFeMovimientoBanco($feActual);
            $em->persist($nuevaColumnaBanco);
            $em->flush();            
        elseif($tipoAccion == 'editar'):
          /** Busco la Columna del banco que se genero por este movimiento **/
          $columnaBanco = $em->getRepository('lOroEntityBundle:Banco')->findOneBy(array(
            'tipoMovimiento' => 'transferencia-hc',
            'idMovimiento' => $movimiento->getId()));
            
                      
          $difMontoViejoNuevo = $montoDolaresTransfVieja - $montoDolaresTransferencia;
          
          if($difMontoViejoNuevo != 0):
            $nuevoMontoDolaresColumnaBanco = $columnaBanco->getMontoDolares() - $difMontoViejoNuevo;
          
            $columnaBanco->setMontoDolares($nuevoMontoDolaresColumnaBanco);
            $em->persist($columnaBanco);
            $em->flush();
          
            /** Con la columna que se genero por el movimiento, busco las que fueron generadas despues de esta
              y ella misma para editarles el monto **/
            $columnasBancosParaEdicion = $em->getRepository('lOroEntityBundle:Banco')->findAllColumnasBancoMayoresA($columnaBanco->getId());
          
            foreach($columnasBancosParaEdicion as $row):
              $nuevoMontoDolaresBanco = $row->getMontoDolares() - $difMontoViejoNuevo;
              $row->setMontoDolares($nuevoMontoDolaresBanco);
              $em->persist($row);
              $em->flush();
            endforeach;
          endif;
        elseif($tipoAccion == 'eliminar'):
          /** Busco la Columna del banco que se genero por este movimiento **/
          $columnaBanco = $em->getRepository('lOroEntityBundle:Banco')->findOneBy(array(
            'tipoMovimiento' => 'transferencia-hc',
            'idMovimiento' => $movimiento->getId()));
          
          /** Con la columna que se genero por el movimiento, busco las que fueron 
              generadas despues de esta y ella misma para restarle el monto **/
          $columnasBancosParaEdicion = $em->getRepository('lOroEntityBundle:Banco')->findAllColumnasBancoMayoresA($columnaBanco->getId());
          
          if($columnasBancosParaEdicion):
          
            foreach($columnasBancosParaEdicion as $row):
              $nuevoMontoDolaresBanco = $row->getMontoDolares() - $montoDolaresTransferencia;
         
              $row->setMontoDolares($nuevoMontoDolaresBanco);
              $em->persist($row);
              $em->flush();               
            endforeach;
          endif;
          
          $em->remove($columnaBanco);
          $em->flush();
        endif;
    }
    
    /**
    * Creates a form to create a Transferencias entity.
    *
    * @param Transferencias $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Transferencias $entity)
    {
        $form = $this->createForm(TransferenciasType::class, $entity, array(
            'action' => $this->generateUrl('transferencias_create'),
            'method' => 'POST',
            'attr' => array('id' => 'form-transferencias-hc')
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Agregar',
                                             'attr' => array('class' => 'btn btn-lg btn-success')));

        return $form;
    }

    /**
     * Displays a form to create a new Transferencias entity.
     *
     */
    public function newAction()
    {
        $entity = new Transferencias();
        $form   = $this->createCreateForm($entity);

        
        return $this->render('lOroTransferenciasBundle:Transferencias:new.html.twig', array(
              'entity' => $entity,
              'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Transferencias entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:Transferencias')->find($id);

        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Transferencias entity.');
        }

        return $this->render('lOroTransferenciasBundle:Transferencias:show.html.twig', array(
            'entity'      => $entity,        
            ));
    }

    /**
     * Displays a form to edit an existing Transferencias entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:Transferencias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Transferencias entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('lOroTransferenciasBundle:Transferencias:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Transferencias entity.
    *
    * @param Transferencias $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Transferencias $entity)
    {
        $form = $this->createForm(new TransferenciasType(), $entity, array(
            'action' => $this->generateUrl('transferencias_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('id' => 'form-transferencias-hc')
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar',
                                             'attr' => array('class' => 'btn btn-lg btn-success')));

        return $form;
    }
    /**
     * Edits an existing Transferencias entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:Transferencias')->find($id);
        $montoDolaresTransVieja = $entity->getMontoTransferencia();
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Transferencias entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            
            $tipoTransaccion = $editForm->get('tipoTransaccion')->getData();
            $entity->setTipoTransaccion($tipoTransaccion);
            $em->flush();
            
            //$this->grabarMovimientoEnBanco($entity,'transferencia-hc',$editForm->get('montoTransferencia')->getData(),'editar',$montoDolaresTransVieja);

            return $this->redirect($this->generateUrl('transferencias_list'));
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
      $entity = $em->getRepository('lOroEntityBundle:Transferencias')->find($id);

      if (!$entity) {
        throw $this->createNotFoundException('Unable to find Transferencias entity.');
      }

        $this->grabarMovimientoEnBanco($entity,'transferencia-hc',$entity->getMontoTransferencia(),'eliminar');
        
        $em->remove($entity);
        $em->flush();
            
        
      return $this->redirect($this->generateUrl('transferencias_list'));
    }
    
    public function datosTransferenciasAction($id) {
      $em = $this->getDoctrine()->getManager();
      $transferencia = $em->getRepository('lOroEntityBundle:Transferencias')->find($id);
      $datosTransferencia = null;

      if ($transferencia):
        $fechaTransferencia = $transferencia->getFeTransferencia();
        $datosTransferencia['feTransferencia'] = $fechaTransferencia->format('d-m-Y');
        $datosTransferencia['montoTransferencia'] = number_format($transferencia->getMontoTransferencia(),2,',','.');
      endif;
        
      return new JsonResponse($datosTransferencia);        
    }  
    
    public function confirmarTransferenciasAction($id) {
      $em = $this->getDoctrine()->getManager();
      $transferencia = $em->getRepository('lOroEntityBundle:Transferencias')->find($id);
      $resultado = 'error';

      if ($transferencia):
        $transferencia->setFeConfirmacion(new \DateTime('now'));
        $transferencia->setEstatus('C');
        $em->persist($transferencia);
        $em->flush();
          
        $resultado = 'exito';
      endif;
        
      return new JsonResponse($resultado);        
    }  
    
    public function devolverTransferenciasAction(Request $request) {
      $em = $this->getDoctrine()->getManager();
      $id = $request->request->get('id');
      $transferencia = $em->getRepository('lOroEntityBundle:Transferencias')->find($id);
      $resultado = 'error';

      if ($transferencia):
        $transferencia->setFeDevolucion(new \DateTime('now'));
        $transferencia->setEstatus('D');
        $transferencia->setObservacionDevolucion($request->request->get('observacion'));
        $em->persist($transferencia);
        $em->flush();
          
        $resultado = 'exito';
      endif;
        
      return new JsonResponse($resultado);        
    }
}
