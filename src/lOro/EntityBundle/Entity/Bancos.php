<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bancos
 *
 * @ORM\Table(name="bancos")
 * @ORM\Entity
 */
class Bancos
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
     * @ORM\Column(name="nb_banco", type="string", length=255)
     */
    private $nbBanco;


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
     * Set nbBanco
     *
     * @param string $nbBanco
     * @return Bancos
     */
    public function setNbBanco($nbBanco)
    {
        $this->nbBanco = $nbBanco;

        return $this;
    }

    /**
     * Get nbBanco
     *
     * @return string 
     */
    public function getNbBanco()
    {
        return $this->nbBanco;
    }
}
