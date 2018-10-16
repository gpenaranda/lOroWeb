<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parametros
 *
 * @ORM\Table(name="parametros")
 * @ORM\Entity
 */
class Parametros
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
     * @ORM\Column(name="nb_parametro", type="string", length=255)
     */
    private $nbParametro;

    /**
     * @var string
     *
     * @ORM\Column(name="valor_parametro", type="string", length=255)
     */
    private $valorParametro;

    /**
     * @var string
     *
     * @ORM\Column(name="estatus", type="string", length=1)
     */
    private $estatus;


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
     * Set nbParametro
     *
     * @param string $nbParametro
     * @return Parametros
     */
    public function setNbParametro($nbParametro)
    {
        $this->nbParametro = $nbParametro;

        return $this;
    }

    /**
     * Get nbParametro
     *
     * @return string 
     */
    public function getNbParametro()
    {
        return $this->nbParametro;
    }

    /**
     * Set valorParametro
     *
     * @param string $valorParametro
     * @return Parametros
     */
    public function setValorParametro($valorParametro)
    {
        $this->valorParametro = $valorParametro;

        return $this;
    }

    /**
     * Get valorParametro
     *
     * @return string 
     */
    public function getValorParametro()
    {
        return $this->valorParametro;
    }

    /**
     * Set estatus
     *
     * @param string $estatus
     * @return Parametros
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
}
