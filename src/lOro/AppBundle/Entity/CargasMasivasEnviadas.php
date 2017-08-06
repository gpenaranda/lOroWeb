<?php

namespace lOro\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CargasMasivasEnviadas
 *
 * @ORM\Table(name="cargas_masivas_enviadas")
 * @ORM\Entity(repositoryClass="lOro\AppBundle\Entity\Repository\CargasMasivasEnviadasRepository")
 */
class CargasMasivasEnviadas
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fe_registro", type="date", nullable=false)
     */
    private $feRegistro;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fe_ejecucion", type="date", nullable=false)
     */
    private $feEjecucion;    

    /**
     * @var string
     *
     * @ORM\Column(name="cod_carga_masiva", type="string", length=45, nullable=false)
     */
    private $codCargaMasiva;

    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set feRegistro
     *
     * @param \DateTime $feRegistro
     *
     * @return CargasMasivasEnviadas
     */
    public function setFeRegistro($feRegistro)
    {
        $this->feRegistro = $feRegistro;

        return $this;
    }

    /**
     * Get feRegistro
     *
     * @return \DateTime
     */
    public function getFeRegistro()
    {
        return $this->feRegistro;
    }

    /**
     * Set feEjecucion
     *
     * @param \DateTime $feEjecucion
     *
     * @return CargasMasivasEnviadas
     */
    public function setFeEjecucion($feEjecucion)
    {
        $this->feEjecucion = $feEjecucion;

        return $this;
    }

    /**
     * Get feEjecucion
     *
     * @return \DateTime
     */
    public function getFeEjecucion()
    {
        return $this->feEjecucion;
    }

    /**
     * Set codCargaMasiva
     *
     * @param string $codCargaMasiva
     *
     * @return CargasMasivasEnviadas
     */
    public function setCodCargaMasiva($codCargaMasiva)
    {
        $this->codCargaMasiva = $codCargaMasiva;

        return $this;
    }

    /**
     * Get codCargaMasiva
     *
     * @return string
     */
    public function getCodCargaMasiva()
    {
        return $this->codCargaMasiva;
    }
}
