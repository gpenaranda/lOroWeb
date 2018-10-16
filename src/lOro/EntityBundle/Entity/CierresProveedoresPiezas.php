<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CierresProveedoresPiezas
 *
 * @ORM\Table(name="cierres_proveedores_piezas")
 * @ORM\Entity()
 */
class CierresProveedoresPiezas
{
  
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\VentasCierres", inversedBy="piezasCierresProveedores")
     * @ORM\JoinColumn(name="cierre_proveedor_id", referencedColumnName="id")
     */
    private $cierreProveedor;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Piezas", inversedBy="cierresProveedoresPiezas")
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
     * @return CierresProveedoresPiezas
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
     * Set cierreProveedor
     *
     * @param \lOro\EntityBundle\Entity\VentasCierres $cierreProveedor
     * @return CierresProveedoresPiezas
     */
    public function setCierreProveedor(\lOro\EntityBundle\Entity\VentasCierres $cierreProveedor)
    {
        $this->cierreProveedor = $cierreProveedor;

        return $this;
    }

    /**
     * Get cierreProveedor
     *
     * @return \lOro\EntityBundle\Entity\VentasCierres 
     */
    public function getCierreProveedor()
    {
        return $this->cierreProveedor;
    }

    /**
     * Set pieza
     *
     * @param \lOro\EntityBundle\Entity\Piezas $pieza
     * @return CierresProveedoresPiezas
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
