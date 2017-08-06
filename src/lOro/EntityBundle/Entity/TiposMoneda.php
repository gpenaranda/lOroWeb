<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TiposMoneda
 *
 * @ORM\Table(name="tipos_moneda")
 * @ORM\Entity
 */
class TiposMoneda
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
     * @ORM\Column(name="nb_moneda", type="string", length=255, nullable=true)
     */
    private $nbMoneda;
    
    /**
     * @var string
     *
     * @ORM\Column(name="simbolo_moneda", type="string", length=4, nullable=true)
     */
    private $simboloMoneda;    

            
    /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\VentasDolares", mappedBy="tipoMoneda")
     */
    private $ventasDolares;   

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->transferencias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nbMoneda
     *
     * @param string $nbMoneda
     * @return TiposMoneda
     */
    public function setNbMoneda($nbMoneda)
    {
        $this->nbMoneda = $nbMoneda;

        return $this;
    }

    /**
     * Get nbMoneda
     *
     * @return string 
     */
    public function getNbMoneda()
    {
        return $this->nbMoneda;
    }

    /**
     * Set simboloMoneda
     *
     * @param string $simboloMoneda
     * @return TiposMoneda
     */
    public function setSimboloMoneda($simboloMoneda)
    {
        $this->simboloMoneda = $simboloMoneda;

        return $this;
    }

    /**
     * Get simboloMoneda
     *
     * @return string 
     */
    public function getSimboloMoneda()
    {
        return $this->simboloMoneda;
    }

    

    /**
     * Add ventasDolares
     *
     * @param \lOro\EntityBundle\Entity\Transferencias $ventasDolares
     * @return TiposMoneda
     */
    public function addVentasDolare(\lOro\EntityBundle\Entity\Transferencias $ventasDolares)
    {
        $this->ventasDolares[] = $ventasDolares;

        return $this;
    }

    /**
     * Remove ventasDolares
     *
     * @param \lOro\EntityBundle\Entity\Transferencias $ventasDolares
     */
    public function removeVentasDolare(\lOro\EntityBundle\Entity\Transferencias $ventasDolares)
    {
        $this->ventasDolares->removeElement($ventasDolares);
    }

    /**
     * Get ventasDolares
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVentasDolares()
    {
        return $this->ventasDolares;
    }
}
