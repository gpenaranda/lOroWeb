<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Balances
 *
 * @ORM\Table(name="balances")
 * @ORM\Entity(repositoryClass="lOro\EntityBundle\Entity\Repository\BalancesRepository")
 */
class Balances
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
     * @var string
     *
     * @ORM\Column(name="monto_bs", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montoBs;

    /**
     * @var string
     *
     * @ORM\Column(name="promedio_dolares", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $promedioDolares;

    /**
     * @var string
     *
     * @ORM\Column(name="monto_dolares", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montoDolares;

    /**
     * @var string
     *
     * @ORM\Column(name="total_oro_para_venta", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $totalOroParaVenta;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fe_inicio_balance", type="date", nullable=false)
     */
    private $feInicioBalance;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fe_final_balance", type="date", nullable=true)
     */
    private $feFinalBalance;
    /**
     * @var string
     *
     * @ORM\Column(name="estatus", type="string", length=1, nullable=false)
     */
    private $estatus;

    /**
     * @var string
     *
     * @ORM\Column(name="total_oro_de_entrega", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $totalOroDeEntrega;    

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
     * Set montoBs
     *
     * @param string $montoBs
     * @return Balances
     */
    public function setMontoBs($montoBs)
    {
        $this->montoBs = $montoBs;

        return $this;
    }

    /**
     * Get montoBs
     *
     * @return string 
     */
    public function getMontoBs()
    {
        return $this->montoBs;
    }

    /**
     * Set promedioDolares
     *
     * @param string $promedioDolares
     * @return Balances
     */
    public function setPromedioDolares($promedioDolares)
    {
        $this->promedioDolares = $promedioDolares;

        return $this;
    }

    /**
     * Get promedioDolares
     *
     * @return string 
     */
    public function getPromedioDolares()
    {
        return $this->promedioDolares;
    }

    /**
     * Set montoDolares
     *
     * @param string $montoDolares
     * @return Balances
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
     * Set totalOroParaVenta
     *
     * @param string $totalOroParaVenta
     * @return Balances
     */
    public function setTotalOroParaVenta($totalOroParaVenta)
    {
        $this->totalOroParaVenta = $totalOroParaVenta;

        return $this;
    }

    /**
     * Get totalOroParaVenta
     *
     * @return string 
     */
    public function getTotalOroParaVenta()
    {
        return $this->totalOroParaVenta;
    }

    /**
     * Set feInicioBalance
     *
     * @param \DateTime $feInicioBalance
     * @return Balances
     */
    public function setFeInicioBalance($feInicioBalance)
    {
        $this->feInicioBalance = $feInicioBalance;

        return $this;
    }

    /**
     * Get feInicioBalance
     *
     * @return \DateTime 
     */
    public function getFeInicioBalance()
    {
        return $this->feInicioBalance;
    }

    /**
     * Set feFinalBalance
     *
     * @param \DateTime $feFinalBalance
     * @return Balances
     */
    public function setFeFinalBalance($feFinalBalance)
    {
        $this->feFinalBalance = $feFinalBalance;

        return $this;
    }

    /**
     * Get feFinalBalance
     *
     * @return \DateTime 
     */
    public function getFeFinalBalance()
    {
        return $this->feFinalBalance;
    }

    /**
     * Set estatus
     *
     * @param string $estatus
     * @return Balances
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
     * Set totalOroDeEntrega
     *
     * @param string $totalOroDeEntrega
     * @return Balances
     */
    public function setTotalOroDeEntrega($totalOroDeEntrega)
    {
        $this->totalOroDeEntrega = $totalOroDeEntrega;

        return $this;
    }

    /**
     * Get totalOroDeEntrega
     *
     * @return string 
     */
    public function getTotalOroDeEntrega()
    {
        return $this->totalOroDeEntrega;
    }

}
