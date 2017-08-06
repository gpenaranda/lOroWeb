<?php

namespace lOro\EntityBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class EntregasRepository extends EntityRepository
{
    public function buscarUltimoRegistroUsuario($id)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e
                 FROM lOroEntityBundle:Entregas e
                 WHERE e.usuarioId = :usuarioId
                 ORDER BY e.id DESC'
            )
            ->setMaxResults(1)
            ->setParameter('usuarioId', $id)
            ->getOneOrNullResult();
    }
    
    public function traerEntregasRelacionadas($feEntrega,$idEntrega) {
        
        return $this->getEntityManager()
            ->createQuery(
                'SELECT chce 
                 FROM lOroEntityBundle:CierresHcEntregas AS chce 
                 JOIN chce.entrega AS e
                 WHERE (e.id = :idEntrega
                   OR e.feEntrega > :feEntrega
                 ) ORDER BY e.feEntrega ASC'
            )
            ->setParameters(array('feEntrega' => $feEntrega, 'id' => $idEntrega))
            ->getResult();
        
    }
    
    public function borrarTodosCierresHcEntregas() {
        $conn = $this->getEntityManager()->getConnection();
      
          
        $query = "DELETE FROM cierres_hc_entregas;";
        
        
        $stmt = $conn->executeQuery($query);
            
            
      return null;        
    }
    
    public function borrarTodosCierresProveedoresEntregas($proveedor) {
        $conn = $this->getEntityManager()->getConnection();
        $proveedorId = $proveedor->getId();
          
        $query = "DELETE FROM cierres_proveedores_entregas WHERE proveedor_id = $proveedorId;";
        
        
        $stmt = $conn->executeQuery($query);
            
            
      return null;        
    }    
    
    public function setearVentasCierres() {
        $conn = $this->getEntityManager()->getConnection();
      
          
        $query = "UPDATE ventas_cierres SET estatus = 'I' WHERE id = id;";
        
        
        $stmt = $conn->executeQuery($query);
        
        $query = "UPDATE ventas_cierres SET gramos_cerrados_restantes = cantidad_total_venta WHERE id = id;";
        
        
        $stmt = $conn->executeQuery($query);        
        
        $query = "UPDATE ventas_cierres SET estatus = 'A' WHERE id = 335;";
        
        
        $stmt = $conn->executeQuery($query);         
        
            
            
      return null;        
    }   
    
    public function setearEntregas() {
        $conn = $this->getEntityManager()->getConnection();
      
          
        $query = "UPDATE entregas SET restante_por_relacion = peso_puro_entrega WHERE id = id;";
        
        
        $stmt = $conn->executeQuery($query);
        
        
        $query = "UPDATE entregas SET restante_por_relacion = 189.68 WHERE id = 112;";
        
        
        $stmt = $conn->executeQuery($query);
        
        $query = "UPDATE entregas SET restante_por_relacion = 0.00 WHERE id IN (108,109,110,111);";
        
        
        $stmt = $conn->executeQuery($query);
        
        $query = "UPDATE entregas SET restante_por_relacion = 0.00 WHERE fe_entrega < '2014-12-19';";
        
        
        $stmt = $conn->executeQuery($query);        
        
            
      return null;        
    }  
    
    
    
    
    public function setearVentasCierresProveedor($proveedor) {
        $conn = $this->getEntityManager()->getConnection();
        $proveedorId = $proveedor->getId();
          
        $query = "UPDATE ventas_cierres SET estatus = 'I' WHERE id = id AND proveedor_id = $proveedorId;";
        
        
        $stmt = $conn->executeQuery($query);
        
        $query = "UPDATE ventas_cierres SET gramos_cerrados_restantes = cantidad_total_venta WHERE id = id AND proveedor_id = $proveedorId;";
        
        
        $stmt = $conn->executeQuery($query);        
        
        $query = "UPDATE ventas_cierres SET estatus= 'A' WHERE proveedor_id = $proveedorId ORDER BY fe_venta ASC LIMIT 1";
       
        $stmt = $conn->executeQuery($query);              
      return null;        
    }     
    
    public function buscarPrimerVentaCierreProveedor($proveedor) {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT vc 
                 FROM lOroEntityBundle:VentasCierres AS vc 
                 WHERE vc.tipoCierre = :tipoCierre
                 AND   vc.gramosCerradosRestantes != 0
                 AND   vc.proveedorCierre = :proveedorCierre
                 ORDER BY vc.feVenta ASC'
            )
            ->setParameters(array('tipoCierre' => 'proveedor', 'proveedorCierre' => $proveedor))
            ->getResult();    
    }
    

    
    public function setearEntregasRestantePorRelacionProveedor($proveedor) {
        $conn = $this->getEntityManager()->getConnection();
        $proveedorId = $proveedor->getId();
          
        $query = "UPDATE entregas SET restante_por_relacion_proveedor = peso_puro_entrega WHERE id = id AND proveedor_id = $proveedorId;";
        
        
        $stmt = $conn->executeQuery($query);
            
      return null;        
    }     
    
    
    public function buscarEntregasPorId($valorBusqueda)
    {
        
      $valorBusqueda = ($valorBusqueda ? $valorBusqueda : '');
      $qb = $this->createQueryBuilder('u');
      $result = $qb ->select('u')
                    ->where(
                      $qb->expr()->like('u.id',$qb->expr()->literal('%'. $valorBusqueda . '%'))
                    )
                    ->orderBy('u.id','DESC')
       ->getQuery()   
       ->getResult();
       
      return $result;
    }
    
    public function buscarEntregasPorFechas($feInicio,$feFinal) 
    {
      return $this->getEntityManager()
            ->createQuery(
                'SELECT e
                 FROM lOroEntityBundle:Entregas e
                 WHERE e.feEntrega
                 BETWEEN :feInicio AND :feFinal
                 ORDER BY e.feEntrega DESC'
            )
            ->setParameter('feInicio', new \ DateTime($feInicio))
            ->setParameter('feFinal', new \ DateTime($feFinal))
            ->getResult();
      }
      
      public function buscarEntregasPorProveedor($idProveedor) {
          
      return $this->getEntityManager()
            ->createQuery(
                'SELECT e
                 FROM lOroEntityBundle:Entregas e
                 WHERE e.proveedor = :idProveedor
                 ORDER BY e.id DESC'
            )
            ->setParameter('idProveedor', $idProveedor)
            ->getResult();          
      }
      
      public function buscarMaxIdMensual($idEntrega,$fechaEntrega) {
        $conn = $this->getEntityManager()->getConnection();
      
        $config = $this->getEntityManager()->getConfiguration();
        $config->addCustomNumericFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $config->addCustomNumericFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');          
          
        $queryMensual = "SELECT (MAX(id_mensual) + 1) AS id_mensual
                         FROM entregas AS e
                         WHERE MONTH(e.fe_entrega) = MONTH('".$fechaEntrega->format('Y-m-d')."')
                         AND YEAR(e.fe_entrega) = YEAR('".$fechaEntrega->format('Y-m-d')."');";
        
        
        $stmt = $conn->executeQuery($queryMensual);
            
            
      return $stmt->fetch();
       
      }
      
      public function buscarPorMesIdMensual() {
      
      $conn = $this->getEntityManager()->getConnection();
      
      $config = $this->getEntityManager()->getConfiguration();
      $config->addCustomNumericFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
      
      return $this->getEntityManager()
            ->createQuery('SELECT e.id,
                                  e.idMensual,
                                  e.feEntrega,
                                  e.pesoPuroEntrega,
                                  tme.nbMoneda,
                                  p.nbProveedor,
                                  (SELECT COUNT(pe.id) FROM lOroEntityBundle:Piezas AS pe WHERE IDENTITY(pe.entrega) = e.id) AS piezasEntregadas,
                                  MONTH(e.feEntrega) AS mes_entrega
                           FROM lOroEntityBundle:Entregas AS e
                           LEFT JOIN e.proveedor AS p
                           LEFT JOIN e.tipoMonedaEntrega AS tme
                           ORDER BY mes_entrega DESC, e.idMensual ASC'
            )->getResult();            
      }
}
