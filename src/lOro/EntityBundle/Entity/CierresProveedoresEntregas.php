<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CierresProveedoresEntregas
 *
 * @ORM\Table(name="cierres_proveedores_entregas")
 * @ORM\Entity()
 */
class CierresProveedoresEntregas
{
  
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\VentasCierres", inversedBy="entregasCierresProveedor")
     * @ORM\JoinColumn(name="cierre_proveedor_id", referencedColumnName="id")
     */
    private $cierreProveedor;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Entregas", inversedBy="cierresProveedorEntregas")
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
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Proveedores")
     * @ORM\JoinColumn(name="proveedor_id", referencedColumnName="id", nullable=true)
     */
    private $proveedor;    


    /**
     * Set materialEntregado
     *
     * @param string $materialEntregado
     * @return CierresProveedoresEntregas
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
     * @return CierresProveedoresEntregas
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
     * Set entrega
     *
     * @param \lOro\EntityBundle\Entity\Entregas $entrega
     * @return CierresProveedoresEntregas
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

    /**
     * Set proveedor
     *
     * @param \lOro\EntityBundle\Entity\Proveedores $proveedor
     * @return CierresProveedoresEntregas
     */
    public function setProveedor(\lOro\EntityBundle\Entity\Proveedores $proveedor)
    {
        $this->proveedor = $proveedor;

        return $this;
    }

    /**
     * Get proveedor
     *
     * @return \lOro\EntityBundle\Entity\Proveedores 
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }
}
