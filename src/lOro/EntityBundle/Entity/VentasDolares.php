<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VentasDolares
 *
 * @ORM\Table(name="ventas_dolares")
 * @ORM\Entity(repositoryClass="lOro\EntityBundle\Entity\Repository\VentasDolaresRepository")
 */
class VentasDolares
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_venta", type="date")
     */
    private $fechaVenta;

    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Proveedores")
     * @ORM\JoinColumn(name="comprador_id", referencedColumnName="id",nullable=true) 
     */
    private $comprador;
    
    /**
     * @var string
     *
     * @ORM\Column(name="empresa_venta", type="string",length=255,nullable=true)
     */
    private $empresa;   

    /**
     * @var string
     *
     * @ORM\Column(name="cantidad_dolares_comprados", type="decimal",precision=10, scale=2)
     */
    private $cantidadDolaresComprados;

    /**
     * @var string
     *
     * @ORM\Column(name="dolar_referencia", type="decimal",precision=10, scale=2)
     */
    private $dolarReferencia;
    
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\TiposMoneda", inversedBy="ventasDolares")
     * @ORM\JoinColumn(name="tipo_moneda_id", referencedColumnName="id", nullable=true) 
     */
    private $tipoMoneda;     
    
    /**
     * @var string
     *
     * @ORM\Column(name="monto_venta_bolivares", type="decimal",precision=14, scale=2)
     */
    private $montoVentaBolivares;   
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="dolares_entregados", type="boolean")
     */
    private $dolaresEntregados = false;        
    
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Balances")
     * @ORM\JoinColumn(name="balance_id", referencedColumnName="id") 
     */
    private $balance;    

    /**
     * @var boolean
     *
     * @ORM\Column(name="conciliado_en_caja", type="boolean")
     */
    private $conciliadoEnCaja = FALSE; 
    
    /**
     * @var string
     *
     * @ORM\Column(name="cotizacion_referencia", type="decimal",precision=14,scale=2,nullable=true)
     */
    private $cotizacionReferencia;      
    
    
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
     * Set fechaVenta
     *
     * @param \DateTime $fechaVenta
     * @return VentasDolares
     */
    public function setFechaVenta($fechaVenta)
    {
        $this->fechaVenta = $fechaVenta;

        return $this;
    }

    /**
     * Get fechaVenta
     *
     * @return \DateTime 
     */
    public function getFechaVenta()
    {
        return $this->fechaVenta;
    }

    /**
     * Set empresa
     *
     * @param string $empresa
     * @return VentasDolares
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return string 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set cantidadDolaresComprados
     *
     * @param string $cantidadDolaresComprados
     * @return VentasDolares
     */
    public function setCantidadDolaresComprados($cantidadDolaresComprados)
    {
        $this->cantidadDolaresComprados = $cantidadDolaresComprados;

        return $this;
    }

    /**
     * Get cantidadDolaresComprados
     *
     * @return string 
     */
    public function getCantidadDolaresComprados()
    {
        return $this->cantidadDolaresComprados;
    }

    /**
     * Set dolarReferencia
     *
     * @param string $dolarReferencia
     * @return VentasDolares
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
     * Set montoVentaBolivares
     *
     * @param string $montoVentaBolivares
     * @return VentasDolares
     */
    public function setMontoVentaBolivares($montoVentaBolivares)
    {
        $this->montoVentaBolivares = $montoVentaBolivares;

        return $this;
    }

    /**
     * Get montoVentaBolivares
     *
     * @return string 
     */
    public function getMontoVentaBolivares()
    {
        return $this->montoVentaBolivares;
    }

    /**
     * Set dolaresEntregados
     *
     * @param boolean $dolaresEntregados
     * @return VentasDolares
     */
    public function setDolaresEntregados($dolaresEntregados)
    {
        $this->dolaresEntregados = $dolaresEntregados;

        return $this;
    }

    /**
     * Get dolaresEntregados
     *
     * @return boolean 
     */
    public function getDolaresEntregados()
    {
        return $this->dolaresEntregados;
    }

    /**
     * Set comprador
     *
     * @param \lOro\EntityBundle\Entity\Proveedores $comprador
     * @return VentasDolares
     */
    public function setComprador(\lOro\EntityBundle\Entity\Proveedores $comprador = null)
    {
        $this->comprador = $comprador;

        return $this;
    }

    /**
     * Get comprador
     *
     * @return \lOro\EntityBundle\Entity\Proveedores 
     */
    public function getComprador()
    {
        return $this->comprador;
    }

    /**
     * Set balance
     *
     * @param \lOro\EntityBundle\Entity\Balances $balance
     * @return VentasDolares
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
     * Set conciliadoEnCaja
     *
     * @param boolean $conciliadoEnCaja
     * @return VentasDolares
     */
    public function setConciliadoEnCaja($conciliadoEnCaja)
    {
        $this->conciliadoEnCaja = $conciliadoEnCaja;

        return $this;
    }

    /**
     * Get conciliadoEnCaja
     *
     * @return boolean 
     */
    public function getConciliadoEnCaja()
    {
        return $this->conciliadoEnCaja;
    }

    /**
     * Set tipoMoneda
     *
     * @param \lOro\EntityBundle\Entity\TiposMoneda $tipoMoneda
     * @return VentasDolares
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

    /**
     * Set cotizacionReferencia
     *
     * @param string $cotizacionReferencia
     * @return VentasDolares
     */
    public function setCotizacionReferencia($cotizacionReferencia)
    {
        $this->cotizacionReferencia = $cotizacionReferencia;

        return $this;
    }

    /**
     * Get cotizacionReferencia
     *
     * @return string 
     */
    public function getCotizacionReferencia()
    {
        return $this->cotizacionReferencia;
    }
}
