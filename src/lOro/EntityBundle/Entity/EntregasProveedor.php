<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EntregasProveedor
 *
 * @ORM\Table(name="entrgas_proveedor")
 * @ORM\Entity(repositoryClass="lOro\EntityBundle\Entity\Repository\EntregasProveedorRepository")
 */
class EntregasProveedor
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
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Proveedores")
     */
    private $proveedor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=false)
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string",length=100, nullable=true)
     */
    private $codigo;  
    
    /**
     * @var string
     *
     * @ORM\Column(name="peso_bruto", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $pesoBruto;

    /**
     * @var string
     *
     * @ORM\Column(name="ley", type="decimal", precision=10, scale=4, nullable=false)
     */
    private $ley;

    /**
     * @var string
     *
     * @ORM\Column(name="peso_puro", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $pesoPuro;

    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\TiposMoneda")
     * @ORM\JoinColumn(name="tipo_moneda_id", referencedColumnName="id", nullable=true) 
     */
    private $tipoMoneda;

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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return EntregasProveedor
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     *
     * @return EntregasProveedor
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set pesoBruto
     *
     * @param string $pesoBruto
     *
     * @return EntregasProveedor
     */
    public function setPesoBruto($pesoBruto)
    {
        $this->pesoBruto = $pesoBruto;

        return $this;
    }

    /**
     * Get pesoBruto
     *
     * @return string
     */
    public function getPesoBruto()
    {
        return $this->pesoBruto;
    }

    /**
     * Set ley
     *
     * @param string $ley
     *
     * @return EntregasProveedor
     */
    public function setLey($ley)
    {
        $this->ley = $ley;

        return $this;
    }

    /**
     * Get ley
     *
     * @return string
     */
    public function getLey()
    {
        return $this->ley;
    }

    /**
     * Set pesoPuro
     *
     * @param string $pesoPuro
     *
     * @return EntregasProveedor
     */
    public function setPesoPuro($pesoPuro)
    {
        $this->pesoPuro = $pesoPuro;

        return $this;
    }

    /**
     * Get pesoPuro
     *
     * @return string
     */
    public function getPesoPuro()
    {
        return $this->pesoPuro;
    }

    /**
     * Set proveedor
     *
     * @param \lOro\EntityBundle\Entity\Proveedores $proveedor
     *
     * @return EntregasProveedor
     */
    public function setProveedor(\lOro\EntityBundle\Entity\Proveedores $proveedor = null)
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

    /**
     * Set tipoMoneda
     *
     * @param \lOro\EntityBundle\Entity\TiposMoneda $tipoMoneda
     *
     * @return EntregasProveedor
     */
    public function setTipoMoneda(\lOro\EntityBundle\Entity\TiposMoneda $tipoMoneda = null)
    {
        $this->tipoMoneda = $tipoMoneda;

        return $this;
    }

    /**
     * Get tipoMoneda
     *
     * @return \lOro\EntityBundle\Entity\TiposMoneda
     */
    public function getTipoMoneda()
    {
        return $this->tipoMoneda;
    }
}
