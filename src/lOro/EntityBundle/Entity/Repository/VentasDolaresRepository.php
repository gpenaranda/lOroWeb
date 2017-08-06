<?php

namespace lOro\EntityBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;


class VentasDolaresRepository extends EntityRepository
{
    
    public function getCreditoDolaresComprador($compradorId) {
      $conn = $this->getEntityManager()->getConnection();
           
      $stmt = $conn->executeQuery("SELECT * FROM v_creditos_dolares_compradores WHERE comprador_id = $compradorId;");
            
           
      return $stmt->fetch();
    }
    
    public function buscarEmpresasPorNombre($nbEmpresa)
    {
     $qb = $this->createQueryBuilder('u');
      $result = $qb ->select('DISTINCT (u.empresa) AS empresa')
                    ->where(
                      $qb->expr()->like('u.empresa',$qb->expr()->literal('%'. $nbEmpresa . '%'))
                    )
                    ->orderBy('u.empresa','DESC')
       ->getQuery()   
       ->getResult();
       
      return $result;
    }   
    
    public function sumVentasDolaresMes() {
    /*
        $rsm = new ResultSetMapping();
      
      
           $rsm->addEntityResult('lOro\EntityBundle\Entity\VentasDolares', 'vd');
            $rsm->addFieldResult('vd', 'mesVenta', 'mes_venta');
            $rsm->addFieldResult('vd', 'bolivaresVentaMes', 'bolivares_venta_mes');
  */
        $config = $this->getEntityManager()->getConfiguration();
        $config->addCustomNumericFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $config->addCustomNumericFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        
      $query = $this->getEntityManager()
                    ->createQuery(
                'SELECT MONTH(vd.fechaVenta) AS mes_venta,
                 SUM(vd.montoVentaBolivares) AS bolivares_venta_mes,
                 SUM(vd.cantidadDolaresComprados) AS monto_dolares
                 FROM lOroEntityBundle:VentasDolares vd
                 WHERE MONTH(vd.fechaVenta) = MONTH(CURRENT_TIMESTAMP())
                 GROUP BY mes_venta'
                );
      return $query->getOneOrNullResult();     
    }
    
    public function sumBolivaresPagadosProveedoresMes() {
    
        $config = $this->getEntityManager()->getConfiguration();
        $config->addCustomNumericFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $config->addCustomNumericFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        
      $query = $this->getEntityManager()
                    ->createQuery(
                'SELECT MONTH(pp.fePago) AS mes_venta,
                 SUM(pp.montoPagado) AS bolivares_pagados
                 FROM lOroEntityBundle:PagosProveedores pp
                 WHERE MONTH(pp.fePago) = MONTH(CURRENT_TIMESTAMP())
                 GROUP BY mes_venta'
                );
      return $query->getOneOrNullResult();    
    } 
    
    public function sumBolivaresPagosVariosMes() {
    
        $config = $this->getEntityManager()->getConfiguration();
        $config->addCustomNumericFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $config->addCustomNumericFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        
      $query = $this->getEntityManager()
                    ->createQuery(
                'SELECT MONTH(pv.fePago) AS mes_venta,
                 SUM(pv.montoPago) AS bolivares_pagados
                 FROM lOroEntityBundle:PagosVarios pv
                 WHERE MONTH(pv.fePago) = MONTH(CURRENT_TIMESTAMP())
                 GROUP BY mes_venta'
                );
      return $query->getOneOrNullResult();    
    }  
    
    public function sumDolaresCerradosAupanasMes() {
    
        $config = $this->getEntityManager()->getConfiguration();
        $config->addCustomNumericFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $config->addCustomNumericFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        
      $query = $this->getEntityManager()
                    ->createQuery(
                "SELECT MONTH(pv.feVenta) AS mes_venta,
                 SUM(pv.montoTotalDolar) AS monto
                 FROM lOroEntityBundle:VentasCierres pv
                 WHERE MONTH(pv.feVenta) = MONTH(CURRENT_TIMESTAMP())
                 AND pv.tipoCierre = 'hc'
                 GROUP BY mes_venta"
                );
      return $query->getOneOrNullResult();    
    }    
    
    public function buscarUltimosDolaresCierres() {
      $query = $this->getEntityManager()
                    ->createQuery("SELECT vc.dolarReferencia
                                   FROM lOroEntityBundle:VentasCierres AS vc
                                   WHERE vc.tipoCierre = 'proveedor'
                                   ORDER BY vc.feVenta DESC"
                                 )
                    ->setMaxResults(5);
      
      return $query->getResult();         
    }
    
    public function buscarUltimosDolaresRefVentaDolar($cantidadVentasTomadas) {
      $query = $this->getEntityManager()
                    ->createQuery("SELECT vd.dolarReferencia
                                   FROM lOroEntityBundle:VentasDolares AS vd
                                   ORDER BY vd.fechaVenta DESC"
                                 )
                    ->setMaxResults($cantidadVentasTomadas);
      
      return $query->getResult();         
    }
}
