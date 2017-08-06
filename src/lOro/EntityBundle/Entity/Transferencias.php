<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transferencias
 *
 * @ORM\Table(name="transferencias")
 * @ORM\Entity(repositoryClass="lOro\EntityBundle\Entity\Repository\TransferenciasRepository")
 */
class Transferencias
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
     * @ORM\Column(name="fe_transferencia", type="date")
     */
    private $feTransferencia;

    /**
     * @var string
     *
     * @ORM\Column(name="monto_transferencia", type="decimal",precision=10,scale=2)
     */
    private $montoTransferencia;


    

    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Proveedores")
     * @ORM\JoinColumn(name="beneficiario_id", referencedColumnName="id",nullable=true) 
     */
    private $beneficiario;

    /**
     * @var string
     *
     * @ORM\Column(name="empresa_beneficiaria", type="string",length=255,nullable=true)
     */
    private $empresa; 
    
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\TipoTransaccion", inversedBy="transferencias")
     * @ORM\JoinColumn(name="tipo_transaccion_id", referencedColumnName="id") 
     */
    private $tipoTransaccion;   
    
   
    
    /**
     * @var integer
     *
     * @ORM\Column(name="nro_referencia", type="integer",nullable=true)
     */
    private $nroReferencia;
    
    /**
     * @var string
     *
     * @ORM\Column(name="estatus", type="string",length=1, nullable=true)
     */
    private $estatus = "P";    

    /**
     * @var string
     *
     * @ORM\Column(name="observacion_devolucion", type="string",length=500, nullable=true)
     */
    private $observacionDevolucion; 
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fe_confirmacion", type="date")
     */
    private $feConfirmacion;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fe_devolucion", type="date")
     */
    private $feDevolucion;    

    

    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\TiposMoneda")
     * @ORM\JoinColumn(name="tipo_moneda_conversion_id", referencedColumnName="id", nullable=true) 
     */
    private $tipoMonedaConversion;  
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="monto_a_convertir", type="decimal",precision=10, scale=2)
     */
    private $montoAConvertir;    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="es_conversion", type="integer", length=1)
     */
    private $esConversion; 
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="cotizacion_referencia", type="decimal",precision=10,scale=4,nullable=true)
     */
    private $cotizacionReferencia;     
    
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\TiposMoneda")
     * @ORM\JoinColumn(name="tipo_moneda_transf_id", referencedColumnName="id", nullable=true) 
     */
    private $tipoMonedaTransf;      
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string",length=500, nullable=true)
     */
    private $descripcion;     

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
     * Set feTransferencia
     *
     * @param \DateTime $feTransferencia
     * @return Transferencias
     */
    public function setFeTransferencia($feTransferencia)
    {
        $this->feTransferencia = $feTransferencia;

        return $this;
    }

    /**
     * Get feTransferencia
     *
     * @return \DateTime 
     */
    public function getFeTransferencia()
    {
        return $this->feTransferencia;
    }

    /**
     * Set montoTransferencia
     *
     * @param string $montoTransferencia
     * @return Transferencias
     */
    public function setMontoTransferencia($montoTransferencia)
    {
        $this->montoTransferencia = $montoTransferencia;

        return $this;
    }

    /**
     * Get montoTransferencia
     *
     * @return string 
     */
    public function getMontoTransferencia()
    {
        return $this->montoTransferencia;
    }

    /**
     * Set empresa
     *
     * @param string $empresa
     * @return Transferencias
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
     * Set nroReferencia
     *
     * @param integer $nroReferencia
     * @return Transferencias
     */
    public function setNroReferencia($nroReferencia)
    {
        $this->nroReferencia = $nroReferencia;

        return $this;
    }

    /**
     * Get nroReferencia
     *
     * @return integer 
     */
    public function getNroReferencia()
    {
        return $this->nroReferencia;
    }

    /**
     * Set estatus
     *
     * @param string $estatus
     * @return Transferencias
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
     * Set observacionDevolucion
     *
     * @param string $observacionDevolucion
     * @return Transferencias
     */
    public function setObservacionDevolucion($observacionDevolucion)
    {
        $this->observacionDevolucion = $observacionDevolucion;

        return $this;
    }

    /**
     * Get observacionDevolucion
     *
     * @return string 
     */
    public function getObservacionDevolucion()
    {
        return $this->observacionDevolucion;
    }

    /**
     * Set feConfirmacion
     *
     * @param \DateTime $feConfirmacion
     * @return Transferencias
     */
    public function setFeConfirmacion($feConfirmacion)
    {
        $this->feConfirmacion = $feConfirmacion;

        return $this;
    }

    /**
     * Get feConfirmacion
     *
     * @return \DateTime 
     */
    public function getFeConfirmacion()
    {
        return $this->feConfirmacion;
    }

    /**
     * Set feDevolucion
     *
     * @param \DateTime $feDevolucion
     * @return Transferencias
     */
    public function setFeDevolucion($feDevolucion)
    {
        $this->feDevolucion = $feDevolucion;

        return $this;
    }

    /**
     * Get feDevolucion
     *
     * @return \DateTime 
     */
    public function getFeDevolucion()
    {
        return $this->feDevolucion;
    }

    /**
     * Set montoAConvertir
     *
     * @param string $montoAConvertir
     * @return Transferencias
     */
    public function setMontoAConvertir($montoAConvertir)
    {
        $this->montoAConvertir = $montoAConvertir;

        return $this;
    }

    /**
     * Get montoAConvertir
     *
     * @return string 
     */
    public function getMontoAConvertir()
    {
        return $this->montoAConvertir;
    }

    /**
     * Set esConversion
     *
     * @param integer $esConversion
     * @return Transferencias
     */
    public function setEsConversion($esConversion)
    {
        $this->esConversion = $esConversion;

        return $this;
    }

    /**
     * Get esConversion
     *
     * @return integer 
     */
    public function getEsConversion()
    {
        return $this->esConversion;
    }

    /**
     * Set cotizacionReferencia
     *
     * @param string $cotizacionReferencia
     * @return Transferencias
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

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Transferencias
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set beneficiario
     *
     * @param \lOro\EntityBundle\Entity\Proveedores $beneficiario
     * @return Transferencias
     */
    public function setBeneficiario(\lOro\EntityBundle\Entity\Proveedores $beneficiario = null)
    {
        $this->beneficiario = $beneficiario;

        return $this;
    }

    /**
     * Get beneficiario
     *
     * @return \lOro\EntityBundle\Entity\Proveedores 
     */
    public function getBeneficiario()
    {
        return $this->beneficiario;
    }

    /**
     * Set tipoTransaccion
     *
     * @param \lOro\EntityBundle\Entity\TipoTransaccion $tipoTransaccion
     * @return Transferencias
     */
    public function setTipoTransaccion(\lOro\EntityBundle\Entity\TipoTransaccion $tipoTransaccion = null)
    {
        $this->tipoTransaccion = $tipoTransaccion;

        return $this;
    }

    /**
     * Get tipoTransaccion
     *
     * @return \lOro\EntityBundle\Entity\TipoTransaccion 
     */
    public function getTipoTransaccion()
    {
        return $this->tipoTransaccion;
    }

    /**
     * Set tipoMonedaConversion
     *
     * @param \lOro\EntityBundle\Entity\TiposMoneda $tipoMonedaConversion
     * @return Transferencias
     */
    public function setTipoMonedaConversion(\lOro\EntityBundle\Entity\TiposMoneda $tipoMonedaConversion = null)
    {
        $this->tipoMonedaConversion = $tipoMonedaConversion;

        return $this;
    }

    /**
     * Get tipoMonedaConversion
     *
     * @return \lOro\EntityBundle\Entity\TiposMoneda 
     */
    public function getTipoMonedaConversion()
    {
        return $this->tipoMonedaConversion;
    }

    /**
     * Set tipoMonedaTransfId
     *
     * @param \lOro\EntityBundle\Entity\TiposMoneda $tipoMonedaTransfId
     * @return Transferencias
     */
    public function setTipoMonedaTransfId(\lOro\EntityBundle\Entity\TiposMoneda $tipoMonedaTransfId = null)
    {
        $this->tipoMonedaTransfId = $tipoMonedaTransfId;

        return $this;
    }

    /**
     * Get tipoMonedaTransfId
     *
     * @return \lOro\EntityBundle\Entity\TiposMoneda 
     */
    public function getTipoMonedaTransfId()
    {
        return $this->tipoMonedaTransfId;
    }

    /**
     * Set tipoMonedaTransf
     *
     * @param \lOro\EntityBundle\Entity\TiposMoneda $tipoMonedaTransf
     * @return Transferencias
     */
    public function setTipoMonedaTransf(\lOro\EntityBundle\Entity\TiposMoneda $tipoMonedaTransf = null)
    {
        $this->tipoMonedaTransf = $tipoMonedaTransf;

        return $this;
    }

    /**
     * Get tipoMonedaTransf
     *
     * @return \lOro\EntityBundle\Entity\TiposMoneda 
     */
    public function getTipoMonedaTransf()
    {
        return $this->tipoMonedaTransf;
    }
}
