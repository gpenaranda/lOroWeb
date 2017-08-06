<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PagosMinoristas
 *
 * @ORM\Table(name="pagos_minoristas")
 * @ORM\Entity(repositoryClass="lOro\EntityBundle\Entity\Repository\PagosMinoristasRepository")
 */
class PagosMinoristas
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
     * @ORM\Column(name="monto_pagado", type="decimal", precision=10, scale=2)
     */
    private $montoPagado;
    
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\EmpresasProveedores", inversedBy="pagosRealizadosCasaMinoristas")
     * @ORM\JoinColumn(name="empresa_casa_id", referencedColumnName="id",nullable=true) 
     */
    private $empresaCasa;
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="nro_referencia", type="string",length=25,nullable=true)
     */
    private $nroReferencia;

    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\EmpresasProveedores", inversedBy="pagosRealizadosMinoristas")
     * @ORM\JoinColumn(name="empresa_proveedor_id", referencedColumnName="id") 
     */
    private $empresaPago;    
        
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\TipoTransaccion", inversedBy="pagosMinoristas")
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
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Bancos")
     * @ORM\JoinColumn(name="banco_id", referencedColumnName="id") 
     */
    private $banco;    
    
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="conciliado_en_caja", type="boolean")
     */
    private $conciliadoEnCaja = FALSE;     

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
     * @return PagosProveedores
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
     * Set montoPagado
     *
     * @param string $montoPagado
     * @return PagosProveedores
     */
    public function setMontoPagado($montoPagado)
    {
        $this->montoPagado = $montoPagado;

        return $this;
    }

    /**
     * Get montoPagado
     *
     * @return string 
     */
    public function getMontoPagado()
    {
        return $this->montoPagado;
    }

    /**
     * Set empresaPago
     *
     * @param \lOro\EntityBundle\Entity\EmpresasProveedores $empresaPago
     * @return PagosProveedores
     */
    public function setEmpresaPago(\lOro\EntityBundle\Entity\EmpresasProveedores $empresaPago = null)
    {
        $this->empresaPago = $empresaPago;

        return $this;
    }

    /**
     * Get empresaPago
     *
     * @return \lOro\EntityBundle\Entity\EmpresasProveedores 
     */
    public function getEmpresaPago()
    {
        return $this->empresaPago;
    }

    /**
     * Set balance
     *
     * @param \lOro\EntityBundle\Entity\Balances $balance
     * @return PagosProveedores
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
     * Set tipoTransaccion
     *
     * @param \lOro\EntityBundle\Entity\TipoTransaccion $tipoTransaccion
     * @return PagosProveedores
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
     * Set empresaCasa
     *
     * @param \lOro\EntityBundle\Entity\EmpresasProveedores $empresaCasa
     * @return PagosProveedores
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
     * @return PagosProveedores
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
     * @return PagosProveedores
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
     * @return PagosProveedores
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
     * Set banco
     *
     * @param \lOro\EntityBundle\Entity\Bancos $banco
     * @return PagosProveedores
     */
    public function setBanco(\lOro\EntityBundle\Entity\Bancos $banco = null)
    {
        $this->banco = $banco;

        return $this;
    }

    /**
     * Get banco
     *
     * @return \lOro\EntityBundle\Entity\Bancos 
     */
    public function getBanco()
    {
        return $this->banco;
    }

    /**
     * Set nroReferencia
     *
     * @param string $nroReferencia
     * @return PagosProveedores
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
     * Set conciliadoEnCaja
     *
     * @param boolean $conciliadoEnCaja
     * @return PagosProveedores
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
}
