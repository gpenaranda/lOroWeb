<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Abonos
 *
 * @ORM\Table(name="abonos")
 * @ORM\Entity(repositoryClass="lOro\EntityBundle\Entity\Repository\AbonosRepository")
 */
class Abonos
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
     * @ORM\Column(name="fe_abono", type="date")
     */
    private $feAbono;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=700)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="nro_referencia", type="string",length=20,nullable=true)
     */
    private $nroReferencia;
    
    /**
     * @var string
     *
     * @ORM\Column(name="monto", type="decimal", precision=14, scale=2)
     */
    private $monto;

    /**
     * @ORM\ManyToOne(targetEntity="TipoTransaccion")
     * @ORM\JoinColumn(name="tipo_transaccion_id", referencedColumnName="id") 
     */
    private $tipoTransaccion; 

    
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
     * Set feAbono
     *
     * @param \DateTime $feAbono
     * @return Abonos
     */
    public function setFeAbono($feAbono)
    {
        $this->feAbono = $feAbono;

        return $this;
    }

    /**
     * Get feAbono
     *
     * @return \DateTime 
     */
    public function getFeAbono()
    {
        return $this->feAbono;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Abonos
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
     * Set nroReferencia
     *
     * @param string $nroReferencia
     * @return Abonos
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
     * Set monto
     *
     * @param string $monto
     * @return Abonos
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get monto
     *
     * @return string 
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set feRegistro
     *
     * @param \DateTime $feRegistro
     * @return Abonos
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
     * Set tipoPago
     *
     * @param string $tipoPago
     * @return Abonos
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
     * Set tipoTransaccion
     *
     * @param \lOro\EntityBundle\Entity\TipoTransaccion $tipoTransaccion
     * @return Abonos
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
     * Set usuarioRegistrador
     *
     * @param \lOro\EntityBundle\Entity\Users $usuarioRegistrador
     * @return Abonos
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
}
