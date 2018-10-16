<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PagosVarios
 *
 * @ORM\Table(name="pagos_varios")
 * @ORM\Entity(repositoryClass="lOro\EntityBundle\Entity\Repository\PagosVariosRepository")
 */
class PagosVarios
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
     * @ORM\Column(name="fe_pago", type="date")
     */
    private $fePago;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion_pago", type="string", length=700)
     */
    private $descripcionPago;

    /**
     * @var string
     *
     * @ORM\Column(name="nro_referencia", type="string",length=20,nullable=true)
     */
    private $nroReferencia;
    
    /**
     * @var string
     *
     * @ORM\Column(name="monto_pago", type="decimal", precision=10, scale=2)
     */
    private $montoPago;

    /**
     * @ORM\ManyToOne(targetEntity="TipoTransaccion", inversedBy="pagosVarios")
     * @ORM\JoinColumn(name="tipo_transaccion_id", referencedColumnName="id") 
     */
    private $tipoTransaccion; 
    
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\EmpresasProveedores", inversedBy="pagosVariosRealizadosCasa")
     * @ORM\JoinColumn(name="empresa_casa_id", referencedColumnName="id",nullable=true) 
     */
    private $empresaCasa;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fe_registro", type="datetime",nullable=true)
     */
    private $feRegistro;
    
    /**
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumn(name="registrador_id", referencedColumnName="id",nullable=true) 
     */
    private $usuarioRegistrador; 
    
    /**
     * @var string
     *
     * @ORM\Column(name="tipo_pago", type="string",length=25,nullable=true)
     */
    private $tipoPago;
    
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\TiposPagosVarios")
     * @ORM\JoinColumn(name="tipo_pago_vario_id", referencedColumnName="id",nullable=true) 
     */
    private $tipoPagoVario;   
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="conciliado_en_caja", type="boolean")
     */
    private $conciliadoEnCaja = FALSE;    
    
 
    /**
     * Set conciliadoEnCaja
     *
     * @param boolean $conciliadoEnCaja
     * @return PagosVarios
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fePago
     *
     * @param \DateTime $fePago
     * @return PagosVarios
     */
    public function setFePago($fePago)
    {
        $this->fePago = $fePago;

        return $this;
    }

    /**
     * Get fePago
     *
     * @return \DateTime 
     */
    public function getFePago()
    {
        return $this->fePago;
    }

    /**
     * Set descripcionPago
     *
     * @param string $descripcionPago
     * @return PagosVarios
     */
    public function setDescripcionPago($descripcionPago)
    {
        $this->descripcionPago = $descripcionPago;

        return $this;
    }

    /**
     * Get descripcionPago
     *
     * @return string 
     */
    public function getDescripcionPago()
    {
        return $this->descripcionPago;
    }

    /**
     * Set montoPago
     *
     * @param string $montoPago
     * @return PagosVarios
     */
    public function setMontoPago($montoPago)
    {
        $this->montoPago = $montoPago;

        return $this;
    }

    /**
     * Get montoPago
     *
     * @return string 
     */
    public function getMontoPago()
    {
        return $this->montoPago;
    }

    /**
     * Set tipoTransaccion
     *
     * @param \lOro\EntityBundle\Entity\TipoTransaccion $tipoTransaccion
     * @return PagosVarios
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
     * Set nroReferencia
     *
     * @param string $nroReferencia
     * @return PagosVarios
     */
    public function setNroReferencia($nroReferencia)
    {
        $this->nroReferencia = $nroReferencia;

        return $this;
    }

    /**
     * Get nroReferencia
     *
     * @return string 
     */
    public function getNroReferencia()
    {
        return $this->nroReferencia;
    }

    /**
     * Set empresaCasa
     *
     * @param \lOro\EntityBundle\Entity\EmpresasProveedores $empresaCasa
     * @return PagosVarios
     */
    public function setEmpresaCasa(\lOro\EntityBundle\Entity\EmpresasProveedores $empresaCasa = null)
    {
        $this->empresaCasa = $empresaCasa;

        return $this;
    }

    /**
     * Get empresaCasa
     *
     * @return \lOro\EntityBundle\Entity\EmpresasProveedores 
     */
    public function getEmpresaCasa()
    {
        return $this->empresaCasa;
    }

    /**
     * Set feRegistro
     *
     * @param \DateTime $feRegistro
     * @return PagosVarios
     */
    public function setFeRegistro($feRegistro)
    {
        $this->feRegistro = $feRegistro;

        return $this;
    }

    /**
     * Get feRegistro
     *
     * @return \DateTime 
     */
    public function getFeRegistro()
    {
        return $this->feRegistro;
    }



    /**
     * Set usuarioRegistrador
     *
     * @param \lOro\EntityBundle\Entity\Users $usuarioRegistrador
     * @return PagosVarios
     */
    public function setUsuarioRegistrador(\lOro\EntityBundle\Entity\Users $usuarioRegistrador = null)
    {
        $this->usuarioRegistrador = $usuarioRegistrador;

        return $this;
    }

    /**
     * Get usuarioRegistrador
     *
     * @return \lOro\EntityBundle\Entity\Users 
     */
    public function getUsuarioRegistrador()
    {
        return $this->usuarioRegistrador;
    }

    /**
     * Set tipoPago
     *
     * @param string $tipoPago
     * @return PagosVarios
     */
    public function setTipoPago($tipoPago)
    {
        $this->tipoPago = $tipoPago;

        return $this;
    }

    /**
     * Get tipoPago
     *
     * @return string 
     */
    public function getTipoPago()
    {
        return $this->tipoPago;
    }

    /**
     * Set tipoPagoVario
     *
     * @param \lOro\EntityBundle\Entity\TiposPagosVarios $tipoPagoVario
     * @return PagosVarios
     */
    public function setTipoPagoVario(\lOro\EntityBundle\Entity\TiposPagosVarios $tipoPagoVario = null)
    {
        $this->tipoPagoVario = $tipoPagoVario;

        return $this;
    }

    /**
     * Get tipoPagoVario
     *
     * @return \lOro\EntityBundle\Entity\TiposPagosVarios 
     */
    public function getTipoPagoVario()
    {
        return $this->tipoPagoVario;
    }

   
}
