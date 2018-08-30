<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CierresHcEntregas
 *
 * @ORM\Table(name="cierres_hc_entregas")
 * @ORM\Entity()
 */
class CierresHcEntregas
{
  
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\VentasCierres", inversedBy="entregasCierresHc")
     * @ORM\JoinColumn(name="cierre_hc_id", referencedColumnName="id")
     */
    private $cierreHc;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Entregas", inversedBy="cierresHcEntregas")
     * @ORM\JoinColumn(name="entrega_id", referencedColumnName="id")
     */
    private $entrega;
    
    /**
     * @var string
     *
     * @ORM\Column(name="material_entregado", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $materialEntregado;    

    /**
     * Set materialEntregado
     *
     * @param string $materialEntregado
     * @return CierresHcEntregas
     */
    public function setMaterialEntregado($materialEntregado)
    {
        $this->materialEntregado = $materialEntregado;

        return $this;
    }

    /**
     * Get materialEntregado
     *
     * @return string 
     */
    public function getMaterialEntregado()
    {
        return $this->materialEntregado;
    }

    /**
     * Set cierreHc
     *
     * @param \lOro\EntityBundle\Entity\VentasCierres $cierreHc
     * @return CierresHcEntregas
     */
    public function setCierreHc(\lOro\EntityBundle\Entity\VentasCierres $cierreHc)
    {
        $this->cierreHc = $cierreHc;

        return $this;
    }

    /**
     * Get cierreHc
     *
     * @return \lOro\EntityBundle\Entity\VentasCierres 
     */
    public function getCierreHc()
    {
        return $this->cierreHc;
    }

    /**
     * Set entrega
     *
     * @param \lOro\EntityBundle\Entity\Entregas $entrega
     * @return CierresHcEntregas
     */
    public function setEntrega(\lOro\EntityBundle\Entity\Entregas $entrega)
    {
        $this->entrega = $entrega;

        return $this;
    }

    /**
     * Get entrega
     *
     * @return \lOro\EntityBundle\Entity\Entregas 
     */
    public function getEntrega()
    {
        return $this->entrega;
    }
}
