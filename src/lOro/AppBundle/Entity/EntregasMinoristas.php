<?php

namespace lOro\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EntregasMinoristas
 *
 * @ORM\Table(name="entregas_minoristas")
 * @ORM\Entity(repositoryClass="lOro\AppBundle\Entity\Repository\EntregasMinoristasRepository")
 */
class EntregasMinoristas
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
     * @ORM\Column(name="fe_entrega", type="date", nullable=false)
     */
    private $feEntrega;

    /**
     * @var string
     *
     * @ORM\Column(name="peso_bruto_entrega", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $pesoBrutoEntrega;

    /**
     * @var string
     *
     * @ORM\Column(name="ley", type="decimal", precision=10, scale=4, nullable=false)
     */
    private $ley;

    /**
     * @var string
     *
     * @ORM\Column(name="peso_puro_entrega", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $pesoPuroEntrega;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Proveedores", inversedBy="entregasMinoristas")
     * @ORM\JoinColumn(name="minorista_id", referencedColumnName="id") 
     */
    private $minorista;
    

        

    /**
     * @var string
     *
     * @ORM\Column(name="ubicacion_fisica_entrega", type="string", length=1, nullable=true)
     */
    private $ubicacionFisicaEntrega;    
    

    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Piezas", inversedBy="entregasMinoristas")
     * @ORM\JoinColumn(name="pieza_id", referencedColumnName="id",nullable=true)
     */
    private $pieza;    
   

 

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
     * Set feEntrega
     *
     * @param \DateTime $feEntrega
     *
     * @return EntregasMinoristas
     */
    public function setFeEntrega($feEntrega)
    {
        $this->feEntrega = $feEntrega;

        return $this;
    }

    /**
     * Get feEntrega
     *
     * @return \DateTime
     */
    public function getFeEntrega()
    {
        return $this->feEntrega;
    }

    /**
     * Set pesoBrutoEntrega
     *
     * @param string $pesoBrutoEntrega
     *
     * @return EntregasMinoristas
     */
    public function setPesoBrutoEntrega($pesoBrutoEntrega)
    {
        $this->pesoBrutoEntrega = $pesoBrutoEntrega;

        return $this;
    }

    /**
     * Get pesoBrutoEntrega
     *
     * @return string
     */
    public function getPesoBrutoEntrega()
    {
        return $this->pesoBrutoEntrega;
    }

    /**
     * Set ley
     *
     * @param string $ley
     *
     * @return EntregasMinoristas
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
     * Set pesoPuroEntrega
     *
     * @param string $pesoPuroEntrega
     *
     * @return EntregasMinoristas
     */
    public function setPesoPuroEntrega($pesoPuroEntrega)
    {
        $this->pesoPuroEntrega = $pesoPuroEntrega;

        return $this;
    }

    /**
     * Get pesoPuroEntrega
     *
     * @return string
     */
    public function getPesoPuroEntrega()
    {
        return $this->pesoPuroEntrega;
    }


    /**
     * Set ubicacionFisicaEntrega
     *
     * @param string $ubicacionFisicaEntrega
     *
     * @return EntregasMinoristas
     */
    public function setUbicacionFisicaEntrega($ubicacionFisicaEntrega)
    {
        $this->ubicacionFisicaEntrega = $ubicacionFisicaEntrega;

        return $this;
    }

    /**
     * Get ubicacionFisicaEntrega
     *
     * @return string
     */
    public function getUbicacionFisicaEntrega()
    {
        return $this->ubicacionFisicaEntrega;
    }


    /**
     * Set minorista
     *
     * @param \lOro\EntityBundle\Entity\Proveedores $minorista
     *
     * @return EntregasMinoristas
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
     * Set pieza
     *
     * @param \lOro\EntityBundle\Entity\Piezas $pieza
     *
     * @return EntregasMinoristas
     */
    public function setPieza(\lOro\EntityBundle\Entity\Piezas $pieza = null)
    {
        $this->pieza = $pieza;

        return $this;
    }

    /**
     * Get pieza
     *
     * @return \lOro\EntityBundle\Entity\Piezas
     */
    public function getPieza()
    {
        return $this->pieza;
    }
}
