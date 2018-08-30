<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="proveedores_usuarios")
 * @ORM\Entity()
 */
class ProveedoresUsuarios
{
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Proveedores")
     * @ORM\Id
     * @ORM\JoinColumn(name="proveedor_id", referencedColumnName="id") 
     */
    private $proveedor;

    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Users")
     * @ORM\Id
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id") 
     */
    private $user;    


    /**
     * Set proveedor
     *
     * @param \lOro\EntityBundle\Entity\Proveedores $proveedor
     * @return proveedor
     */
    public function setProveedor(\lOro\EntityBundle\Entity\Proveedores $proveedor = null)
    {
        $this->proveedor = $proveedor;

        return $this;
    }

    /**
     * Get proveedor
     *
     * @return \lOro\EntityBundle\Entity\Proveedores 
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }



    /**
     * Set user
     *
     * @param \lOro\EntityBundle\Entity\Users $user
     * @return user
     */
    public function setUser(\lOro\EntityBundle\Entity\Users $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \lOro\EntityBundle\Entity\Users 
     */
    public function getUser()
    {
        return $this->user;
    }    

}
