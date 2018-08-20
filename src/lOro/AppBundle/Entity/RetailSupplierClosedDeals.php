<?php

namespace lOro\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RetailSupplierClosedDeals
 *
 * @ORM\Table(name="cierres_minoristas")
 * @ORM\Entity
 */
class RetailSupplierClosedDeals
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
     * @ORM\Column(name="fe_cierre", type="datetime")
     */
    private $feCierre;

    /**
     * @var string
     *
     * @ORM\Column(name="peso_bruto_cierre", type="decimal", precision=14, scale=2, nullable=false)
     */
    private $pesoBrutoCierre;

    /**
     * @var string
     *
     * @ORM\Column(name="ley", type="decimal", precision=14, scale=2, nullable=false)
     */
    private $ley;

    /**
     * @var string
     *
     * @ORM\Column(name="peso_puro_cierre", type="decimal", precision=14, scale=2, nullable=false)
     */
    private $pesoPuroCierre;

    /**
     * @var string
     *
     * @ORM\Column(name="monto_bs_por_gramo", type="decimal", precision=14, scale=2, nullable=false)
     */
    private $montoBsPorGramo;

    /**
     * @var string
     *
     * @ORM\Column(name="dolar_referencia_dia", type="decimal", precision=14, scale=2, nullable=false)
     */
    private $dolarReferenciaDia;

    /**
     * @var string
     *
     * @ORM\Column(name="valor_onza_referencia", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $valorOnzaReferencia;

    /**
     * @var string
     *
     * @ORM\Column(name="total_monto_bs", type="decimal", precision=14, scale=2, nullable=false)
     */
    private $totalMontoBs;

    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Proveedores", inversedBy="entregasMinoristas")
     * @ORM\JoinColumn(name="minorista_id", referencedColumnName="id") 
     */
    private $minorista;

    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\TiposMoneda")
     * @ORM\JoinColumn(name="tipo_moneda_cierre_id", referencedColumnName="id", nullable=true) 
     */
    private $tipoMonedaCierre;    


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
     * Set feCierre
     *
     * @param \DateTime $feCierre
     *
     * @return RetailSupplierClosedDeals
     */
    public function setFeCierre($feCierre)
    {
        $this->feCierre = $feCierre;

        return $this;
    }

    /**
     * Get feCierre
     *
     * @return \DateTime
     */
    public function getFeCierre()
    {
        return $this->feCierre;
    }

    /**
     * Set pesoBrutoCierre
     *
     * @param string $pesoBrutoCierre
     *
     * @return RetailSupplierClosedDeals
     */
    public function setPesoBrutoCierre($pesoBrutoCierre)
    {
        $this->pesoBrutoCierre = $pesoBrutoCierre;

        return $this;
    }

    /**
     * Get pesoBrutoCierre
     *
     * @return string
     */
    public function getPesoBrutoCierre()
    {
        return $this->pesoBrutoCierre;
    }

    /**
     * Set ley
     *
     * @param string $ley
     *
     * @return RetailSupplierClosedDeals
     */
    public function setLey($ley)
    {
        $this->ley = $ley;

        return $this;
    }

    /**
     * Get ley
     *
     * @return string
     */
    public function getLey()
    {
        return $this->ley;
    }

    /**
     * Set pesoPuroCierre
     *
     * @param string $pesoPuroCierre
     *
     * @return RetailSupplierClosedDeals
     */
    public function setPesoPuroCierre($pesoPuroCierre)
    {
        $this->pesoPuroCierre = $pesoPuroCierre;

        return $this;
    }

    /**
     * Get pesoPuroCierre
     *
     * @return string
     */
    public function getPesoPuroCierre()
    {
        return $this->pesoPuroCierre;
    }

    /**
     * Set montoBsPorGramo
     *
     * @param string $montoBsPorGramo
     *
     * @return RetailSupplierClosedDeals
     */
    public function setMontoBsPorGramo($montoBsPorGramo)
    {
        $this->montoBsPorGramo = $montoBsPorGramo;

        return $this;
    }

    /**
     * Get montoBsPorGramo
     *
     * @return string
     */
    public function getMontoBsPorGramo()
    {
        return $this->montoBsPorGramo;
    }

    /**
     * Set dolarReferenciaDia
     *
     * @param string $dolarReferenciaDia
     *
     * @return RetailSupplierClosedDeals
     */
    public function setDolarReferenciaDia($dolarReferenciaDia)
    {
        $this->dolarReferenciaDia = $dolarReferenciaDia;

        return $this;
    }

    /**
     * Get dolarReferenciaDia
     *
     * @return string
     */
    public function getDolarReferenciaDia()
    {
        return $this->dolarReferenciaDia;
    }

    /**
     * Set totalMontoBs
     *
     * @param string $totalMontoBs
     *
     * @return RetailSupplierClosedDeals
     */
    public function setTotalMontoBs($totalMontoBs)
    {
        $this->totalMontoBs = $totalMontoBs;

        return $this;
    }

    /**
     * Get totalMontoBs
     *
     * @return string
     */
    public function getTotalMontoBs()
    {
        return $this->totalMontoBs;
    }

    /**
     * Set minorista
     *
     * @param \lOro\EntityBundle\Entity\Proveedores $minorista
     * @return RetailSupplierClosedDeals
     */
    public function setMinorista(\lOro\EntityBundle\Entity\Proveedores $minorista = null)
    {
        $this->minorista = $minorista;

        return $this;
    }

    /**
     * Get minorista
     *
     * @return \lOro\EntityBundle\Entity\Proveedores 
     */
    public function getMinorista()
    {
        return $this->minorista;
    }

    /**
     * Set valorOnzaReferencia
     *
     * @param string $valorOnzaReferencia
     * @return RetailSupplierClosedDeals
     */
    public function setValorOnzaReferencia($valorOnzaReferencia)
    {
        $this->valorOnzaReferencia = $valorOnzaReferencia;

        return $this;
    }

    /**
     * Get valorOnzaReferencia
     *
     * @return string 
     */
    public function getValorOnzaReferencia()
    {
        return $this->valorOnzaReferencia;
    }    

    /**
     * Set tipoMonedaCierre
     *
     * @param \lOro\EntityBundle\Entity\TiposMoneda $tipoMonedaCierre
     *
     * @return RetailSupplierClosedDeals
     */
    public function setTipoMonedaCierre(\lOro\EntityBundle\Entity\TiposMoneda $tipoMonedaCierre = null)
    {
        $this->tipoMonedaCierre = $tipoMonedaCierre;

        return $this;
    }

    /**
     * Get tipoMonedaCierre
     *
     * @return \lOro\EntityBundle\Entity\TiposMoneda
     */
    public function getTipoMonedaCierre()
    {
        return $this->tipoMonedaCierre;
    }
}
