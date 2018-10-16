<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * VentasCierres
 *
 * @ORM\Table(name="ventas_cierres")
 * @ORM\Entity(repositoryClass="lOro\EntityBundle\Entity\Repository\VentasCierresRepository")
 */
class VentasCierres
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
     * @ORM\Column(name="fe_venta", type="datetime", nullable=false)
     */
    private $feVenta;

    /**
     * @var string
     *
     * @ORM\Column(name="cantidad_total_venta", type="decimal", precision=14, scale=2, nullable=false)
     */
    private $cantidadTotalVenta;

    /**
     * @var string
     *
     * @ORM\Column(name="valor_onza", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $valorOnza;

    /**
     * @var string
     *
     * @ORM\Column(name="monto_total_dolar", type="decimal", precision=14, scale=2, nullable=true)
     */
    private $montoTotalDolar;

    /**
     * @var string
     *
     * @ORM\Column(name="monto_bs_formula", type="decimal", precision=14, scale=2, nullable=true)
     */
    private $montoBsFormula;

    /**
     * @var string
     *
     * @ORM\Column(name="monto_bs_cierre", type="decimal", precision=14, scale=2, nullable=true)
     */
    private $montoBsCierre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="monto_bs_cierre_por_gramo", type="decimal", precision=14, scale=2, nullable=true)
     */
    private $montoBsCierrePorGramo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="monto_bs_formula_por_gramo", type="decimal", precision=14, scale=2, nullable=true)
     */
    private $montoBsFormulaPorGramo;    
    
    /**
     * @var string
     *
     * @ORM\Column(name="dolar_referencia", type="decimal", precision=14, scale=2, nullable=true)
     */
    private $dolarReferencia;
    
    /**
     * @var string
     *
     * @ORM\Column(name="tipo_cierre", type="string",length=30, nullable=true)
     */
    private $tipoCierre;    
    
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Proveedores", inversedBy="ventasCierres")
     * @ORM\JoinColumn(name="proveedor_id", referencedColumnName="id") 
     */
    private $proveedorCierre;

    
    /**
     * @var string
     *
     * @ORM\Column(name="estatus", type="string", length=2, nullable=false)
     */
    private $estatus;
    
 
    
    /**
     * @var string
     *
     * @ORM\Column(name="estatus_relacion", type="string", length=2, nullable=true)
     */
    private $estatusRelacion;   
    
    /**
     * @var string
     *
     * @ORM\Column(name="gramos_cerrados_restantes", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $gramosCerradosRestantes;    
    
    /**
     * @var string
     *
     * @ORM\Column(name="gramos_cerrados_restantes_piezas", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $gramosCerradosRestantesPiezas;
    
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\MargenesGanancias", inversedBy="ventasCierres")
     * @ORM\JoinColumn(name="margen_ganancia_id", referencedColumnName="id")
     */
    private $margenGanancia;

    
   /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\VentasEntregas", mappedBy="ventasCierres", cascade={"remove"})
     */
    private $entregas;
    
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Balances")
     * @ORM\JoinColumn(name="balance_id", referencedColumnName="id") 
     */
    private $balance;
    
   /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\CierresHcEntregas", mappedBy="cierreHc", cascade={"remove"})
     */
    private $entregasCierresHc;   
    
   /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\CierresProveedoresPiezas", mappedBy="cierreProveedor", cascade={"remove"})
     */
    private $piezasCierresProveedores;       
    
    
   /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\CierresProveedoresEntregas", mappedBy="cierreProveedor", cascade={"remove"})
     */
    private $entregasCierresProveedor;     
    
    /**
     * @var string
     *
     * @ORM\Column(name="dolar_referencia_dia", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $dolarReferenciaDia;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\TiposMoneda")
     * @ORM\JoinColumn(name="tipo_moneda_cierre_id", referencedColumnName="id", nullable=true) 
     */
    private $tipoMonedaCierre; 
    
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\TiposMoneda")
     * @ORM\JoinColumn(name="tipo_moneda_cierre_hc_id", referencedColumnName="id", nullable=true) 
     */
    private $tipoMonedaCierreHc; 

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->entregas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set feVenta
     *
     * @param \DateTime $feVenta
     * @return VentasCierres
     */
    public function setFeVenta($feVenta)
    {
        $this->feVenta = $feVenta;

        return $this;
    }

    /**
     * Get feVenta
     *
     * @return \DateTime 
     */
    public function getFeVenta()
    {
        return $this->feVenta;
    }

    /**
     * Set cantidadTotalVenta
     *
     * @param string $cantidadTotalVenta
     * @return VentasCierres
     */
    public function setCantidadTotalVenta($cantidadTotalVenta)
    {
        $this->cantidadTotalVenta = $cantidadTotalVenta;

        return $this;
    }

    /**
     * Get cantidadTotalVenta
     *
     * @return string 
     */
    public function getCantidadTotalVenta()
    {
        return $this->cantidadTotalVenta;
    }

    /**
     * Set valorOnza
     *
     * @param string $valorOnza
     * @return VentasCierres
     */
    public function setValorOnza($valorOnza)
    {
        $this->valorOnza = $valorOnza;

        return $this;
    }

    /**
     * Get valorOnza
     *
     * @return string 
     */
    public function getValorOnza()
    {
        return $this->valorOnza;
    }

    /**
     * Set montoTotalDolar
     *
     * @param string $montoTotalDolar
     * @return VentasCierres
     */
    public function setMontoTotalDolar($montoTotalDolar)
    {
        $this->montoTotalDolar = $montoTotalDolar;

        return $this;
    }

    /**
     * Get montoTotalDolar
     *
     * @return string 
     */
    public function getMontoTotalDolar()
    {
        return $this->montoTotalDolar;
    }

    /**
     * Set montoBsFormula
     *
     * @param string $montoBsFormula
     * @return VentasCierres
     */
    public function setMontoBsFormula($montoBsFormula)
    {
        $this->montoBsFormula = $montoBsFormula;

        return $this;
    }

    /**
     * Get montoBsFormula
     *
     * @return string 
     */
    public function getMontoBsFormula()
    {
        return $this->montoBsFormula;
    }

    /**
     * Set montoBsCierre
     *
     * @param string $montoBsCierre
     * @return VentasCierres
     */
    public function setMontoBsCierre($montoBsCierre)
    {
        $this->montoBsCierre = $montoBsCierre;

        return $this;
    }

    /**
     * Get montoBsCierre
     *
     * @return string 
     */
    public function getMontoBsCierre()
    {
        return $this->montoBsCierre;
    }

    /**
     * Set dolarReferencia
     *
     * @param string $dolarReferencia
     * @return VentasCierres
     */
    public function setDolarReferencia($dolarReferencia)
    {
        $this->dolarReferencia = $dolarReferencia;

        return $this;
    }

    /**
     * Get dolarReferencia
     *
     * @return string 
     */
    public function getDolarReferencia()
    {
        return $this->dolarReferencia;
    }

    /**
     * Set estatus
     *
     * @param string $estatus
     * @return VentasCierres
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
     * Set proveedorCierre
     *
     * @param \lOro\EntityBundle\Entity\Proveedores $proveedorCierre
     * @return VentasCierres
     */
    public function setProveedorCierre(\lOro\EntityBundle\Entity\Proveedores $proveedorCierre = null)
    {
        $this->proveedorCierre = $proveedorCierre;

        return $this;
    }

    /**
     * Get proveedorCierre
     *
     * @return \lOro\EntityBundle\Entity\Proveedores 
     */
    public function getProveedorCierre()
    {
        return $this->proveedorCierre;
    }

    /**
     * Set margenGanancia
     *
     * @param \lOro\EntityBundle\Entity\MargenesGanancias $margenGanancia
     * @return VentasCierres
     */
    public function setMargenGanancia(\lOro\EntityBundle\Entity\MargenesGanancias $margenGanancia = null)
    {
        $this->margenGanancia = $margenGanancia;

        return $this;
    }

    /**
     * Get margenGanancia
     *
     * @return \lOro\EntityBundle\Entity\MargenesGanancias 
     */
    public function getMargenGanancia()
    {
        return $this->margenGanancia;
    }

    /**
     * Add entregas
     *
     * @param \lOro\EntityBundle\Entity\VentasEntregas $entregas
     * @return VentasCierres
     */
    public function addEntrega(\lOro\EntityBundle\Entity\VentasEntregas $entregas)
    {
        $this->entregas[] = $entregas;

        return $this;
    }

    /**
     * Remove entregas
     *
     * @param \lOro\EntityBundle\Entity\VentasEntregas $entregas
     */
    public function removeEntrega(\lOro\EntityBundle\Entity\VentasEntregas $entregas)
    {
        $this->entregas->removeElement($entregas);
    }

    /**
     * Get entregas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEntregas()
    {
        return $this->entregas;
    }

    /**
     * Set balance
     *
     * @param \lOro\EntityBundle\Entity\Balances $balance
     * @return VentasCierres
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
     * Set tipoCierre
     *
     * @param boolean $tipoCierre
     * @return VentasCierres
     */
    public function setTipoCierre($tipoCierre)
    {
        $this->tipoCierre = $tipoCierre;

        return $this;
    }

    /**
     * Get tipoCierre
     *
     * @return boolean 
     */
    public function getTipoCierre()
    {
        return $this->tipoCierre;
    }

    /**
     * Set estatusRelacion
     *
     * @param string $estatusRelacion
     * @return VentasCierres
     */
    public function setEstatusRelacion($estatusRelacion)
    {
        $this->estatusRelacion = $estatusRelacion;

        return $this;
    }

    /**
     * Get estatusRelacion
     *
     * @return string 
     */
    public function getEstatusRelacion()
    {
        return $this->estatusRelacion;
    }



    /**
     * Set montoBsCierrePorGramo
     *
     * @param string $montoBsCierrePorGramo
     * @return VentasCierres
     */
    public function setMontoBsCierrePorGramo($montoBsCierrePorGramo)
    {
        $this->montoBsCierrePorGramo = $montoBsCierrePorGramo;

        return $this;
    }

    /**
     * Get montoBsCierrePorGramo
     *
     * @return string 
     */
    public function getMontoBsCierrePorGramo()
    {
        return $this->montoBsCierrePorGramo;
    }

    /**
     * Set montoBsFormulaPorGramo
     *
     * @param string $montoBsFormulaPorGramo
     * @return VentasCierres
     */
    public function setMontoBsFormulaPorGramo($montoBsFormulaPorGramo)
    {
        $this->montoBsFormulaPorGramo = $montoBsFormulaPorGramo;

        return $this;
    }

    /**
     * Get montoBsFormulaPorGramo
     *
     * @return string 
     */
    public function getMontoBsFormulaPorGramo()
    {
        return $this->montoBsFormulaPorGramo;
    }

   

    /**
     * Add entregasCierresHc
     *
     * @param \lOro\EntityBundle\Entity\CierresHcEntregas $entregasCierresHc
     * @return VentasCierres
     */
    public function addEntregasCierresHc(\lOro\EntityBundle\Entity\CierresHcEntregas $entregasCierresHc)
    {
        $this->entregasCierresHc[] = $entregasCierresHc;

        return $this;
    }

    /**
     * Remove entregasCierresHc
     *
     * @param \lOro\EntityBundle\Entity\CierresHcEntregas $entregasCierresHc
     */
    public function removeEntregasCierresHc(\lOro\EntityBundle\Entity\CierresHcEntregas $entregasCierresHc)
    {
        $this->entregasCierresHc->removeElement($entregasCierresHc);
    }

    /**
     * Get entregasCierresHc
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEntregasCierresHc()
    {
        return $this->entregasCierresHc;
    }

    /**
     * Set gramosCerradosRestantes
     *
     * @param string $gramosCerradosRestantes
     * @return VentasCierres
     */
    public function setGramosCerradosRestantes($gramosCerradosRestantes)
    {
        $this->gramosCerradosRestantes = $gramosCerradosRestantes;

        return $this;
    }

    /**
     * Get gramosCerradosRestantes
     *
     * @return string 
     */
    public function getGramosCerradosRestantes()
    {
        return $this->gramosCerradosRestantes;
    }

    /**
     * Add entregasCierresProveedor
     *
     * @param \lOro\EntityBundle\Entity\CierresProveedoresEntregas $entregasCierresProveedor
     * @return VentasCierres
     */
    public function addEntregasCierresProveedor(\lOro\EntityBundle\Entity\CierresProveedoresEntregas $entregasCierresProveedor)
    {
        $this->entregasCierresProveedor[] = $entregasCierresProveedor;

        return $this;
    }

    /**
     * Remove entregasCierresProveedor
     *
     * @param \lOro\EntityBundle\Entity\CierresProveedoresEntregas $entregasCierresProveedor
     */
    public function removeEntregasCierresProveedor(\lOro\EntityBundle\Entity\CierresProveedoresEntregas $entregasCierresProveedor)
    {
        $this->entregasCierresProveedor->removeElement($entregasCierresProveedor);
    }

    /**
     * Get entregasCierresProveedor
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEntregasCierresProveedor()
    {
        return $this->entregasCierresProveedor;
    }

    /**
     * Set gramosCerradosRestantesPiezas
     *
     * @param string $gramosCerradosRestantesPiezas
     * @return VentasCierres
     */
    public function setGramosCerradosRestantesPiezas($gramosCerradosRestantesPiezas)
    {
        $this->gramosCerradosRestantesPiezas = $gramosCerradosRestantesPiezas;

        return $this;
    }

    /**
     * Get gramosCerradosRestantesPiezas
     *
     * @return string 
     */
    public function getGramosCerradosRestantesPiezas()
    {
        return $this->gramosCerradosRestantesPiezas;
    }

    /**
     * Set dolarReferenciaDia
     *
     * @param string $dolarReferenciaDia
     * @return VentasCierres
     */
    public function setDolarReferenciaDia($dolarReferenciaDia)
    {
        $this->dolarReferenciaDia = $dolarReferenciaDia;

        return $this;
    }

    /**
     * Get dolarReferenciaDia
     *
     * @return string 
     */
    public function getDolarReferenciaDia()
    {
        return $this->dolarReferenciaDia;
    }

    /**
     * Add piezasCierresProveedores
     *
     * @param \lOro\EntityBundle\Entity\CierresProveedoresPiezas $piezasCierresProveedores
     * @return VentasCierres
     */
    public function addPiezasCierresProveedore(\lOro\EntityBundle\Entity\CierresProveedoresPiezas $piezasCierresProveedores)
    {
        $this->piezasCierresProveedores[] = $piezasCierresProveedores;

        return $this;
    }

    /**
     * Remove piezasCierresProveedores
     *
     * @param \lOro\EntityBundle\Entity\CierresProveedoresPiezas $piezasCierresProveedores
     */
    public function removePiezasCierresProveedore(\lOro\EntityBundle\Entity\CierresProveedoresPiezas $piezasCierresProveedores)
    {
        $this->piezasCierresProveedores->removeElement($piezasCierresProveedores);
    }

    /**
     * Get piezasCierresProveedores
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPiezasCierresProveedores()
    {
        return $this->piezasCierresProveedores;
    }

    /**
     * Set tipoMonedaCierre
     *
     * @param \lOro\EntityBundle\Entity\TiposMoneda $tipoMonedaCierre
     * @return VentasCierres
     */
    public function setTipoMonedaCierre(\lOro\EntityBundle\Entity\TiposMoneda $tipoMonedaCierre = null)
    {
        $this->tipoMonedaCierre = $tipoMonedaCierre;

        return $this;
    }

    /**
     * Get tipoMonedaCierre
     *
     * @return \lOro\EntityBundle\Entity\TiposMoneda 
     */
    public function getTipoMonedaCierre()
    {
        return $this->tipoMonedaCierre;
    }


    /**
     * Set tipoMonedaCierreHc
     *
     * @param \lOro\EntityBundle\Entity\TiposMoneda $tipoMonedaCierreHc
     *
     * @return VentasCierres
     */
    public function setTipoMonedaCierreHc(\lOro\EntityBundle\Entity\TiposMoneda $tipoMonedaCierreHc = null)
    {
        $this->tipoMonedaCierreHc = $tipoMonedaCierreHc;

        return $this;
    }

    /**
     * Get tipoMonedaCierreHc
     *
     * @return \lOro\EntityBundle\Entity\TiposMoneda
     */
    public function getTipoMonedaCierreHc()
    {
        return $this->tipoMonedaCierreHc;
    }
}
