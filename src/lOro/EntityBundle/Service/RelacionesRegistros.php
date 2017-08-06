<?php

namespace lOro\EntityBundle\Service;

use Doctrine\ORM\EntityManager;
use lOro\EntityBundle\Entity\CierresHcEntregas;
use lOro\EntityBundle\Entity\CierresProveedoresEntregas;
use lOro\EntityBundle\Entity\CierresHcPiezas;

class RelacionesRegistros {
    
    protected $em;
    
    public function __construct(EntityManager $em) {
      $this->em = $em;
    }
    
    public function relacionarPiezasConCierresHc($pieza) {
      
      
       do {
       /* Se busca el cierre Activo con HC */
       $cierreActivoHc = $this->em->getRepository('lOroEntityBundle:VentasCierres')->findOneBy(array('estatus' => 'A','tipoCierre' => 'hc'));
       
       if($cierreActivoHc):
         /* Se busca el valor restante del Cierre Activo */
         $gramosRestantesCierre = $cierreActivoHc->getGramosCerradosRestantesPiezas();  
    
         /* Solo se continua si el valor del restante es Diferente de 0 */
                if ($gramosRestantesCierre != 0):
                    $pesoPuroPieza = $pieza->getGramosRestantesRelacion();
                


                    /* Se compara el peso puro de la entrega con el restante del cierre y se
                     * comienza a evaluar, Si es mayor o Menor
                     */
                    $difEntregaCierre = $pesoPuroPieza - $gramosRestantesCierre;

                    /* Si la diferencia es mayor a 0 significa que la entrega es mayor al
                     * restante del cierre
                     */
                    if ($difEntregaCierre > 0):
                        $nuevoCierreActivo = $this->em->getRepository('lOroEntityBundle:VentasCierres')->traerSiguienteCierrePiezasInactivo($cierreActivoHc->getId(), $cierreActivoHc->getFeVenta(), 'hc');
                    

                        
                        $cierreActivoHc->setGramosCerradosRestantesPiezas(0);
                        $cierreActivoHc->setEstatus('I');
                        $pieza->setGramosRestantesRelacion($difEntregaCierre);

                     
                        $this->em->persist($cierreActivoHc);
                        $this->em->persist($pieza);
//                        $this->em->flush();


                        
                        
                        /* Como la dif es > 0 el Material Entregado es = al Cierre */
                        $materialEntregado = $gramosRestantesCierre;
                        
                        if ($nuevoCierreActivo):
                            $nuevoCierreActivo[0]->setEstatus('A');
                            $this->em->persist($nuevoCierreActivo[0]);
//                            $this->em->flush();
                            //$cierreActivoHc = $nuevoCierreActivo[0];
                        endif;



                        
                    /* Si la diferencia es menor a 0 significa que el restante del cierre es mayor a la
                     * entrega
                     */
                    elseif ($difEntregaCierre < 0):
                        /* Como la dif es < 0 el Material Entregado es = a la Entrega */
                        $materialEntregado = $pesoPuroPieza;
                    
                        $cierreActivoHc->setGramosCerradosRestantesPiezas(abs($difEntregaCierre));
                        $pieza->setGramosRestantesRelacion(0);

                        $this->em->persist($cierreActivoHc);
                        $this->em->persist($pieza);
//                        $this->em->flush();
                        //break;
                        $difEntregaCierre = 0;
                        

                        
                    /* Si la diferencia es igual a 0 significa que la entrega y el
                     * restante del cierre se anularon
                     */    
                    else:
                        $nuevoCierreActivo = $this->em->getRepository('lOroEntityBundle:VentasCierres')->traerSiguienteCierrePiezasInactivo($cierreActivoHc->getId(), $cierreActivoHc->getFeVenta(), 'hc');

                        $cierreActivoHc->setGramosCerradosRestantesPiezas(0);
                        $cierreActivoHc->setEstatus('I');
                        $pieza->setGramosRestantesRelacion(0);

                        $this->em->persist($cierreActivoHc);
                        $this->em->persist($pieza);
                        $this->em->flush();
                       
                        /* Como la dif es > 0 el Material Entregado es = al Cierre */
                        $materialEntregado = $gramosRestantesCierre;
                        
                        if ($nuevoCierreActivo):
                            $nuevoCierreActivo[0]->setEstatus('A');
                            $this->em->persist($nuevoCierreActivo[0]);
//                            $this->em->flush();
                        endif;                        
                    endif;

                    if ($materialEntregado != 0):
                      $cierreHcEntrega = new CierresHcPiezas();
                      $cierreHcEntrega->setCierreHc($cierreActivoHc);
                      $cierreHcEntrega->setPieza($pieza);
                      $cierreHcEntrega->setMaterialEntregado($materialEntregado);

                      $this->em->persist($cierreHcEntrega);
//                      $this->em->flush();
                    endif;
                                    

                else:
                  $difEntregaCierre = 0;  
                endif;
            else:
              $difEntregaCierre = 0;
            endif;
       } while($difEntregaCierre != 0);   
       
    }
    
    public function relacionarCierresHcConEntregas($cierre) {
    
      do {
       /* Se busca la entrega mas antigua que falta por relacionar y su restante
        * sea > 0 */
       $entregasPorRelacionar = $this->em->getRepository('lOroEntityBundle:VentasCierres')->traerEntregasRestantesPorRelacionar();
      
       if($entregasPorRelacionar):
         $restantePorRelacionEntrega = $entregasPorRelacionar[0]->getRestantePorRelacion();
         $gramosCerradosRestantes = $cierre->getGramosCerradosRestantes();
        
         $difCierreEntrega = $gramosCerradosRestantes - $restantePorRelacionEntrega;
        
         /* Si la dif es > 0 significa que el cierre todavia queda para relacionar
          * asi que se coloca en los gramos Cerrados Restantes = dif y el restante
          * por relacion queda en 0 */
          if($difCierreEntrega > 0):
            $cierre->setGramosCerradosRestantes($difCierreEntrega);
            $entregasPorRelacionar[0]->setRestantePorRelacion(0);

            $this->em->persist($cierre);
            $this->em->persist($entregasPorRelacionar[0]);
            $this->em->flush();

            /* Como la dif es > 0 el Material Entregado es = al Restante de la Entrega */
            $materialEntregado = $restantePorRelacionEntrega;


          
          /* Si la dif es < 0 significa que el cierre ya esta completo, por ende 
           * se vuelve inactivo y se busca uno nuevo, si no hay nuevo queda hasta ahi
           * y los gramos entregados = gramos cerrados restantes
           */
          elseif($difCierreEntrega < 0):
              
            $nuevoCierreActivo = $this->em->getRepository('lOroEntityBundle:VentasCierres')->traerSiguienteCierreInactivo($cierre->getId(), $cierre->getFeVenta(), 'hc');
            
            $cierre->setGramosCerradosRestantes(0);
            $cierre->setEstatus('I');
            $entregasPorRelacionar[0]->setRestantePorRelacion(abs($difCierreEntrega));

            $this->em->persist($cierre);
            $this->em->persist($entregasPorRelacionar[0]);
            $this->em->flush();


            /* Como la dif es < 0 el Material Entregado es = al Cierre */
            $materialEntregado = $gramosCerradosRestantes;
                        
            if ($nuevoCierreActivo):
              $nuevoCierreActivo[0]->setEstatus('A');
              $this->em->persist($nuevoCierreActivo[0]);
              $this->em->flush();
            endif;  
            
            $difCierreEntrega = 0;
          
          /* Si la dif = 0 se vuelve el cierre I y se colocan 0 tanto la relacion
           * como los gramos restantes */
          else:
            
            $nuevoCierreActivo = $this->em->getRepository('lOroEntityBundle:VentasCierres')->traerSiguienteCierreInactivo($cierre->getId(), $cierre->getFeVenta(), 'hc');
            
            $cierre->setGramosCerradosRestantes(0);
            $cierre->setEstatus('I');
            $entregasPorRelacionar[0]->setRestantePorRelacion(0);

            $this->em->persist($cierre);
            $this->em->persist($entregasPorRelacionar[0]);
            $this->em->flush();


            /* Como la dif es < 0 el Material Entregado es = al Cierre */
            $materialEntregado = $gramosCerradosRestantes;
                        
            if ($nuevoCierreActivo):
              $nuevoCierreActivo[0]->setEstatus('A');
              $this->em->persist($nuevoCierreActivo[0]);
              $this->em->flush();
            endif;  
            
            $difCierreEntrega = 0;
            
          endif;
          
          if ($materialEntregado != 0):
            $cierreHcEntrega = new CierresHcEntregas();
            $cierreHcEntrega->setCierreHc($cierre);
            $cierreHcEntrega->setEntrega($entregasPorRelacionar[0]);
            $cierreHcEntrega->setMaterialEntregado($materialEntregado);

            $this->em->persist($cierreHcEntrega);
            $this->em->flush();
          endif;
        endif;
        
      } while($difCierreEntrega != 0);
   }   
   
   public function relacionarCierreProveedorConEntregas($cierre) {
     $proveedor = $cierre->getProveedorCierre();
     $difCierreEntrega = 0;
     
     do {
       /* Se busca la entrega mas antigua que falta por relacionar y su restante
        * sea > 0 */
       $entregasPorRelacionar = $this->em->getRepository('lOroEntityBundle:VentasCierres')->traerEntregasProveedorRestantesPorRelacionar($proveedor);
      
       if($entregasPorRelacionar):
         $restantePorRelacionEntrega = $entregasPorRelacionar[0]->getRestantePorRelacionProveedor();
         $gramosCerradosRestantes = $cierre->getGramosCerradosRestantes();
        
         $difCierreEntrega = $gramosCerradosRestantes - $restantePorRelacionEntrega;
        
         /* Si la dif es > 0 significa que el cierre todavia queda para relacionar
          * asi que se coloca en los gramos Cerrados Restantes = dif y el restante
          * por relacion queda en 0 */
          if($difCierreEntrega > 0):
            $cierre->setGramosCerradosRestantes($difCierreEntrega);
            $entregasPorRelacionar[0]->setRestantePorRelacionProveedor(0);

            $this->em->persist($cierre);
            $this->em->persist($entregasPorRelacionar[0]);
            $this->em->flush();

            /* Como la dif es > 0 el Material Entregado es = al Restante de la Entrega */
            $materialEntregado = $restantePorRelacionEntrega;


          
          /* Si la dif es < 0 significa que el cierre ya esta completo, por ende 
           * se vuelve inactivo y se busca uno nuevo, si no hay nuevo queda hasta ahi
           * y los gramos entregados = gramos cerrados restantes
           */
          elseif($difCierreEntrega < 0):
              
            $nuevoCierreActivo = $this->em->getRepository('lOroEntityBundle:VentasCierres')->traerSiguienteCierreInactivoPorProveedor($cierre->getId(), $cierre->getFeVenta(), $proveedor);
            
            $cierre->setGramosCerradosRestantes(0);
            $cierre->setEstatus('I');
            $entregasPorRelacionar[0]->setRestantePorRelacionProveedor(abs($difCierreEntrega));

            $this->em->persist($cierre);
            $this->em->persist($entregasPorRelacionar[0]);
            $this->em->flush();


            /* Como la dif es < 0 el Material Entregado es = al Cierre */
            $materialEntregado = $gramosCerradosRestantes;
                        
            if ($nuevoCierreActivo):
              $nuevoCierreActivo[0]->setEstatus('A');
              $this->em->persist($nuevoCierreActivo[0]);
              $this->em->flush();
            endif;  
            
            $difCierreEntrega = 0;
          
          /* Si la dif = 0 se vuelve el cierre I y se colocan 0 tanto la relacion
           * como los gramos restantes */
          else:
            
            $nuevoCierreActivo = $this->em->getRepository('lOroEntityBundle:VentasCierres')->traerSiguienteCierreInactivoPorProveedor($cierre->getId(), $cierre->getFeVenta(), $proveedor);
            
            $cierre->setGramosCerradosRestantes(0);
            $cierre->setEstatus('I');
            $entregasPorRelacionar[0]->setRestantePorRelacionProveedor(0);

            $this->em->persist($cierre);
            $this->em->persist($entregasPorRelacionar[0]);
            $this->em->flush();


            /* Como la dif es < 0 el Material Entregado es = al Cierre */
            $materialEntregado = $gramosCerradosRestantes;
                        
            if ($nuevoCierreActivo):
              $nuevoCierreActivo[0]->setEstatus('A');
              $this->em->persist($nuevoCierreActivo[0]);
              $this->em->flush();
            endif;  
            
            $difCierreEntrega = 0;
            
          endif;
          
          if ($materialEntregado != 0):
            $cierreProveedorEntrega = new CierresProveedoresEntregas();
            $cierreProveedorEntrega->setCierreHc($cierre);
            $cierreProveedorEntrega->setEntrega($entregasPorRelacionar[0]);
            $cierreProveedorEntrega->setMaterialEntregado($materialEntregado);
            $cierreProveedorEntrega->setProveedor($proveedor);

            $this->em->persist($cierreProveedorEntrega);
            $this->em->flush();
          endif;
        endif;
        
      } while($difCierreEntrega != 0);     
   }
   
   
   public function relacionarEntregasConCierresHc($entrega) {
   
     do {
       /* Se busca el cierre Activo con HC */
       $cierreActivoHc = $this->em->getRepository('lOroEntityBundle:VentasCierres')->findOneBy(array('estatus' => 'A','tipoCierre' => 'hc'));
       
       if($cierreActivoHc):
         /* Se busca el valor restante del Cierre Activo */
         $gramosRestantesCierre = $cierreActivoHc->getGramosCerradosRestantes();  
    
         /* Solo se continua si el valor del restante es Diferente de 0 */
                if ($gramosRestantesCierre != 0):
                    $pesoPuroEntrega = $entrega->getRestantePorRelacion();
                


                    /* Se compara el peso puro de la entrega con el restante del cierre y se
                     * comienza a evaluar, Si es mayor o Menor
                     */
                    $difEntregaCierre = $pesoPuroEntrega - $gramosRestantesCierre;

                    /* Si la diferencia es mayor a 0 significa que la entrega es mayor al
                     * restante del cierre
                     */
                    if ($difEntregaCierre > 0):
                        $nuevoCierreActivo = $this->em->getRepository('lOroEntityBundle:VentasCierres')->traerSiguienteCierreInactivo($cierreActivoHc->getId(), $cierreActivoHc->getFeVenta(), 'hc');
                    

                        
                        $cierreActivoHc->setGramosCerradosRestantes(0);
                        $cierreActivoHc->setEstatus('I');
                        $entrega->setRestantePorRelacion($difEntregaCierre);

                        $this->em->persist($cierreActivoHc);
                        $this->em->persist($entrega);
                        $this->em->flush();

                        /* Como la dif es > 0 el Material Entregado es = al Cierre */
                        $materialEntregado = $gramosRestantesCierre;
                        
                        if ($nuevoCierreActivo):
                            $nuevoCierreActivo[0]->setEstatus('A');
                            $this->em->persist($nuevoCierreActivo[0]);
                            $this->em->flush();
                            //$cierreActivoHc = $nuevoCierreActivo[0];
                        endif;



                    /* Si la diferencia es menor a 0 significa que el restante del cierre es mayor a la
                     * entrega
                     */
                    elseif ($difEntregaCierre < 0):
                        /* Como la dif es < 0 el Material Entregado es = a la Entrega */
                        $materialEntregado = $pesoPuroEntrega;
                    
                        $cierreActivoHc->setGramosCerradosRestantes(abs($difEntregaCierre));
                        $entrega->setRestantePorRelacion(0);

                        $this->em->persist($cierreActivoHc);
                        $this->em->persist($entrega);
                        $this->em->flush();
                        //break;
                        $difEntregaCierre = 0;
                        
                    /* Si la diferencia es igual a 0 significa que la entrega y el
                     * restante del cierre se anularon
                     */    
                    else:
                        $nuevoCierreActivo = $this->em->getRepository('lOroEntityBundle:VentasCierres')->traerSiguienteCierreInactivo($cierreActivoHc->getId(), $cierreActivoHc->getFeVenta(), 'hc');

                        $cierreActivoHc->setGramosCerradosRestantes(0);
                        $cierreActivoHc->setEstatus('I');
                        $entrega->setRestantePorRelacion(0);

                        $this->em->persist($cierreActivoHc);
                        $this->em->persist($entrega);
                        $this->em->flush();
                       
                        /* Como la dif es > 0 el Material Entregado es = al Cierre */
                        $materialEntregado = $gramosRestantesCierre;
                        
                        if ($nuevoCierreActivo):
                            $nuevoCierreActivo[0]->setEstatus('A');
                            $this->em->persist($nuevoCierreActivo[0]);
                            $this->em->flush();
                        endif;                        
                    endif;

                    if ($materialEntregado != 0):
                      $cierreHcEntrega = new CierresHcEntregas();
                      $cierreHcEntrega->setCierreHc($cierreActivoHc);
                      $cierreHcEntrega->setEntrega($entrega);
                      $cierreHcEntrega->setMaterialEntregado($materialEntregado);

                      $this->em->persist($cierreHcEntrega);
                      $this->em->flush();
                    endif;
                else:
                  $difEntregaCierre = 0;  
                endif;
            else:
              $difEntregaCierre = 0;
            endif;
       } while($difEntregaCierre != 0);

   }
   
   /**
    * Funcion que permite realizar la Relacion de la Entrega Generada con los
    * Cierres Realizados por el Proveedor (Solo con su mismo proveedor y el Cierre tiene que estar activo, si no hay activos no se hace nada)
    * 
    * @param object $entrega Objeto que representa la entrega registrada en el sistema y sera relacionada con los Cierres del Proveedor
    **/
   public function relacionarEntregasConCierresProveedor($entrega) {
     $proveedor = $entrega->getProveedor();
     
     
     
     do {
       /* Se busca el cierre Activo con el Proveedor */
       $cierreActivo = $this->em->getRepository('lOroEntityBundle:VentasCierres')->findOneBy(array('estatus' => 'A', 'proveedorCierre' => $proveedor));
       
       if($cierreActivo):
         /* Se busca el valor restante del Cierre Activo */
         $gramosRestantesCierre = $cierreActivo->getGramosCerradosRestantes();  

         /* Solo se continua si el valor del restante es Diferente de 0 */
         if ($gramosRestantesCierre != 0):
           $pesoPuroEntrega = $entrega->getRestantePorRelacionProveedor();
                
            /* Se compara el peso puro de la entrega con el restante del cierre y se
             * comienza a evaluar, Si es mayor o Menor
             */
             $difEntregaCierre = $pesoPuroEntrega - $gramosRestantesCierre;

                    /* Si la diferencia es mayor a 0 significa que la entrega es mayor al
                     * restante del cierre
                     */
                    if ($difEntregaCierre > 0):
                        $nuevoCierreActivo = $this->em->getRepository('lOroEntityBundle:VentasCierres')->traerSiguienteCierreInactivoPorProveedor($cierreActivo->getId(), $cierreActivo->getFeVenta(),$proveedor);
                        
                    
                    

                    
                        $cierreActivo->setGramosCerradosRestantes(0);
                        $cierreActivo->setEstatus('I');
                        $entrega->setRestantePorRelacionProveedor($difEntregaCierre);

                        $this->em->persist($cierreActivo);
                        $this->em->persist($entrega);
                        $this->em->flush();

                        /* Como la dif es > 0 el Material Entregado es = al Cierre */
                        $materialEntregado = $gramosRestantesCierre;
                        
                        if ($nuevoCierreActivo):
                            $nuevoCierreActivo[0]->setEstatus('A');
                            $this->em->persist($nuevoCierreActivo[0]);
                            $this->em->flush();
                            //$cierreActivoHc = $nuevoCierreActivo[0];
                        endif;



                    /* Si la diferencia es menor a 0 significa que el restante del cierre es mayor a la
                     * entrega
                     */
                    elseif ($difEntregaCierre < 0):
                        /* Como la dif es < 0 el Material Entregado es = a la Entrega */
                        $materialEntregado = $pesoPuroEntrega;
                    
                        $cierreActivo->setGramosCerradosRestantes(abs($difEntregaCierre));
                        $entrega->setRestantePorRelacionProveedor(0);

                        $this->em->persist($cierreActivo);
                        $this->em->persist($entrega);
                        $this->em->flush();
                        //break;
                        $difEntregaCierre = 0;
                        
                    /* Si la diferencia es igual a 0 significa que la entrega y el
                     * restante del cierre se anularon
                     */    
                    else:
                        $nuevoCierreActivo = $this->em->getRepository('lOroEntityBundle:VentasCierres')->traerSiguienteCierreInactivoPorProveedor($cierreActivo->getId(), $cierreActivo->getFeVenta(),$proveedor);

                        $cierreActivo->setGramosCerradosRestantes(0);
                        $cierreActivo->setEstatus('I');
                        $entrega->setRestantePorRelacionProveedor(0);

                        $this->em->persist($cierreActivo);
                        $this->em->persist($entrega);
                        $this->em->flush();
                       
                        /* Como la dif es > 0 el Material Entregado es = al Cierre */
                        $materialEntregado = $gramosRestantesCierre;
                        
                        if ($nuevoCierreActivo):
                            $nuevoCierreActivo[0]->setEstatus('A');
                            $this->em->persist($nuevoCierreActivo[0]);
                            $this->em->flush();
                        endif;                        
                    endif;

                    if ($materialEntregado != 0):
                      $cierreProveedorEntrega = new CierresProveedoresEntregas();
                      $cierreProveedorEntrega->setCierreProveedor($cierreActivo);
                      $cierreProveedorEntrega->setEntrega($entrega);
                      $cierreProveedorEntrega->setMaterialEntregado($materialEntregado);
                      $cierreProveedorEntrega->setProveedor($proveedor);

                      $this->em->persist($cierreProveedorEntrega);
                      $this->em->flush();
                    endif;
                else:
                  $difEntregaCierre = 0;  
                endif;
                
            /* Si no se posee Cierre Activo la Dif de la Entrega Cierre es 0  para que salga del bucle */
            else:
              $difEntregaCierre = 0;
            endif;
       } while($difEntregaCierre != 0);

   }

}