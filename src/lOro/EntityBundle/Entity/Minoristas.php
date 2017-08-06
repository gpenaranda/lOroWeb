<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Minoristas
 *
 * @ORM\Table(name="minoristas")
 * @ORM\Entity(repositoryClass="lOro\EntityBundle\Entity\Repository\MinoristasRepository")
 */
class Minoristas
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
     * @ORM\Column(name="nb_minorista", type="string", length=255, nullable=true)
     */
    private $nbMinorista;

    /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\EntregasMinoristas", mappedBy="minorista")
     */
    private $entregasMinoristas;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->entregasMinoristas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nbMinorista
     *
     * @param string $nbMinorista
     * @return Minoristas
     */
    public function setNbMinorista($nbMinorista)
    {
        $this->nbMinorista = $nbMinorista;

        return $this;
    }

    /**
     * Get nbMinorista
     *
     * @return string 
     */
    public function getNbMinorista()
    {
        return $this->nbMinorista;
    }

    /**
     * Add entregasMinoristas
     *
     * @param \lOro\EntityBundle\Entity\EntregasMinoristas $entregasMinoristas
     * @return Minoristas
     */
    public function addEntregasMinorista(\lOro\EntityBundle\Entity\EntregasMinoristas $entregasMinoristas)
    {
        $this->entregasMinoristas[] = $entregasMinoristas;

        return $this;
    }

    /**
     * Remove entregasMinoristas
     *
     * @param \lOro\EntityBundle\Entity\EntregasMinoristas $entregasMinoristas
     */
    public function removeEntregasMinorista(\lOro\EntityBundle\Entity\EntregasMinoristas $entregasMinoristas)
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
}
