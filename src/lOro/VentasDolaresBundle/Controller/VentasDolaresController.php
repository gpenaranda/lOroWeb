<?php

namespace lOro\VentasDolaresBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use lOro\EntityBundle\Entity\VentasDolares;
use lOro\EntityBundle\Entity\VentasDolaresEmpresasCasa;

use lOro\VentasDolaresBundle\Form\VentasDolaresType;
use lOro\EntityBundle\Entity\Banco;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;


/**
 * VentasDolares controller.
 *
 */
class VentasDolaresController extends Controller
{

    /**
     * Lists all VentasDolares entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('lOroEntityBundle:VentasDolares')->findBy(array(), array('fechaVenta' => 'DESC'));

        return $this->render('lOroVentasDolaresBundle:VentasDolares:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    

    
    /**
     * Creates a new VentasDolares entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new VentasDolares();
        $form = $this->createCreateForm($entity,TRUE);
        $form->handleRequest($request);

        
        if ($request->isMethod('POST')):
            $em = $this->getDoctrine()->getManager();
            
            /* Grabar la Entidad Ventas Dolares antes de poder asociar las transferencias por Empresas */
            $em->persist($entity);
            $em->flush();
            
            
            $empresasCasaSeleccionadas = $_POST['empresasCasa'];
            
            
            $concantEmpresas = '';
            
            $i = 0;
            foreach($empresasCasaSeleccionadas as $posArreglo => $empCasaId):
                /* Si solo existe una cantidad a enviar, vamos a realizar de manera directa el cambio de comillas y puntos para que el campo
                * no tenga problemas en la base de datos
                */
                if(count($_POST['cantidadAEnviar']) == 1):
                    $_POST['cantidadAEnviar'][$posArreglo] = str_replace('.','',$_POST['cantidadAEnviar'][$posArreglo]);
                    $_POST['cantidadAEnviar'][$posArreglo] = str_replace(',','.',$_POST['cantidadAEnviar'][$posArreglo]);; 
                endif;

              $ventasDolaresEmpresasCasa = new VentasDolaresEmpresasCasa();
            
              $empresaCasa = $em->getRepository('lOroEntityBundle:EmpresasProveedores')->find($empCasaId);
              $cantidadAEnviar =  $_POST['cantidadAEnviar'][$posArreglo];
              
              

              
              $ventasDolaresEmpresasCasa->setEmpresaCasa($empresaCasa);
              $ventasDolaresEmpresasCasa->setVentaDolares($entity);
              $ventasDolaresEmpresasCasa->setCantidadTransferida($cantidadAEnviar);
              $em->persist($ventasDolaresEmpresasCasa);
              $em->flush();
              
              $concantEmpresas .= ($i == 0 ? $empresaCasa->getNombreEmpresa() : ', '.$empresaCasa->getNombreEmpresa());
              $i++;
            endforeach;

            
            $entity->setEmpresa($concantEmpresas);
            $em->persist($entity);
            $em->flush();
           

            return $this->redirect($this->generateUrl('ventas-dolares'));
        endif;

        return $this->render('lOroVentasDolaresBundle:VentasDolares:new.html.twig', array(
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
    protected function grabarMovimientoEnBanco($movimiento,$tipoMovimiento,$montoBolivaresVentaDolares,$dolaresComprados,$tipoAccion,$dolaresViejos,$bolivaresViejos) {
    
    $em = $this->getDoctrine()->getManager();
    $feActual = new \DateTime('now');
    $montoDolaresUltimaColumnaBanco = 0;
    $montoBolivaresUltimaColumnaBanco = 0;
    $montoBolivares = 0;
    $montoDolaresBanco = 0;
   
    

    

     
    /* Si la accion es crear una nueva venta de dolares,
     * se genera una nuevaColumnaBanco, para agregar la transaccion
     */
    if($tipoAccion == 'crear'):

    /** Se busca la ultimaColumna del Banco **/
    $ultimaColumnaBanco = $em->getRepository('lOroEntityBundle:Banco')->findOneByLastRegistroBanco();
   
    /* Si se consigue una ultimaColumna del banco se toman los datos, de
     * getMontoDolares y getMontoBolivares para ser sumados o restados a los nuevos,
     * sino quedan en 0
     */
    if($ultimaColumnaBanco): 
      $montoDolaresUltimaColumnaBanco = $ultimaColumnaBanco->getMontoDolares();
      $montoBolivaresUltimaColumnaBanco = $ultimaColumnaBanco->getMontoBolivares();
    endif;
    
     /* Se generan los montos de dolares y bolivares para la nueva columna de banco,
      * en la venta de dolares, se suman Bolivares al banco y se Restan Dolares 
      */
     $montoBolivaresBanco = $montoBolivaresUltimaColumnaBanco + $montoBolivaresVentaDolares;
     $montoDolaresBanco = $montoDolaresUltimaColumnaBanco - $dolaresComprados;
     
      $nuevaColumnaBanco = new Banco(); 
    
    
     
     $nuevaColumnaBanco->setMontoDolares($montoDolaresBanco);
     $nuevaColumnaBanco->setMontoBolivares($montoBolivaresBanco);
     $nuevaColumnaBanco->setTipoMovimiento($tipoMovimiento);
     $nuevaColumnaBanco->setIdMovimiento($movimiento->getId());
     $nuevaColumnaBanco->setFeMovimientoBanco($feActual);
     $em->persist($nuevaColumnaBanco);
     $em->flush();
     
    /* Si es una edicion, */
    elseif($tipoAccion == 'edicion'):
            
      /* Solo se puede cambiar el banco cuando los dolares viejos son diferentes
       * a los dolares nuevos
       */
      if($dolaresViejos != $dolaresComprados):
        $columnaBanco = $em->getRepository('lOroEntityBundle:Banco')->findOneBy(array('idMovimiento' => $movimiento->getId(),'tipoMovimiento' => $tipoMovimiento));
 
      /** Se busca la ultimaColumna del Banco con respecto a la columna actual **/
      $ultimaColumnaBanco = $em->getRepository('lOroEntityBundle:Banco')->findColumnaBancoMenorA($columnaBanco->getId());
   
      /* Si se consigue una ultimaColumna del banco se toman los datos, de
       * getMontoDolares y getMontoBolivares para ser sumados o restados a los nuevos,
       * sino quedan en 0
       */
      if($ultimaColumnaBanco): 
        $montoDolaresUltimaColumnaBanco = $ultimaColumnaBanco->getMontoDolares();
        $montoBolivaresUltimaColumnaBanco = $ultimaColumnaBanco->getMontoBolivares();
      endif;
    
      /* Se generan los montos de dolares y bolivares para la nueva columna de banco,
       * en la venta de dolares, se suman Bolivares al banco y se Restan Dolares 
       */
       $montoBolivaresBanco = $montoBolivaresUltimaColumnaBanco + $montoBolivaresVentaDolares;
       $montoDolaresBanco = $montoDolaresUltimaColumnaBanco - $dolaresComprados;
     
       
       $columnaBanco->setMontoBolivares($montoBolivaresBanco);
       $columnaBanco->setMontoDolares($montoDolaresBanco);
       $em->persist($columnaBanco);
       $em->flush();
     
        /* Se saca la diferencia entre el monto viejo y el nuevo */
        $difMontoBolivaresBanco = $bolivaresViejos - $montoBolivaresVentaDolares;
        $difMontoDolaresBanco = $dolaresViejos - $dolaresComprados;
        
       
        /* Se buscan todas las columnas del banco q vienen despues de la actual para cambiarlas */
        $columnasBancoDespuesActual = $em->getRepository('lOroEntityBundle:Banco')->findAllColumnasBancoMayoresA($columnaBanco->getId());
        
        /* Si existen columnas despues de la actual, tenemos q cambiarlas tambien */
        if($columnasBancoDespuesActual):
          foreach($columnasBancoDespuesActual as $columnaBancoDespuesActual):
            
            if($difMontoDolaresBanco < 0):
              $montoBolivaresBanco = $columnaBancoDespuesActual->getMontoBolivares() + abs($difMontoBolivaresBanco);
              $montoDolaresBanco = $columnaBancoDespuesActual->getMontoDolares() - abs($difMontoDolaresBanco);  
            elseif($difMontoDolaresBanco > 0): 
              $montoBolivaresBanco = $columnaBancoDespuesActual->getMontoBolivares() - abs($difMontoBolivaresBanco);
              $montoDolaresBanco = $columnaBancoDespuesActual->getMontoDolares() + abs($difMontoDolaresBanco);  
            endif;
            
            $columnaBancoDespuesActual->setMontoDolares($montoDolaresBanco);
            $columnaBancoDespuesActual->setMontoBolivares($montoBolivaresBanco);
            $em->persist($columnaBancoDespuesActual);
            $em->flush();
          endforeach;

          endif;

      endif;
    
    /* Si se quiere eliminar la venta de dolares, primeramente se tiene que
     * remover la columna creada en la tabla de Banco, y luego de esta las posteriores
     * se tiene que sumar los dolares y restar los bolivares en las columnas indicadas
     */
    elseif($tipoAccion == 'eliminar'):
    
       $columnaBanco = $em->getRepository('lOroEntityBundle:Banco')->findOneBy(array('idMovimiento' => $movimiento->getId(),'tipoMovimiento' => $tipoMovimiento));
    
       /* Se buscan todas las columnas del banco q vienen despues de la actual para cambiarlas */
       $columnasBancoDespuesActual = $em->getRepository('lOroEntityBundle:Banco')->findAllColumnasBancoMayoresA($columnaBanco->getId());
        
       $montoDolares = $movimiento->getCantidadDolaresComprados();
       $montoBolivares = $movimiento->getMontoVentaBolivares();
       
       /* Si existen columnas despues de la actual, se tiene que
        * restar de la columnaBolivares y se debe sumar en la columnaDolares 
        */
       if($columnasBancoDespuesActual):
         foreach($columnasBancoDespuesActual as $columnaBancoDespuesActual):
           
           $montoBolivaresBanco = $columnaBancoDespuesActual->getMontoBolivares() - $montoBolivares;
           $montoDolaresBanco = $columnaBancoDespuesActual->getMontoDolares() + $montoDolares;  
           
           $columnaBancoDespuesActual->setMontoDolares($montoDolaresBanco);
           $columnaBancoDespuesActual->setMontoBolivares($montoBolivaresBanco);
           $em->persist($columnaBancoDespuesActual);
           $em->flush();
         endforeach;
       endif;
       
       $em->remove($columnaBanco);
       $em->flush();
    endif;

   

    

    
    
            
            

    }
    

    /**
    * Creates a form to create a VentasDolares entity.
    *
    * @param VentasDolares $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(VentasDolares $entity,$compradorDolares = FALSE)
    {
        $form = $this->createForm(VentasDolaresType::class, $entity, array(
            'action' => $this->generateUrl('ventas-dolares_create'),
            'method' => 'POST',
            'attr' => array('id' => 'form-ventas-dolares'),
            'compradorDolares' => $compradorDolares
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Agregar',
                                             'attr' => array('class' => 'btn btn-lg btn-success', 'style' => 'margin-top:10px;')));

        return $form;
    }

    /**
     * Displays a form to create a new VentasDolares entity.
     *
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new VentasDolares();
        $form   = $this->createCreateForm($entity,TRUE);

        $empresasCasa = $em->getRepository('lOroEntityBundle:EmpresasProveedores')->findBy(array('esEmpresaCasa' => TRUE));
        
        
        return $this->render('lOroVentasDolaresBundle:VentasDolares:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'empresasCasa' => $empresasCasa
        ));
    }

    /**
     * Finds and displays a VentasDolares entity.
     *
     */
    public function showAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('lOroEntityBundle:VentasDolares')->find($id);
      $resultado = 'vacio';
      
      if($entity):
        $depositosPorCuenta = $em->getRepository('lOroEntityBundle:VentasDolaresEmpresasCasa')->findBy(array('ventaDolares' => $entity->getId()));
      
        $feVenta = $entity->getFechaVenta();
      
        $resultado = array();
        $resultado['id'] = $entity->getId();
        $resultado['fechaVenta'] = $feVenta->format('d-m-Y');
        $resultado['cantidadDolaresComprados'] = number_format($entity->getCantidadDolaresComprados(),2,',','.');
        $resultado['dolarReferencia'] = number_format($entity->getDolarReferencia(),2,',','.');
        $resultado['montoVentaBolivares'] = number_format($entity->getMontoVentaBolivares(),2,',','.');
        $resultado['tipoMoneda'] = $entity->getTipoMoneda()->getNbMoneda();
        $resultado['simboloMoneda'] = $entity->getTipoMoneda()->getSimboloMoneda();
        $resultado['comprador'] = $entity->getComprador()->getNbProveedor();
        $resultado['empresaVenta'] = $entity->getEmpresa();
        
        $arregloDepPorCuentas = array();
        if($depositosPorCuenta):
          foreach($depositosPorCuenta as $depPorCuenta):
            $dataDepPorCuentas['cuenta'] = $depPorCuenta->getEmpresaCasa()->getNombreEmpresa();
            $dataDepPorCuentas['cantidadDepositada'] = number_format($depPorCuenta->getCantidadTransferida(),2,',','.');
            
            $arregloDepPorCuentas[] = $dataDepPorCuentas;
          endforeach;    
        endif;
        
        $resultado['depositosPorCuenta'] = $arregloDepPorCuentas;
      endif;
        
      return new JsonResponse($resultado);
    }

    /**
     * Displays a form to edit an existing VentasDolares entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:VentasDolares')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VentasDolares entity.');
        }

        $editForm = $this->createEditForm($entity,TRUE);

        return $this->render('lOroVentasDolaresBundle:VentasDolares:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a VentasDolares entity.
    *
    * @param VentasDolares $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(VentasDolares $entity,$compradorDolares = FALSE)
    {
        $form = $this->createForm(new VentasDolaresType($compradorDolares), $entity, array(
            'action' => $this->generateUrl('ventas-dolares_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('id' => 'form-ventas-dolares')
        ));

        $form->add('submit', 'submit',array('label' => 'Editar',
                                             'attr' => array('class' => 'btn btn-lg btn-success', 'style' => 'margin-top:10px;')));

        return $form;
    }
    /**
     * Edits an existing VentasDolares entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('lOroEntityBundle:VentasDolares')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VentasDolares entity.');
        }
        
        $dolaresViejos = $entity->getCantidadDolaresComprados();
        $bolivaresViejos = $entity->getMontoVentaBolivares();
        
        $editForm = $this->createEditForm($entity,TRUE);
        
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            
            $this->grabarMovimientoEnBanco($entity,'venta-dolares',$editForm->get('montoVentaBolivares')->getData(),$editForm->get('cantidadDolaresComprados')->getData(),'edicion',$dolaresViejos,$bolivaresViejos);
           
            
            $em->flush();
            
            return $this->redirect($this->generateUrl('ventas-dolares'));
        }

        return $this->render('lOroVentasDolaresBundle:VentasDolares:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    
 
    
    /**
     * Deletes a VentasDolares entity.
     *
     */
    public function deleteAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('lOroEntityBundle:VentasDolares')->find($id);

      
      if (!$entity) {
        throw $this->createNotFoundException('Unable to find VentasDolares entity.');
      }

      
      $depositosPorCuenta = $em->getRepository('lOroEntityBundle:VentasDolaresEmpresasCasa')->findBy(array('ventaDolares' => $entity->getId()));
      
      if($depositosPorCuenta):
        foreach($depositosPorCuenta as $row):
          $em->remove($row);
          $em->flush(); 
        endforeach;    
      endif;
      
      $em->remove($entity);
      $em->flush();
        
      $this->get('session')->getFlashBag()->add('notice', 'La venta de divisas N° '.$id.' ha sido eliminada de manera satisfactoria.');
      return new JsonResponse('exito');
    }

    /**
     * Creates a form to delete a VentasDolares entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ventas-dolares_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    
    public function empresasPorCompradorAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $comprador_id = $request->request->get('comprador_id');
        
        $comprador = $em->getRepository('lOroEntityBundle:Proveedores')->find($comprador_id);
        
        $entities = $em->getRepository('lOroEntityBundle:EmpresasProveedores')->findBy(array('proveedor' => $comprador));
        
        
        $arregloEmpresas = array();
        $arregloDatos = array();
        foreach($entities as $row):
          $arregloDatos['id'] = $row->getId();
          $arregloDatos['nombreEmpresa'] = $row->getNombreEmpresa();
          
          $arregloEmpresas[] = $arregloDatos;
        endforeach;
        
        
        return new Response(json_encode($arregloEmpresas)); 
    }
    
    public function buscarCreditoVerdesProveedorAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $compradorId = $request->request->get('proveedorId');
        
        $creditoDolaresComprador = $em->getRepository('lOroEntityBundle:VentasDolares')->getCreditoDolaresComprador($compradorId);
        
        
        
        return new Response(json_encode($creditoDolaresComprador)); 
    }    
    
    public function buscarEmpresasAction(Request $request){
     $em = $this->getDoctrine()->getManager();
     $nbEmpresa = $request->request->get('q');
     $arregloEmpresas = array();
     
     $empresas = $em->getRepository('lOroEntityBundle:VentasDolares')->buscarEmpresasPorNombre($nbEmpresa);   
      
     if($empresas):
       foreach($empresas as $row):
         $arregloEmpresas[] = $row['empresa'];  
       endforeach;    
     endif;
     
      return new JsonResponse($arregloEmpresas);
    }
}
