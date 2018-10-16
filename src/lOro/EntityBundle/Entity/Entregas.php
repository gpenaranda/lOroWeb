<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entregas
 *
 * @ORM\Table(name="entregas")
 * @ORM\Entity(repositoryClass="lOro\EntityBundle\Entity\Repository\EntregasRepository")
 */
class Entregas
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="usuario_id", type="integer")
     *
     */
    private $usuarioId;

    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fe_entrega", type="date", nullable=false)
     */
    private $feEntrega;

    /**
     * @var string
     *
     * @ORM\Column(name="peso_bruto_entrega", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $pesoBrutoEntrega;

    /**
     * @var string
     *
     * @ORM\Column(name="ley", type="decimal", precision=10, scale=4, nullable=false)
     */
    private $ley;

    /**
     * @var string
     *
     * @ORM\Column(name="peso_puro_entrega", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $pesoPuroEntrega;
    
    /**
     * @var string
     *
     * @ORM\Column(name="estatus", type="string",length=2, nullable=true)
     */
    private $estatus;    

    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Proveedores", inversedBy="entregas")
     */
    private $proveedor;
    
   /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\VentasEntregas", mappedBy="entregas")
     */
    private $ventasCierresEntrega;    

    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Balances")
     * @ORM\JoinColumn(name="balance_id", referencedColumnName="id") 
     */
    private $balance;
    
    /**
     * @var string
     *
     * @ORM\Column(name="restante_por_relacion", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $restantePorRelacion;
    
    /**
     * @var string
     *
     * @ORM\Column(name="restante_por_relacion_proveedor", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $restantePorRelacionProveedor;    

    /**
     *
     * @ORM\OneToMany(targetEntity="Piezas", mappedBy="entrega",cascade={"persist"})
     */
    private $piezasEntregadas;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id_mensual", type="integer",nullable=true)
     *
     */
    private $idMensual;    
    
   /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\CierresHcEntregas", mappedBy="entrega", cascade={"remove"})
     */
    private $cierresHcEntregas;      
    
   /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\CierresProveedoresEntregas", mappedBy="entrega", cascade={"remove"})
     */
    private $cierresProveedorEntregas;
    
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\TiposMoneda")
     * @ORM\JoinColumn(name="tipo_moneda_id", referencedColumnName="id", nullable=true) 
     */
    private $tipoMonedaEntrega;    

    /**
     * @var boolean
     *
     * @ORM\Column(name="entrega_realizada_hc", type="boolean", nullable=true)
     */
    private $entregaRealizadaHc;       
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ventasCierresEntrega = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set feEntrega
     *
     * @param \DateTime $feEntrega
     * @return Entregas
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
     * Set pesoBrutoEntrega
     *
     * @param string $pesoBrutoEntrega
     * @return Entregas
     */
    public function setPesoBrutoEntrega($pesoBrutoEntrega)
    {
        $this->pesoBrutoEntrega = $pesoBrutoEntrega;

        return $this;
    }

    /**
     * Get pesoBrutoEntrega
     *
     * @return string 
     */
    public function getPesoBrutoEntrega()
    {
        return $this->pesoBrutoEntrega;
    }

    /**
     * Set ley
     *
     * @param string $ley
     * @return Entregas
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
     * Set pesoPuroEntrega
     *
     * @param string $pesoPuroEntrega
     * @return Entregas
     */
    public function setPesoPuroEntrega($pesoPuroEntrega)
    {
        $this->pesoPuroEntrega = $pesoPuroEntrega;

        return $this;
    }

    /**
     * Get pesoPuroEntrega
     *
     * @return string 
     */
    public function getPesoPuroEntrega()
    {
        return $this->pesoPuroEntrega;
    }

    /**
     * Set proveedor
     *
     * @param \lOro\EntityBundle\Entity\Proveedores $proveedor
     * @return Entregas
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
     * Add ventasCierresEntrega
     *
     * @param \lOro\EntityBundle\Entity\VentasEntregas $ventasCierresEntrega
     * @return Entregas
     */
    public function addVentasCierresEntrega(\lOro\EntityBundle\Entity\VentasEntregas $ventasCierresEntrega)
    {
        $this->ventasCierresEntrega[] = $ventasCierresEntrega;

        return $this;
    }

    /**
     * Remove ventasCierresEntrega
     *
     * @param \lOro\EntityBundle\Entity\VentasEntregas $ventasCierresEntrega
     */
    public function removeVentasCierresEntrega(\lOro\EntityBundle\Entity\VentasEntregas $ventasCierresEntrega)
    {
        $this->ventasCierresEntrega->removeElement($ventasCierresEntrega);
    }

    /**
     * Get ventasCierresEntrega
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVentasCierresEntrega()
    {
        return $this->ventasCierresEntrega;
    }

    /**
     * Set balance
     *
     * @param \lOro\EntityBundle\Entity\Balances $balance
     * @return Entregas
     */
    public function setBalance(\lOro\EntityBundle\Entity\Balances $balance = null)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return \lOro\EntityBundle\Entity\Balances 
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set estatus
     *
     * @param string $estatus
     * @return Entregas
     */
    public function setEstatus($estatus)
    {
        $this->estatus = $estatus;

        return $this;
    }

    /**
     * Get estatus
     *
     * @return string 
     */
    public function getEstatus()
    {
        return $this->estatus;
    }

    /**
     * Set restantePorRelacion
     *
     * @param string $restantePorRelacion
     * @return Entregas
     */
    public function setRestantePorRelacion($restantePorRelacion)
    {
        $this->restantePorRelacion = $restantePorRelacion;

        return $this;
    }

    /**
     * Get restantePorRelacion
     *
     * @return string 
     */
    public function getRestantePorRelacion()
    {
        return $this->restantePorRelacion;
    }

 

    /**
     * Set id
     *
     * @param integer $id
     * @return Entregas
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

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
     * Set usuarioId
     *
     * @param integer $usuarioId
     * @return Entregas
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;

        return $this;
    }

    /**
     * Get usuarioId
     *
     * @return integer 
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Set piezasEntregadas
     *
     * @param \lOro\EntityBundle\Entity\Piezas $piezasEntregadas
     * @return Entregas
     */
    public function setPiezasEntregadas(\lOro\EntityBundle\Entity\Piezas $piezasEntregadas)
    {
        $this->piezasEntregadas = $piezasEntregadas;

        return $this;
    }

    /**
     * Get piezasEntregadas
     *
     * @return \lOro\EntityBundle\Entity\Piezas 
     */
    public function getPiezasEntregadas()
    {
        return $this->piezasEntregadas;
    }

    /**
     * Add piezasEntregadas
     *
     * @param \lOro\EntityBundle\Entity\Piezas $piezasEntregadas
     * @return Entregas
     */
    public function addPiezasEntregada(\lOro\EntityBundle\Entity\Piezas $piezasEntregadas)
    {
        $this->piezasEntregadas[] = $piezasEntregadas;

        return $this;
    }

    /**
     * Remove piezasEntregadas
     *
     * @param \lOro\EntityBundle\Entity\Piezas $piezasEntregadas
     */
    public function removePiezasEntregada(\lOro\EntityBundle\Entity\Piezas $piezasEntregadas)
    {
        $this->piezasEntregadas->removeElement($piezasEntregadas);
    }

    /**
     * Set idMensual
     *
     * @param integer $idMensual
     * @return Entregas
     */
    public function setIdMensual($idMensual)
    {
        $this->idMensual = $idMensual;

        return $this;
    }

    /**
     * Get idMensual
     *
     * @return integer 
     */
    public function getIdMensual()
    {
        return $this->idMensual;
    }

    /**
     * Add cierresHcEntregas
     *
     * @param \lOro\EntityBundle\Entity\CierresHcEntregas $cierresHcEntregas
     * @return Entregas
     */
    public function addCierresHcEntrega(\lOro\EntityBundle\Entity\CierresHcEntregas $cierresHcEntregas)
    {
        $this->cierresHcEntregas[] = $cierresHcEntregas;

        return $this;
    }

    /**
     * Remove cierresHcEntregas
     *
     * @param \lOro\EntityBundle\Entity\CierresHcEntregas $cierresHcEntregas
     */
    public function removeCierresHcEntrega(\lOro\EntityBundle\Entity\CierresHcEntregas $cierresHcEntregas)
    {
        $this->cierresHcEntregas->removeElement($cierresHcEntregas);
    }

    /**
     * Get cierresHcEntregas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCierresHcEntregas()
    {
        return $this->cierresHcEntregas;
    }

    /**
     * Add cierresProveedorEntregas
     *
     * @param \lOro\EntityBundle\Entity\CierresProveedoresEntregas $cierresProveedorEntregas
     * @return Entregas
     */
    public function addCierresProveedorEntrega(\lOro\EntityBundle\Entity\CierresProveedoresEntregas $cierresProveedorEntregas)
    {
        $this->cierresProveedorEntregas[] = $cierresProveedorEntregas;

        return $this;
    }

    /**
     * Remove cierresProveedorEntregas
     *
     * @param \lOro\EntityBundle\Entity\CierresProveedoresEntregas $cierresProveedorEntregas
     */
    public function removeCierresProveedorEntrega(\lOro\EntityBundle\Entity\CierresProveedoresEntregas $cierresProveedorEntregas)
    {
        $this->cierresProveedorEntregas->removeElement($cierresProveedorEntregas);
    }

    /**
     * Get cierresProveedorEntregas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCierresProveedorEntregas()
    {
        return $this->cierresProveedorEntregas;
    }

    /**
     * Set restantePorRelacionProveedor
     *
     * @param string $restantePorRelacionProveedor
     * @return Entregas
     */
    public function setRestantePorRelacionProveedor($restantePorRelacionProveedor)
    {
        $this->restantePorRelacionProveedor = $restantePorRelacionProveedor;

        return $this;
    }

    /**
     * Get restantePorRelacionProveedor
     *
     * @return string 
     */
    public function getRestantePorRelacionProveedor()
    {
        return $this->restantePorRelacionProveedor;
    }

    /**
     * Set tipoMonedaEntrega
     *
     * @param \lOro\EntityBundle\Entity\TiposMoneda $tipoMonedaEntrega
     * @return Entregas
     */
    public function setTipoMonedaEntrega(\lOro\EntityBundle\Entity\TiposMoneda $tipoMonedaEntrega = null)
    {
        $this->tipoMonedaEntrega = $tipoMonedaEntrega;

        return $this;
    }

    /**
     * Get tipoMonedaEntrega
     *
     * @return \lOro\EntityBundle\Entity\TiposMoneda 
     */
    public function getTipoMonedaEntrega()
    {
        return $this->tipoMonedaEntrega;
    }

    /**
     * Set entregaRealizadaHc
     *
     * @param boolean $entregaRealizadaHc
     *
     * @return Entregas
     */
    public function setEntregaRealizadaHc($entregaRealizadaHc)
    {
        $this->entregaRealizadaHc = $entregaRealizadaHc;

        return $this;
    }

    /**
     * Get entregaRealizadaHc
     *
     * @return boolean
     */
    public function getEntregaRealizadaHc()
    {
        return $this->entregaRealizadaHc;
    }
}
