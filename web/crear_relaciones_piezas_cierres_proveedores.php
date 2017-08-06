<?php

$mysqli = new mysqli("192.186.228.199", "uloro", "l0r0_t3xt1l3s_$$", "l_oro_prod_e");
//$mysqli = new mysqli("localhost", "root", "root", "l_oro_prod");

/* comprobar la conexión */
if ($mysqli->connect_errno) {
    printf("Falló la conexión: %s\n", $mysqli->connect_error);
    exit();
}

$idProveedor = $_GET['id_proveedor'];

$query = "SET SQL_SAFE_UPDATES = 0;";
$mysqli->query($query);

/* Borrar Todos los Cierres Proveedores - Piezas */
$query = "DELETE 
          FROM cierres_proveedores_piezas
          WHERE cierre_proveedor_id IN (
                SELECT  id
                FROM    ventas_cierres
                WHERE   proveedor_id = $idProveedor
          );";

$mysqli->query($query);
/* Borrar Todos Cierres Hc - Piezas */

/* Setear Ventas Cierres */
$query = "UPDATE ventas_cierres SET estatus = 'I', gramos_cerrados_restantes_piezas = cantidad_total_venta WHERE id = id AND tipo_cierre = 'proveedor' AND proveedor_id = $idProveedor;";
$mysqli->query($query);

/* Setea cada uno de los cierres mas antigus de los proveedores en estatus 'A' */
setearActivoCierresPorProveedor($mysqli);


/* Setear Piezas con los proveedores */
$query = "UPDATE piezas SET gramos_restantes_relacion_proveedor = peso_puro_pieza
          WHERE entrega_id IN (
            SELECT  id
            FROM    entregas
            WHERE   proveedor_id = $idProveedor);";
$mysqli->query($query);
/* Setear Piezas con los proveedores */


  $proveedores = traerProveedoresConCierres($mysqli);

  
  while ($fila = $proveedores->fetch_assoc()):
    do {
      $gramosRestantes = verificarCierresProveedor($mysqli,$fila['id_proveedor']);
    } while($gramosRestantes < 0);
    
    relacionarCierresProveedorConPiezas($mysqli,$fila["id_proveedor"]);
  endwhile;


function verificarCierresProveedor($mysqli,$idProveedor) {
/* Se busca el cierre Activo con el Proveedor */
       $query = "SELECT * FROM ventas_cierres WHERE estatus = 'A' AND proveedor_id = $idProveedor AND tipo_cierre = 'proveedor';";
       $qCierreActivo = $mysqli->query($query);
       $cierreActivoHc = $qCierreActivo->fetch_object();
       
       
       
       if($cierreActivoHc):
          if($cierreActivoHc->gramos_cerrados_restantes_piezas < 0):
            $qNuevoCierreActivo = buscarNuevoCierreActivo($mysqli,$cierreActivoHc,$idProveedor);                      
            $nuevoCierreActivo = $qNuevoCierreActivo->fetch_object();
                        
            $gramosCerradosRestantesNuevoCierre = ($cierreActivoHc->gramos_cerrados_restantes_piezas + $nuevoCierreActivo->gramos_cerrados_restantes_piezas);
            
            $query = "UPDATE ventas_cierres SET gramos_cerrados_restantes_piezas = 0, estatus = 'I' WHERE id = $cierreActivoHc->id;";
            $mysqli->query($query);
                        
            $query = "UPDATE ventas_cierres SET gramos_cerrados_restantes_piezas = $gramosCerradosRestantesNuevoCierre, estatus = 'A' WHERE id = $nuevoCierreActivo->id;";
            $mysqli->query($query);
      endif;
     endif;    
     
     return $gramosCerradosRestantesNuevoCierre;
}


function relacionarCierresProveedorConPiezas($mysqli,$idProveedor) {
$sql = "SELECT p.* 
        FROM piezas AS p 
        JOIN entregas AS e ON (e.id = p.entrega_id)
        WHERE gramos_restantes_relacion_proveedor != 0.00
        AND e.proveedor_id = $idProveedor
        ORDER BY anio ASC, cod_pieza ASC
        ;";

if ($result = $mysqli->query($sql)):

  while($pieza = $result->fetch_object()):

    /**************************************************************************/
    do {
    $qPiezaN = buscarPieza($mysqli,$pieza);
    $piezaN = $qPiezaN->fetch_object();
  
    
    $piezaId = $piezaN->id; 
    $codPieza = $piezaN->cod_pieza;
    $gramosRestantesRelacion = $piezaN->gramos_restantes_relacion_proveedor;
    
       /* Se busca el cierre Activo con el Proveedor */
       $query = "SELECT * FROM ventas_cierres WHERE estatus = 'A' AND proveedor_id = $idProveedor AND tipo_cierre = 'proveedor';";
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
                        
                        $qNuevoCierreActivo = buscarNuevoCierreActivo($mysqli,$cierreActivoHc,$idProveedor);                      
                        $nuevoCierreActivo = $qNuevoCierreActivo->fetch_object();
                        
                        /* ACTUALIZACION DEL CIERRE Y LA PIEZA */
                        $query = "UPDATE ventas_cierres SET gramos_cerrados_restantes_piezas = 0, estatus = 'I' WHERE id = $cierreActivoHc->id;";
                        $mysqli->query($query);
                        
                        $query = "UPDATE piezas SET gramos_restantes_relacion_proveedor = $difEntregaCierre WHERE id = $piezaId;";
                       
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
     
                        $query = "UPDATE piezas SET gramos_restantes_relacion_proveedor = 0 WHERE id = $piezaId;";
                        $mysqli->query($query);
                        /* ACTUALIZACION DEL CIERRE Y LA PIEZA */
                        
                       
                        $difEntregaCierre = 0;
                        

                        
                    /* Si la diferencia es igual a 0 significa que la entrega y el
                     * restante del cierre se anularon
                     */
                    else:
                        $qNuevoCierreActivo = buscarNuevoCierreActivo($mysqli,$cierreActivoHc,$idProveedor);                      
                        $nuevoCierreActivo = $qNuevoCierreActivo->fetch_object();
                        

                        /* ACTUALIZACION DEL CIERRE Y LA PIEZA */
                        $query = "UPDATE ventas_cierres SET gramos_cerrados_restantes_piezas = 0, estatus = 'I' WHERE id = $cierreActivoHc->id;";
                        $mysqli->query($query);
     
                        $query = "UPDATE piezas SET gramos_restantes_relacion_proveedor = 0 WHERE id = $piezaId;";
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
                      $query = "INSERT INTO cierres_proveedores_piezas (cierre_proveedor_id,pieza_id,material_entregado) VALUES ($cierreActivoHc->id,$piezaId,$materialEntregado);";
                      $mysqli->query($query);
                      echo 'Entrega - Cierre Insertado <br>';
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
endif;
$result->close();

}

function setearActivoCierresPorProveedor($mysqli) 
{
  $proveedores = traerProveedoresConCierres($mysqli);
  
  while ($fila = $proveedores->fetch_assoc()):
    $idProveedor = $fila["id_proveedor"];
    $query = "SELECT * FROM ventas_cierres AS vc WHERE vc.proveedor_id = $idProveedor ORDER BY vc.fe_venta ASC LIMIT 1;";
    $QCierreMasAntiguo = ejecutarSQL($mysqli,$query);
    
    $cierreMasAntiguo = $QCierreMasAntiguo->fetch_object();
    
    $query = "UPDATE ventas_cierres SET estatus = 'A' WHERE id = $cierreMasAntiguo->id AND tipo_cierre = 'proveedor';";
    ejecutarSQL($mysqli,$query);
  endwhile;
}

function traerProveedoresConCierres($mysqli) {
  $idProveedor = $_GET['id_proveedor'];
  
  $query = "SELECT DISTINCT(p.id) AS id_proveedor "
          . "FROM proveedores AS p "
          . "JOIN ventas_cierres AS vc ON (vc.proveedor_id = p.id)"
          . "AND p.id = $idProveedor;";
  
  return ejecutarSQL($mysqli,$query);    
}

function buscarNuevoCierreActivo($mysqli,$cierreActivoHc,$idProveedor) {
  $query = "SELECT * 
            FROM ventas_cierres AS vc
            WHERE vc.id != $cierreActivoHc->id
            AND   vc.fe_venta >= '$cierreActivoHc->fe_venta'
            AND   vc.tipo_cierre = 'proveedor'
            AND   vc.gramos_cerrados_restantes_piezas != 0.00 
            AND   vc.estatus = 'I'
            AND   vc.proveedor_id = $idProveedor
            ORDER BY vc.fe_venta ASC;";
  
  return ejecutarSQL($mysqli,$query);   
}

function buscarPieza($mysqli,$pieza) {
  $query = "SELECT * FROM piezas WHERE id = $pieza->id;";
         
  return ejecutarSQL($mysqli,$query); 
}


function ejecutarSQL($mysqli,$query) {
  $sql = $mysqli->query($query);
  
  catchMysqlError($mysqli,$query); 
  
          
  return ($mysqli->affected_rows > 0 ? $sql : null);   
}

function catchMysqlError($mysqli,$query) {
if ($mysqli->error) {
    try {   
        throw new Exception("MySQL error $mysqli->error <br> Query:<br> $query", $mysqli->errno);   
    } catch(Exception $e ) {
        echo "Error No: ".$e->getCode(). " - ". $e->getMessage() . "<br >";
        echo nl2br($e->getTraceAsString());
    }
  }    
}