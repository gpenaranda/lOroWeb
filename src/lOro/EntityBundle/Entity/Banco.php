<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Banco
 *
 * @ORM\Table(name="banco")
 * @ORM\Entity(repositoryClass="lOro\EntityBundle\Entity\Repository\BancoRepository")
 */
class Banco
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
     * @var string
     *
     * @ORM\Column(name="monto_dolares", type="decimal",precision=10,scale=2)
     */
    private $montoDolares;

    /**
     * @var string
     *
     * @ORM\Column(name="monto_bolivares", type="decimal",precision=10,scale=2, nullable=true)
     */
    private $montoBolivares;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_movimiento", type="string", length=25)
     */
    private $tipoMovimiento;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_movimiento", type="integer")
     */
    private $idMovimiento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fe_movimiento_banco", type="date")
     */
    private $feMovimientoBanco;

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
     * Set montoDolares
     *
     * @param string $montoDolares
     * @return Banco
     */
    public function setMontoDolares($montoDolares)
    {
        $this->montoDolares = $montoDolares;

        return $this;
    }

    /**
     * Get montoDolares
     *
     * @return string 
     */
    public function getMontoDolares()
    {
        return $this->montoDolares;
    }

    /**
     * Set montoBolivares
     *
     * @param string $montoBolivares
     * @return Banco
     */
    public function setMontoBolivares($montoBolivares)
    {
        $this->montoBolivares = $montoBolivares;

        return $this;
    }

    /**
     * Get montoBolivares
     *
     * @return string 
     */
    public function getMontoBolivares()
    {
        return $this->montoBolivares;
    }

    /**
     * Set tipoMovimiento
     *
     * @param string $tipoMovimiento
     * @return Banco
     */
    public function setTipoMovimiento($tipoMovimiento)
    {
        $this->tipoMovimiento = $tipoMovimiento;

        return $this;
    }

    /**
     * Get tipoMovimiento
     *
     * @return string 
     */
    public function getTipoMovimiento()
    {
        return $this->tipoMovimiento;
    }

    /**
     * Set idMovimiento
     *
     * @param integer $idMovimiento
     * @return Banco
     */
    public function setIdMovimiento($idMovimiento)
    {
        $this->idMovimiento = $idMovimiento;

        return $this;
    }

    /**
     * Get idMovimiento
     *
     * @return integer 
     */
    public function getIdMovimiento()
    {
        return $this->idMovimiento;
    }

    /**
     * Set feMovimientoBanco
     *
     * @param \DateTime $feMovimientoBanco
     * @return Banco
     */
    public function setFeMovimientoBanco($feMovimientoBanco)
    {
        $this->feMovimientoBanco = $feMovimientoBanco;

        return $this;
    }

    /**
     * Get feMovimientoBanco
     *
     * @return \DateTime 
     */
    public function getFeMovimientoBanco()
    {
        return $this->feMovimientoBanco;
    }
}
