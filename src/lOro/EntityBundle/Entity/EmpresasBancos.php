<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmpresasBancos
 *
 * @ORM\Table(name="empresas_bancos")
 * @ORM\Entity
 */
class EmpresasBancos
{
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\EmpresasProveedores", inversedBy="cuentasPorEmpresa")
     * @ORM\JoinColumn(name="empresa_id", referencedColumnName="id", nullable=true) 
     * @ORM\Id
     */
    private $empresa;

    
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Bancos")
     * @ORM\JoinColumn(name="banco_id", referencedColumnName="id", nullable=true) 
     * @ORM\Id
     */
    private $banco;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nro_cuenta", type="string", length=20)
     * @ORM\Id
     */
    private $nroCuenta;

    /**
     * Set nroCuenta
     *
     * @param string $nroCuenta
     *
     * @return EmpresasBancos
     */
    public function setNroCuenta($nroCuenta)
    {
        $this->nroCuenta = $nroCuenta;

        return $this;
    }

    /**
     * Get nroCuenta
     *
     * @return string
     */
    public function getNroCuenta()
    {
        return $this->nroCuenta;
    }

    /**
     * Set empresa
     *
     * @param \lOro\EntityBundle\Entity\EmpresasProveedores $empresa
     *
     * @return EmpresasBancos
     */
    public function setEmpresa(\lOro\EntityBundle\Entity\EmpresasProveedores $empresa)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return \lOro\EntityBundle\Entity\EmpresasProveedores
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set banco
     *
     * @param \lOro\EntityBundle\Entity\Bancos $banco
     *
     * @return EmpresasBancos
     */
    public function setBanco(\lOro\EntityBundle\Entity\Bancos $banco)
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
}
