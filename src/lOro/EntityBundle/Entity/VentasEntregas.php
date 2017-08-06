<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Balances
 *
 * @ORM\Table(name="ventas_entregas")
 * @ORM\Entity(repositoryClass="lOro\EntityBundle\Entity\Repository\VentasEntregasRepository")
 */
class VentasEntregas
{
  
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\VentasCierres", inversedBy="entregas")
     * @ORM\JoinColumn(name="ventas_id", referencedColumnName="id")
     */
    private $ventasCierres;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Entregas", inversedBy="ventasCierresEntrega")
     * @ORM\JoinColumn(name="entregas_id", referencedColumnName="id")
     */
    private $entregas;
    
    /**
     * @var string
     *
     * @ORM\Column(name="oro_entrega", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $oroEntrega;    
    
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Balances")
     * @ORM\JoinColumn(name="balance_id", referencedColumnName="id", nullable=true)
     */
    private $balance;
 

    /**
     * Set oroEntrega
     *
     * @param string $oroEntrega
     * @return VentasEntregas
     */
    public function setOroEntrega($oroEntrega)
    {
        $this->oroEntrega = $oroEntrega;

        return $this;
    }

    /**
     * Get oroEntrega
     *
     * @return string 
     */
    public function getOroEntrega()
    {
        return $this->oroEntrega;
    }

    /**
     * Set ventasCierres
     *
     * @param \lOro\EntityBundle\Entity\VentasCierres $ventasCierres
     * @return VentasEntregas
     */
    public function setVentasCierres(\lOro\EntityBundle\Entity\VentasCierres $ventasCierres)
    {
        $this->ventasCierres = $ventasCierres;

        return $this;
    }

    /**
     * Get ventasCierres
     *
     * @return \lOro\EntityBundle\Entity\VentasCierres 
     */
    public function getVentasCierres()
    {
        return $this->ventasCierres;
    }

    /**
     * Set entregas
     *
     * @param \lOro\EntityBundle\Entity\Entregas $entregas
     * @return VentasEntregas
     */
    public function setEntregas(\lOro\EntityBundle\Entity\Entregas $entregas)
    {
        $this->entregas = $entregas;

        return $this;
    }

    /**
     * Get entregas
     *
     * @return \lOro\EntityBundle\Entity\Entregas 
     */
    public function getEntregas()
    {
        return $this->entregas;
    }

    /**
     * Set balance
     *
     * @param \lOro\EntityBundle\Entity\Balances $balance
     * @return VentasEntregas
     */
    public function setBalance(\lOro\EntityBundle\Entity\Balances $balance)
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
}
