<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EntregasHc
 *
 * @ORM\Table(name="entregas_hc")
 * @ORM\Entity(repositoryClass="lOro\EntityBundle\Entity\Repository\EntregasHcRepository")
 */
class EntregasHc
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="cod_entrega", type="string",length=100, nullable=true)
     */
    private $codEntrega;  
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fe_entrega", type="date", nullable=false)
     */
    private $feEntrega;

    /**
     * @var string
     *
     * @ORM\Column(name="anio", type="string",length=4, nullable=true)
     */
    private $anio;
    
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Piezas")
     */
    private $piezaInicial;
    
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Piezas")
     */
    private $piezaFinal;   
  
    /**
     * @var integer
     *
     * @ORM\Column(name="cant_piezas_entregadas", type="integer",length=4, nullable=true)
     */
    private $cantPiezasEntregadas;


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
     * Set codEntrega
     *
     * @param string $codEntrega
     * @return EntregasHc
     */
    public function setCodEntrega($codEntrega)
    {
        $this->codEntrega = $codEntrega;

        return $this;
    }

    /**
     * Get codEntrega
     *
     * @return string 
     */
    public function getCodEntrega()
    {
        return $this->codEntrega;
    }

    /**
     * Set feEntrega
     *
     * @param \DateTime $feEntrega
     * @return EntregasHc
     */
    public function setFeEntrega($feEntrega)
    {
        $this->feEntrega = $feEntrega;

        return $this;
    }

    /**
     * Get feEntrega
     *
     * @return \DateTime 
     */
    public function getFeEntrega()
    {
        return $this->feEntrega;
    }

    /**
     * Set anio
     *
     * @param string $anio
     * @return EntregasHc
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * Get anio
     *
     * @return string 
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Set cantPiezasEntregadas
     *
     * @param integer $cantPiezasEntregadas
     * @return EntregasHc
     */
    public function setCantPiezasEntregadas($cantPiezasEntregadas)
    {
        $this->cantPiezasEntregadas = $cantPiezasEntregadas;

        return $this;
    }

    /**
     * Get cantPiezasEntregadas
     *
     * @return integer 
     */
    public function getCantPiezasEntregadas()
    {
        return $this->cantPiezasEntregadas;
    }

    /**
     * Set piezaInicial
     *
     * @param \lOro\EntityBundle\Entity\Piezas $piezaInicial
     * @return EntregasHc
     */
    public function setPiezaInicial(\lOro\EntityBundle\Entity\Piezas $piezaInicial = null)
    {
        $this->piezaInicial = $piezaInicial;

        return $this;
    }

    /**
     * Get piezaInicial
     *
     * @return \lOro\EntityBundle\Entity\Piezas 
     */
    public function getPiezaInicial()
    {
        return $this->piezaInicial;
    }

    /**
     * Set piezaFinal
     *
     * @param \lOro\EntityBundle\Entity\Piezas $piezaFinal
     * @return EntregasHc
     */
    public function setPiezaFinal(\lOro\EntityBundle\Entity\Piezas $piezaFinal = null)
    {
        $this->piezaFinal = $piezaFinal;

        return $this;
    }

    /**
     * Get piezaFinal
     *
     * @return \lOro\EntityBundle\Entity\Piezas 
     */
    public function getPiezaFinal()
    {
        return $this->piezaFinal;
    }
}
