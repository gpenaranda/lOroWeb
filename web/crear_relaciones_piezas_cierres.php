<?php


$mysqli = new mysqli("192.186.228.199", "uloro", "l0r0_t3xt1l3s_$$", "l_oro_e");
//$mysqli = new mysqli("localhost", "root", "", "l_oro_db");

/* comprobar la conexión */
if ($mysqli->connect_errno) {
    printf("Falló la conexión: %s\n", $mysqli->connect_error);
    exit();
}

$query = "SET SQL_SAFE_UPDATES = 0;";
$mysqli->query($query);

/* Euros */
realizarRelacionPorTipoMoneda(3,$mysqli);


/* Dolares */
realizarRelacionPorTipoMoneda(2,$mysqli);


$query = "SET SQL_SAFE_UPDATES = 1;";
$mysqli->query($query);


function realizarRelacionPorTipoMoneda($opcionSeleccionada,$mysqli) {
    
 /* Borrar Todos Cierres Hc - Piezas */
  $query = "DELETE FROM cierres_hc_piezas WHERE tipo_moneda_id = $opcionSeleccionada;";
  $mysqli->query($query);
 /* Borrar Todos Cierres Hc - Piezas */    

  switch ($opcionSeleccionada):
    case 3: //Relacion de Piezas en Euros

        /* Setear Ventas Cierres */
        $query = "UPDATE ventas_cierres SET estatus = 'I' WHERE id = id AND tipo_cierre = 'hc' AND tipo_moneda_cierre_hc_id = $opcionSeleccionada;";
        $mysqli->query($query);
        $query = "UPDATE ventas_cierres SET gramos_cerrados_restantes_piezas = cantidad_total_venta WHERE id = id AND tipo_cierre = 'hc'AND tipo_moneda_cierre_hc_id = $opcionSeleccionada;";
        $mysqli->query($query);
        $query = "UPDATE ventas_cierres SET estatus = 'A' WHERE id = 7;";
        $mysqli->query($query);
        /* Setear Ventas Cierres */

        /* Setear Piezas */
        $query = "UPDATE piezas SET gramos_restantes_relacion = peso_puro_pieza WHERE id > 74 AND tipo_moneda_id = $opcionSeleccionada;";
        $mysqli->query($query);
        $query = "UPDATE piezas SET gramos_restantes_relacion = 315.16 WHERE id = 74 AND tipo_moneda_id = $opcionSeleccionada;";
        $mysqli->query($query);   
        $query = "UPDATE piezas SET gramos_restantes_relacion = 0.00 WHERE id < 74 AND tipo_moneda_id = $opcionSeleccionada;";
        $mysqli->query($query);
        /* Setear Piezas */        
        echo 'Relaciones en Euros<br>';
      break;
    case 2: //Relacion de Piezas en Dolares
        
        /* Setear Ventas Cierres */
        $query = "UPDATE ventas_cierres SET estatus = 'I' WHERE id = id AND tipo_cierre = 'hc' AND tipo_moneda_cierre_hc_id = $opcionSeleccionada;";
        $mysqli->query($query);
        $query = "UPDATE ventas_cierres SET gramos_cerrados_restantes_piezas = cantidad_total_venta WHERE id = id AND tipo_cierre = 'hc' AND tipo_moneda_cierre_hc_id = $opcionSeleccionada;";
        $mysqli->query($query);
        $query = "UPDATE ventas_cierres SET estatus = 'A' WHERE id = 8;";
        $mysqli->query($query);
        /* Setear Ventas Cierres */
  
        /* Setear Piezas */
        $query = "UPDATE piezas SET gramos_restantes_relacion = peso_puro_pieza WHERE id > 917 AND tipo_moneda_id = $opcionSeleccionada;";
        $mysqli->query($query);
        $query = "UPDATE piezas SET gramos_restantes_relacion = peso_puro_pieza WHERE id = 918 AND tipo_moneda_id = $opcionSeleccionada;";
        $mysqli->query($query);   
        $query = "UPDATE piezas SET gramos_restantes_relacion = 0.00 WHERE id < 918 AND tipo_moneda_id = $opcionSeleccionada;";
        $mysqli->query($query);
        /* Setear Piezas */  
        
      echo 'Relaciones en Dolares<br>';
      break;
    default:
        break;
  endswitch;
  


$sql = "SELECT * 
        FROM piezas 
        WHERE gramos_restantes_relacion != 0.00
        AND tipo_moneda_id = $opcionSeleccionada
        ORDER BY anio ASC, cod_pieza ASC
        ;";

$result = $mysqli->query($sql);


if ($result):

  while($pieza = $result->fetch_object()):
        
    /**************************************************************************/
    do {
    $piezaN = buscarPieza($mysqli,$pieza,$opcionSeleccionada);
  
    $piezaId = $piezaN->id; 
    $codPieza = $piezaN->cod_pieza;
    $gramosRestantesRelacion = $piezaN->gramos_restantes_relacion;
    
       /* Se busca el cierre Activo con HC */
       $query = "SELECT * FROM ventas_cierres WHERE estatus = 'A' AND tipo_cierre = 'hc' AND tipo_moneda_cierre_hc_id = $opcionSeleccionada;";
       $qCierreActivo = $mysqli->query($query);
       $cierreActivoHc = $qCierreActivo->fetch_object();
       
       
       
       if($cierreActivoHc):
         /* Se busca el valor restante del Cierre Activo */
         $gramosRestantesCierre = $cierreActivoHc->gramos_cerrados_restantes_piezas;  
    
                 /* Solo se continua si el valor del restante es Diferente de 0 */
                if ($gramosRestantesCierre != 0):
                    $pesoPuroPieza = $gramosRestantesRelacion;
                

                    /* Se compara el peso puro de la entrega con el restante del cierre y se
                     * comienza a evaluar, Si es mayor o Menor
                     */
                    $difEntregaCierre = $pesoPuroPieza - $gramosRestantesCierre;


                    

                    /* Si la diferencia es mayor a 0 significa que la pieza es mayor al
                     * restante del cierre
                    */
                    if ($difEntregaCierre > 0):
                        
                        /* Como la dif es > 0 el Material Entregado es = al Cierre*/
                        $materialEntregado = $gramosRestantesCierre;
                        
                        $nuevoCierreActivo = buscarNuevoCierreActivo($mysqli,$cierreActivoHc,$opcionSeleccionada);                      
                        
                        /* ACTUALIZACION DEL CIERRE Y LA PIEZA */
                        $query = "UPDATE ventas_cierres SET gramos_cerrados_restantes_piezas = 0, estatus = 'I' WHERE id = $cierreActivoHc->id;";
                        $mysqli->query($query);
                        
                        $query = "UPDATE piezas SET gramos_restantes_relacion = $difEntregaCierre WHERE id = $piezaId;";
                       
                        $mysqli->query($query);
                        /* ACTUALIZACION DEL CIERRE Y LA PIEZA */


                        if ($nuevoCierreActivo):
                          $query = "UPDATE ventas_cierres SET estatus = 'A' WHERE id = $nuevoCierreActivo->id;";
                          $mysqli->query($query);
                        endif;
                        

                        
                    /* 
                     * Si la diferencia es menor a 0 significa que el restante del cierre es mayor a la
                     * entrega
                     */
                    elseif ($difEntregaCierre < 0):
                        /* Como la dif es < 0 el Material Entregado es = a la Entrega */
                        $materialEntregado = $pesoPuroPieza;
                        
                        
                        $gramosCerradosRestantesPiezas = abs($difEntregaCierre);
                        
                        /* ACTUALIZACION DEL CIERRE Y LA PIEZA */
                        $query = "UPDATE ventas_cierres SET gramos_cerrados_restantes_piezas = $gramosCerradosRestantesPiezas WHERE id = $cierreActivoHc->id;";
                        $mysqli->query($query);
     
                        $query = "UPDATE piezas SET gramos_restantes_relacion = 0 WHERE id = $piezaId;";
                        $mysqli->query($query);
                        /* ACTUALIZACION DEL CIERRE Y LA PIEZA */
                        
                       
                        $difEntregaCierre = 0;
                        

                        
                    /* Si la diferencia es igual a 0 significa que la entrega y el
                     * restante del cierre se anularon
                     */
                    else:
                        $nuevoCierreActivo = buscarNuevoCierreActivo($mysqli,$cierreActivoHc,$opcionSeleccionada);
                        

                        /* ACTUALIZACION DEL CIERRE Y LA PIEZA */
                        $query = "UPDATE ventas_cierres SET gramos_cerrados_restantes_piezas = 0, estatus = 'I' WHERE id = $cierreActivoHc->id;";
                        $mysqli->query($query);
     
                        $query = "UPDATE piezas SET gramos_restantes_relacion = 0 WHERE id = $piezaId;";
                        $mysqli->query($query);
                        /* ACTUALIZACION DEL CIERRE Y LA PIEZA */
   

                       
                        /* Como la dif es > 0 el Material Entregado es = al Cierre */
                        $materialEntregado = $pesoPuroPieza;
                        
                        if ($nuevoCierreActivo):
                          $query = "UPDATE ventas_cierres SET estatus = 'A' WHERE id = $nuevoCierreActivo->id;";
                          $mysqli->query($query);
                        endif;  
                        
                        $difEntregaCierre = 0;  
                    endif;

                    if ($materialEntregado != 0):
                      $query = "INSERT INTO cierres_hc_piezas (cierre_hc_id,pieza_id,material_entregado,tipo_moneda_id) VALUES ($cierreActivoHc->id,$piezaId,$materialEntregado,$opcionSeleccionada);";
                      $mysqli->query($query);
                    endif;
                                  

                else:
                  $difEntregaCierre = 0;  
                endif;

           else:
              $difEntregaCierre = 0;
           endif;
           
           
       } while($difEntregaCierre != 0);     
       
    /**************************************************************************/
  endwhile;
  
  $result->close(); 
endif;
  

}



        

function buscarNuevoCierreActivo($mysqli,$cierreActivoHc,$opcionSeleccionada) {
  $query = "SELECT * 
            FROM ventas_cierres AS vc
            WHERE vc.id != $cierreActivoHc->id
            AND   vc.fe_venta >= '$cierreActivoHc->fe_venta'
            AND   vc.tipo_cierre = 'hc'
            AND   vc.gramos_cerrados_restantes_piezas != 0.00 
            AND   vc.estatus = 'I'
            AND tipo_moneda_cierre_hc_id = $opcionSeleccionada
            ORDER BY vc.fe_venta ASC;";
  
  return ejectuarSQL($mysqli,$query);   
}

function buscarPieza($mysqli,$pieza,$opcionSeleccionada) {
  $query = "SELECT * FROM piezas WHERE id = $pieza->id AND tipo_moneda_id = $opcionSeleccionada;";
         
  return ejectuarSQL($mysqli,$query); 
}


function ejectuarSQL($mysqli,$query) {
  $sql = $mysqli->query($query);
  
  catchMysqlError($mysqli); 
  
  return $sql->fetch_object();   
}

function catchMysqlError($mysqli) {
if ($mysqli->error) {
    try {   
        throw new Exception("MySQL error $mysqli->error <br> Query:<br> $query", $mysqli->errno);   
    } catch(Exception $e ) {
        echo "Error No: ".$e->getCode(). " - ". $e->getMessage() . "<br >";
        echo nl2br($e->getTraceAsString());
    }
  }    
  
}