<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CierresHcPiezas
 *
 * @ORM\Table(name="cierres_hc_piezas")
 * @ORM\Entity()
 */
class CierresHcPiezas
{
  
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\VentasCierres", inversedBy="piezasCierresHc")
     * @ORM\JoinColumn(name="cierre_hc_id", referencedColumnName="id")
     */
    private $cierreHc;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Piezas", inversedBy="cierresHcPiezas")
     * @ORM\JoinColumn(name="pieza_id", referencedColumnName="id")
     */
    private $pieza;
    
    

    
    
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
     * @return CierresHcPiezas
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
     * @return CierresHcPiezas
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
     * Set pieza
     *
     * @param \lOro\EntityBundle\Entity\Piezas $pieza
     * @return CierresHcPiezas
     */
    public function setPieza(\lOro\EntityBundle\Entity\Piezas $pieza)
    {
        $this->pieza = $pieza;

        return $this;
    }

    /**
     * Get pieza
     *
     * @return \lOro\EntityBundle\Entity\Piezas 
     */
    public function getPieza()
    {
        return $this->pieza;
    }
}
