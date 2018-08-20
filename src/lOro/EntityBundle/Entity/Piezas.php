<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Piezas
 *
 * @ORM\Table(name="piezas")
 * @ORM\Entity(repositoryClass="lOro\EntityBundle\Entity\Repository\PiezasRepository")
 */
class Piezas
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
     * @var integer
     *
     * @ORM\Column(name="cod_pieza", type="integer")
     */
    private $codPieza;

    /**
     * @var string
     *
     * @ORM\Column(name="peso_bruto_pieza", type="decimal", precision=10, scale=2)
     */
    private $pesoBrutoPieza;

    /**
     * @var string
     *
     * @ORM\Column(name="ley_pieza", type="decimal", precision=10, scale=4,nullable=true)
     */
    private $leyPieza;

    /**
     * @var string
     *
     * @ORM\Column(name="peso_puro_pieza", type="decimal", precision=10, scale=2,nullable=true)
     */
    private $pesoPuroPieza;
    
    /**
     * @var string
     *
     * @ORM\Column(name="gramos_restantes_relacion", type="decimal", precision=10, scale=2,nullable=true)
     */
    private $gramosRestantesRelacion;
    
    /**
     * @var string
     *
     * @ORM\Column(name="gramos_restantes_relacion_proveedor", type="decimal", precision=10, scale=2,nullable=true)
     */
    private $gramosRestantesRelacionProveedor;    
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="pieza_asociada", type="boolean")
     */
    private $piezaAsociada = FALSE;
    
    /**
     * @ORM\ManyToOne(targetEntity="Entregas", inversedBy="piezasEntregadas")
     * @ORM\JoinColumn(name="entrega_id", referencedColumnName="id",nullable=true)
     */
    private $entrega;
 
   /**
     * @ORM\OneToMany(targetEntity="\lOro\AppBundle\Entity\EntregasMinoristas", mappedBy="pieza")
     */
    private $entregasMinoristas;   
    
   /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\CierresProveedoresPiezas", mappedBy="pieza")
     */
    private $cierresProveedoresPiezas;       
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="anio", type="string", length=4,nullable=true)
     */
    private $anio;

     /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\TiposMoneda")
     * @ORM\JoinColumn(name="tipo_moneda_id", referencedColumnName="id", nullable=true) 
     */
    private $tipoMonedaPieza;      
    
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
     * Set pesoBrutoPieza
     *
     * @param string $pesoBrutoPieza
     * @return Piezas
     */
    public function getSelectPieza()
    {
      return 'Cod: '.$this->codPieza.' - '.$this->pesoBrutoPieza.' Grs.';
    }

    /**
     * Set pesoBrutoPieza
     *
     * @param string $pesoBrutoPieza
     * @return Piezas
     */
    public function setPesoBrutoPieza($pesoBrutoPieza)
    {
        $this->pesoBrutoPieza = $pesoBrutoPieza;

        return $this;
    }

    /**
     * Get pesoBrutoPieza
     *
     * @return string 
     */
    public function getPesoBrutoPieza()
    {
        return $this->pesoBrutoPieza;
    }

    /**
     * Set leyPieza
     *
     * @param string $leyPieza
     * @return Piezas
     */
    public function setLeyPieza($leyPieza)
    {
        $this->leyPieza = $leyPieza;

        return $this;
    }

    /**
     * Get leyPieza
     *
     * @return string 
     */
    public function getLeyPieza()
    {
        return $this->leyPieza;
    }

    /**
     * Set pesoPuroPieza
     *
     * @param string $pesoPuroPieza
     * @return Piezas
     */
    public function setPesoPuroPieza($pesoPuroPieza)
    {
        $this->pesoPuroPieza = $pesoPuroPieza;

        return $this;
    }

    /**
     * Get pesoPuroPieza
     *
     * @return string 
     */
    public function getPesoPuroPieza()
    {
        return $this->pesoPuroPieza;
    }

    /**
     * Set entrega
     *
     * @param \lOro\EntityBundle\Entity\Entregas $entrega
     * @return Piezas
     */
    public function setEntrega(\lOro\EntityBundle\Entity\Entregas $entrega = null)
    {
        $this->entrega = $entrega;

        return $this;
    }

    /**
     * Get entrega
     *
     * @return \lOro\EntityBundle\Entity\Entregas 
     */
    public function getEntrega()
    {
        return $this->entrega;
    }

    /**
     * Set anio
     *
     * @param string $anio
     * @return Piezas
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * Get anio
     *
     * @return string 
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Set codPieza
     *
     * @param integer $codPieza
     * @return Piezas
     */
    public function setCodPieza($codPieza)
    {
        $this->codPieza = $codPieza;

        return $this;
    }

    /**
     * Get codPieza
     *
     * @return integer 
     */
    public function getCodPieza()
    {
        return $this->codPieza;
    }

    /**
     * Set gramosRestantesRelacion
     *
     * @param string $gramosRestantesRelacion
     * @return Piezas
     */
    public function setGramosRestantesRelacion($gramosRestantesRelacion)
    {
        $this->gramosRestantesRelacion = $gramosRestantesRelacion;

        return $this;
    }

    /**
     * Get gramosRestantesRelacion
     *
     * @return string 
     */
    public function getGramosRestantesRelacion()
    {
        return $this->gramosRestantesRelacion;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->entregasMinoristas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add entregasMinoristas
     *
     * @param \lOro\AppBundle\Entity\EntregasMinoristas $entregasMinoristas
     * @return Piezas
     */
    public function addEntregasMinorista(\lOro\AppBundle\Entity\EntregasMinoristas $entregasMinoristas)
    {
        $this->entregasMinoristas[] = $entregasMinoristas;

        return $this;
    }

    /**
     * Remove entregasMinoristas
     *
     * @param \lOro\AppBundle\Entity\EntregasMinoristas $entregasMinoristas
     */
    public function removeEntregasMinorista(\lOro\AppBundle\Entity\EntregasMinoristas $entregasMinoristas)
    {
        $this->entregasMinoristas->removeElement($entregasMinoristas);
    }

    /**
     * Get entregasMinoristas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEntregasMinoristas()
    {
        return $this->entregasMinoristas;
    }

    /**
     * Set piezaAsociada
     *
     * @param boolean $piezaAsociada
     * @return Piezas
     */
    public function setPiezaAsociada($piezaAsociada)
    {
        $this->piezaAsociada = $piezaAsociada;

        return $this;
    }

    /**
     * Get piezaAsociada
     *
     * @return boolean 
     */
    public function getPiezaAsociada()
    {
        return $this->piezaAsociada;
    }

    /**
     * Add cierresProveedoresPiezas
     *
     * @param \lOro\EntityBundle\Entity\CierresProveedoresPiezas $cierresProveedoresPiezas
     * @return Piezas
     */
    public function addCierresProveedoresPieza(\lOro\EntityBundle\Entity\CierresProveedoresPiezas $cierresProveedoresPiezas)
    {
        $this->cierresProveedoresPiezas[] = $cierresProveedoresPiezas;

        return $this;
    }

    /**
     * Remove cierresProveedoresPiezas
     *
     * @param \lOro\EntityBundle\Entity\CierresProveedoresPiezas $cierresProveedoresPiezas
     */
    public function removeCierresProveedoresPieza(\lOro\EntityBundle\Entity\CierresProveedoresPiezas $cierresProveedoresPiezas)
    {
        $this->cierresProveedoresPiezas->removeElement($cierresProveedoresPiezas);
    }

    /**
     * Get cierresProveedoresPiezas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCierresProveedoresPiezas()
    {
        return $this->cierresProveedoresPiezas;
    }

    /**
     * Set gramosRestantesRelacionProveedor
     *
     * @param string $gramosRestantesRelacionProveedor
     * @return Piezas
     */
    public function setGramosRestantesRelacionProveedor($gramosRestantesRelacionProveedor)
    {
        $this->gramosRestantesRelacionProveedor = $gramosRestantesRelacionProveedor;

        return $this;
    }

    /**
     * Get gramosRestantesRelacionProveedor
     *
     * @return string 
     */
    public function getGramosRestantesRelacionProveedor()
    {
        return $this->gramosRestantesRelacionProveedor;
    }

    /**
     * Set tipoMonedaPieza
     *
     * @param \lOro\EntityBundle\Entity\TiposMoneda $tipoMonedaPieza
     * @return Piezas
     */
    public function setTipoMonedaPieza(\lOro\EntityBundle\Entity\TiposMoneda $tipoMonedaPieza = null)
    {
        $this->tipoMonedaPieza = $tipoMonedaPieza;

        return $this;
    }

    /**
     * Get tipoMonedaPieza
     *
     * @return \lOro\EntityBundle\Entity\TiposMoneda 
     */
    public function getTipoMonedaPieza()
    {
        return $this->tipoMonedaPieza;
    }    
}
