<?php

namespace lOro\AppBundle\Entity\Repository;

/**
 * CargasMasivasEnviadasRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CargasMasivasEnviadasRepository extends \Doctrine\ORM\EntityRepository
{
    
  public function buscarCargasMasivasPorMesAnioCurso($feEnviada = null) {
      $conn = $this->getEntityManager()->getConnection();
           
      $config = $this->getEntityManager()->getConfiguration();
      $config->addCustomNumericFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
      $config->addCustomNumericFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
      
      $mesEjecucion = $feEnviada->format('m');
      $anioEjecucion = $feEnviada->format('Y');
      
      $q = "SELECT COUNT(id) as cantidadCargasMesCurso 
                                   FROM cargas_masivas_enviadas
                                   WHERE MONTH(fe_ejecucion) = $mesEjecucion
                                   AND YEAR(fe_ejecucion) = $anioEjecucion
                                   ORDER BY id DESC;";
      
      
      $stmt = $conn->executeQuery($q);
            
           
      return $stmt->fetch();      
  }
}
