<?php

namespace lOro\EntityBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class PiezasRepository extends EntityRepository
{
    
    
    public function getUltimaPieza() {
        $conn = $this->getEntityManager()->getConnection();
      
          
        $sql = "SELECT p.codPieza 
                FROM lOroEntityBundle:Piezas p
                ORDER BY p.id DESC";
        
        
      return $this->getEntityManager()
            ->createQuery($sql)
            ->setMaxResults(1)
            ->getOneOrNullResult();      
    }
    
    public function getPiezasEntre($idPiezaInicial,$idPiezaFinal) {
      $conn = $this->getEntityManager()->getConnection();
      
          
      $query = "SELECT *
                  FROM piezas AS p
                  WHERE p.id BETWEEN $idPiezaInicial AND $idPiezaFinal
                  ORDER BY p.id DESC";
        
        
      $stmt = $conn->executeQuery($query);
            
            
      return $stmt->fetchAll(); 
    }    
    
    public function borrarTodosCierresHcPiezas() {
        $conn = $this->getEntityManager()->getConnection();
      
          
        $query = "DELETE FROM cierres_hc_piezas;";
        
        
        $stmt = $conn->executeQuery($query);
            
            
      return null;        
    }
    
    
    public function setearVentasCierres() {
        $conn = $this->getEntityManager()->getConnection();
      
          
        $query = "UPDATE ventas_cierres SET estatus = 'I' WHERE id = id;";
        
        
        $stmt = $conn->executeQuery($query);
        
        $query = "UPDATE ventas_cierres SET gramos_cerrados_restantes_piezas = cantidad_total_venta WHERE id = id;";
        
        
        $stmt = $conn->executeQuery($query);        
        
        $query = "UPDATE ventas_cierres SET estatus = 'A' WHERE id = 335;";
        
        
        $stmt = $conn->executeQuery($query);         
        
            
            
      return null;        
    }   
    
    public function setearPiezas() {
        $conn = $this->getEntityManager()->getConnection();
      
          
        $query = "UPDATE piezas SET gramos_restantes_relacion = peso_puro_pieza WHERE id > 478;";
        
        
        $stmt = $conn->executeQuery($query);
        
        
        $query = "UPDATE piezas SET gramos_restantes_relacion = 189.68 WHERE cod_pieza = 1434 AND anio = 2014;";
        
        
        $stmt = $conn->executeQuery($query);
        
        $query = "UPDATE piezas SET gramos_restantes_relacion = 0.00 WHERE cod_pieza < 1434 AND anio  = 2014;";
        
        
        $stmt = $conn->executeQuery($query);     
      return null;        
    }  
    
    
    public function buscarUltimoUltimaPiezaRegistrada()
    {
        $config = $this->getEntityManager()->getConfiguration();
        $config->addCustomNumericFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        //$config->addCustomStringFunction('CAST', 'DoctrineExtensions\Query\Mysql\Cast');
            
        return $this->getEntityManager()
            ->createQuery(
                'SELECT (MAX(p.codPieza) +1) AS id_siguiente 
                 FROM lOroEntityBundle:Piezas p
                 WHERE p.anio = YEAR(CURRENT_TIMESTAMP())
                 ORDER BY p.codPieza DESC'
            )
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }
    
    
    public function buscarPiezasPorId($valorBusqueda)
    {
        
      $valorBusqueda = ($valorBusqueda ? $valorBusqueda : '');
      
      $qb = $this->createQueryBuilder('u');
      $result = $qb ->select('u')
                    ->where(
                      $qb->expr()->like('u.codPieza',$qb->expr()->literal('%'. $valorBusqueda . '%'))
                    )
                    ->orderBy('u.codPieza','DESC')
       ->getQuery()   
       ->getResult();
       
      return $result;
    }
}