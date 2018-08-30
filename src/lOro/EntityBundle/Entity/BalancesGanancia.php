<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Balances
 *
 * @ORM\Table(name="balances_ganancia")
 * @ORM\Entity(repositoryClass="lOro\EntityBundle\Entity\Repository\BalancesGananciaRepository")
 */
class BalancesGanancia
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
     * @ORM\Column(name="fe_balance", type="date", nullable=true)
     */
    private $feBalance;
    
    /**
     * @var string
     *
     * @ORM\Column(name="valor_onza", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $valorOnza;
    
    /**
     * @var string
     *
     * @ORM\Column(name="dolar_referencia", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $dolarReferencia;

    /**
     * @var array
     *
     * @ORM\Column(name="material_por_entregar_hc", type="array",nullable=true)
     */
    private $materialPorEntregarHc;    
    
    /**
     * @var array
     *
     * @ORM\Column(name="material_no_cerrado", type="array",nullable=true)
     */
    private $materialNoCerrado;   
    
    /**
     * @var array
     *
     * @ORM\Column(name="debo_proveedores", type="array",nullable=true)
     */
    private $deboProveedores;      

    /**
     * @var string
     *
     * @ORM\Column(name="bolivares_caja", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $bolivaresCaja;    
 
    /**
     * @var string
     *
     * @ORM\Column(name="transferencias_pendientes", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $transferenciasPendientes;
    
    /**
     * @var string
     *
     * @ORM\Column(name="credito_hc", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $creditoHc;     

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
     * Set feBalance
     *
     * @param \DateTime $feBalance
     * @return BalancesGanancia
     */
    public function setFeBalance($feBalance)
    {
        $this->feBalance = $feBalance;

        return $this;
    }

    /**
     * Get feBalance
     *
     * @return \DateTime 
     */
    public function getFeBalance()
    {
        return $this->feBalance;
    }

    /**
     * Set valorOnza
     *
     * @param string $valorOnza
     * @return BalancesGanancia
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
     * Set dolarReferencia
     *
     * @param string $dolarReferencia
     * @return BalancesGanancia
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
     * Set materialPorEntregarHc
     *
     * @param array $materialPorEntregarHc
     * @return BalancesGanancia
     */
    public function setMaterialPorEntregarHc($materialPorEntregarHc)
    {
        $this->materialPorEntregarHc = $materialPorEntregarHc;

        return $this;
    }

    /**
     * Get materialPorEntregarHc
     *
     * @return array 
     */
    public function getMaterialPorEntregarHc()
    {
        return $this->materialPorEntregarHc;
    }

    /**
     * Set materialNoCerrado
     *
     * @param array $materialNoCerrado
     * @return BalancesGanancia
     */
    public function setMaterialNoCerrado($materialNoCerrado)
    {
        $this->materialNoCerrado = $materialNoCerrado;

        return $this;
    }

    /**
     * Get materialNoCerrado
     *
     * @return array 
     */
    public function getMaterialNoCerrado()
    {
        return $this->materialNoCerrado;
    }

    /**
     * Set deboProveedores
     *
     * @param array $deboProveedores
     * @return BalancesGanancia
     */
    public function setDeboProveedores($deboProveedores)
    {
        $this->deboProveedores = $deboProveedores;

        return $this;
    }

    /**
     * Get deboProveedores
     *
     * @return array 
     */
    public function getDeboProveedores()
    {
        return $this->deboProveedores;
    }

    /**
     * Set bolivaresCaja
     *
     * @param string $bolivaresCaja
     * @return BalancesGanancia
     */
    public function setBolivaresCaja($bolivaresCaja)
    {
        $this->bolivaresCaja = $bolivaresCaja;

        return $this;
    }

    /**
     * Get bolivaresCaja
     *
     * @return string 
     */
    public function getBolivaresCaja()
    {
        return $this->bolivaresCaja;
    }

    /**
     * Set transferenciasPendientes
     *
     * @param string $transferenciasPendientes
     * @return BalancesGanancia
     */
    public function setTransferenciasPendientes($transferenciasPendientes)
    {
        $this->transferenciasPendientes = $transferenciasPendientes;

        return $this;
    }

    /**
     * Get transferenciasPendientes
     *
     * @return string 
     */
    public function getTransferenciasPendientes()
    {
        return $this->transferenciasPendientes;
    }

    /**
     * Set creditoHc
     *
     * @param string $creditoHc
     * @return BalancesGanancia
     */
    public function setCreditoHc($creditoHc)
    {
        $this->creditoHc = $creditoHc;

        return $this;
    }

    /**
     * Get creditoHc
     *
     * @return string 
     */
    public function getCreditoHc()
    {
        return $this->creditoHc;
    }
}
