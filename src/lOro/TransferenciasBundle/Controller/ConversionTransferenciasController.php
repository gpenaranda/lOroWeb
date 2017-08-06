<?php

namespace lOro\TransferenciasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use lOro\EntityBundle\Entity\Transferencias;
use lOro\EntityBundle\Entity\CambioTransferenciasBs;
use lOro\TransferenciasBundle\Form\ConversionTransferenciasType;
use lOro\EntityBundle\Entity\Banco;
use lOro\EntityBundle\Entity\Balances;


/**
 * Conversion Transferencias controller.
 *
 */
class ConversionTransferenciasController extends Controller
{

    public function createAction(Request $request)
    {
        $form = $this->createCreateForm();
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
            
       
        if ($form->isValid()) {
          $feActual = new \DateTime();
          $dolarReferencia = $form->get('dolarReferencia')->getData();
          
        // Monto que se espera cambiar - el que viene del formulario
        $montoACambiar = $form->get('montoCambiado')->getData();
        $restanteACambiar = $montoACambiar;
        
        /** Se deben buscar todas las transferencias que se encuentren en SC = Sin Cambiar
         *  para asi poder comenzar a restar las transferencias 
         */
         $transferenciasSinCambiar = $em->getRepository('lOroEntityBundle:Transferencias')->findBy(array('transferenciaCambiada' =>  array('SC','CP')));
         
         $sePuedeCambiar = true;
         foreach($transferenciasSinCambiar as $transferenciaSinCambiar):
           $entity = new CambioTransferenciasBs();
           if($transferenciaSinCambiar->getTransferenciaCambiada() == 'CP'):
             $montoTransferencia = $transferenciaSinCambiar->getRestantePorCambiar();
           else:    
             $montoTransferencia = $transferenciaSinCambiar->getMontoTransferencia();               
           endif;
           
           $restanteACambiar = $restanteACambiar - $montoTransferencia;
           
           if($sePuedeCambiar == true):
               
               if($restanteACambiar > 0 ):
               /** Si el restante es mayor a 0, indica que todo el mondo de la transferencia se consumio
                *  y por ende se cambia en la transferencia el estatus de SC a CC (Cambio Completo)
                **/
               $transferenciaSinCambiar->setTransferenciaCambiada('CC');
               $transferenciaSinCambiar->setRestantePorCambiar(0.00);

               $cantidadCambiadaPorTransferencia = $montoTransferencia;

               elseif($restanteACambiar < 0):
               /** Si el restante es menor a 0 se tiene que verificar por cuanto se paso
                *  para asi solo colocar la cantidad exacta que se cambio por ese cambio
                */
               $transferenciaSinCambiar->setTransferenciaCambiada('CP');
               $transferenciaSinCambiar->setRestantePorCambiar(abs($restanteACambiar));

               $cantidadCambiadaPorTransferencia = $montoTransferencia - abs($restanteACambiar);
               $sePuedeCambiar = false;
               else:
                 $transferenciaSinCambiar->setTransferenciaCambiada('CC');
                 $transferenciaSinCambiar->setRestantePorCambiar(0.00);

                 $cantidadCambiadaPorTransferencia = $montoTransferencia; 
                 $sePuedeCambiar = false;
               endif;

               
               
               
               $entity->setFeCambio($feActual);
               $entity->setDolarReferencia($dolarReferencia);
               $entity->setTransferencia($transferenciaSinCambiar);
               $entity->setMontoCambiado($cantidadCambiadaPorTransferencia);
               $montoFinalBolivares = $cantidadCambiadaPorTransferencia*$dolarReferencia;
               $entity->setMontoFinalBolivares($montoFinalBolivares);

               $em->persist($transferenciaSinCambiar);
               $em->persist($entity);
               $em->flush();
               
               $this->grabarMovimientoEnBanco($entity,'cambio-transferencia',$cantidadCambiadaPorTransferencia,$montoFinalBolivares);
           endif;
           
         endforeach;
            
           // $balance = $this->buscarBalanceActivo();
            
           // $entity->setBalance($balance);

            
            return $this->redirect($this->generateUrl('transferencias'));
        }

        return $this->render('lOroTransferenciasBundle:Transferencias:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Funcion que permite guardar el movimiento en el banco
     * 
     * @param entity $movimiento
     * @param string $tipoMovimiento
     * @param integer $montoDolaresTransferencia
     */
    protected function grabarMovimientoEnBanco($movimiento,$tipoMovimiento,$montoDolaresMovimiento,$montoBolivaresMovimiento) {
    
    $em = $this->getDoctrine()->getManager();
    $feActual = new \DateTime('now');
    $montoDolaresUltimaColumnaBanco = 0;
    $montoBolivaresUltimaColumnaBanco = 0;
    
    /** Se debe buscar en banco para sumar, luego realizar un nuevo registro **/
    $ultimaColumnaBanco = $em->getRepository('lOroEntityBundle:Banco')->findOneByLastRegistroBanco();
   
    if($ultimaColumnaBanco): // Se crea el nuevo Banco y se agrega el monto
      $montoDolaresUltimaColumnaBanco = $ultimaColumnaBanco->getMontoDolares();
      $montoBolivaresUltimaColumnaBanco = $ultimaColumnaBanco->getMontoBolivares();
    endif;
            
    $montoDolaresBanco = $montoDolaresUltimaColumnaBanco - $montoDolaresMovimiento;
    $montoBolivares = $montoBolivaresUltimaColumnaBanco + $montoBolivaresMovimiento;
            
    $nuevaColumnaBanco = new Banco(); // Se crea nueva columna en el banco
            
            
     $nuevaColumnaBanco->setMontoDolares($montoDolaresBanco);
     $nuevaColumnaBanco->setMontoBolivares($montoBolivares);
     $nuevaColumnaBanco->setTipoMovimiento($tipoMovimiento);
     $nuevaColumnaBanco->setIdMovimiento($movimiento->getId());
     $nuevaColumnaBanco->setFeMovimientoBanco($feActual);
     $em->persist($nuevaColumnaBanco);
     $em->flush();
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
    * Creates a form to create a Transferencias entity.
    *
    * @param Transferencias $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm()
    {
        $form = $this->createForm(new ConversionTransferenciasType(),null, array(
            'action' => $this->generateUrl('conversion_transferencias_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Agregar',
                                             'attr' => array('class' => 'btn-lg btn-success')));

        return $form;
    }

    /**
     * Displays a form to create a new Transferencias entity.
     *
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new CambioTransferenciasBs();
        $form   = $this->createCreateForm($entity);
        
        /** Se busca el ultimo banco para ver el monto en dolares disponible
         *  para cambiar
         **/
        $banco = $em->getRepository('lOroEntityBundle:Banco')->findOneByLastRegistroBanco();
        
        if(!$banco):
            // SI NO EXISTE BANCO NO DEBERIA PODER CAMBIAR DOLARES A BOLIVARES 
        endif;
        
        
        return $this->render('lOroTransferenciasBundle:Transferencias/ConversionTransferencias:new.html.twig', array(
              'entity' => $entity,
              'form'   => $form->createView(),
              'banco'  => $banco
        ));
    }    
}

