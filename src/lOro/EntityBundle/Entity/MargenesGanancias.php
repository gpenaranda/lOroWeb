<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * MargenesGanancias
 *
 * @ORM\Table(name="margenes_ganancias")
 * @ORM\Entity
 */
class MargenesGanancias
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
     * @ORM\Column(name="nb_margen_ganancia", type="string", length=255, nullable=false)
     */
    private $nbMargenGanancia;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_margen", type="string", length=255, nullable=false)
     */
    private $tipoMargen;

    /**
     * @var string
     *
     * @ORM\Column(name="estatus", type="string", length=1, nullable=false)
     */
    private $estatus;


    /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\VentasCierres", mappedBy="margenGanancia")
     */
    private $ventasCierres;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ventasCierres = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nbMargenGanancia
     *
     * @param string $nbMargenGanancia
     * @return MargenesGanancias
     */
    public function setNbMargenGanancia($nbMargenGanancia)
    {
        $this->nbMargenGanancia = $nbMargenGanancia;

        return $this;
    }

    /**
     * Get nbMargenGanancia
     *
     * @return string 
     */
    public function getNbMargenGanancia()
    {
        return $this->nbMargenGanancia;
    }

    /**
     * Set tipoMargen
     *
     * @param string $tipoMargen
     * @return MargenesGanancias
     */
    public function setTipoMargen($tipoMargen)
    {
        $this->tipoMargen = $tipoMargen;

        return $this;
    }

    /**
     * Get tipoMargen
     *
     * @return string 
     */
    public function getTipoMargen()
    {
        return $this->tipoMargen;
    }

    /**
     * Set estatus
     *
     * @param string $estatus
     * @return MargenesGanancias
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
     * Add ventasCierres
     *
     * @param \lOro\EntityBundle\Entity\VentasCierres $ventasCierres
     * @return MargenesGanancias
     */
    public function addVentasCierre(\lOro\EntityBundle\Entity\VentasCierres $ventasCierres)
    {
        $this->ventasCierres[] = $ventasCierres;

        return $this;
    }

    /**
     * Remove ventasCierres
     *
     * @param \lOro\EntityBundle\Entity\VentasCierres $ventasCierres
     */
    public function removeVentasCierre(\lOro\EntityBundle\Entity\VentasCierres $ventasCierres)
    {
        $this->ventasCierres->removeElement($ventasCierres);
    }

    /**
     * Get ventasCierres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVentasCierres()
    {
        return $this->ventasCierres;
    }
}
