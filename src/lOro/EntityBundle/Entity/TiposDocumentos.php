<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TiposDocumentos
 *
 * @ORM\Table(name="tipos_documentos")
 * @ORM\Entity
 */
class TiposDocumentos
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="codigo_banplus", type="string", length=255)
     */
    private $codigoBanplus;    

    

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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TiposDocumentos
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set codigoBanplus
     *
     * @param string $codigoBanplus
     *
     * @return TiposDocumentos
     */
    public function setCodigoBanplus($codigoBanplus)
    {
        $this->codigoBanplus = $codigoBanplus;

        return $this;
    }

    /**
     * Get codigoBanplus
     *
     * @return string
     */
    public function getCodigoBanplus()
    {
        return $this->codigoBanplus;
    }
}
