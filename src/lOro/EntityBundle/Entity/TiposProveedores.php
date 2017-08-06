<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TiposProveedores
 *
 * @ORM\Table(name="tipos_proveedores")
 * @ORM\Entity
 */
class TiposProveedores
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
     * @ORM\Column(name="nb_tipo_proveedor", type="string", length=255, nullable=true)
     */
    private $nbTipoProveedor;

    
    
    /**
     * @ORM\OneToMany(targetEntity="Proveedores", mappedBy="tipoProveedor")
     */
    private $proveedores;
            
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->proveedores = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nbTipoProveedor
     *
     * @param string $nbTipoProveedor
     * @return TiposProveedores
     */
    public function setNbTipoProveedor($nbTipoProveedor)
    {
        $this->nbTipoProveedor = $nbTipoProveedor;

        return $this;
    }

    /**
     * Get nbTipoProveedor
     *
     * @return string 
     */
    public function getNbTipoProveedor()
    {
        return $this->nbTipoProveedor;
    }

    /**
     * Add proveedores
     *
     * @param \lOro\EntityBundle\Entity\Proveedores $proveedores
     * @return TiposProveedores
     */
    public function addProveedore(\lOro\EntityBundle\Entity\Proveedores $proveedores)
    {
        $this->proveedores[] = $proveedores;

        return $this;
    }

    /**
     * Remove proveedores
     *
     * @param \lOro\EntityBundle\Entity\Proveedores $proveedores
     */
    public function removeProveedore(\lOro\EntityBundle\Entity\Proveedores $proveedores)
    {
        $this->proveedores->removeElement($proveedores);
    }

    /**
     * Get proveedores
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProveedores()
    {
        return $this->proveedores;
    }
}
