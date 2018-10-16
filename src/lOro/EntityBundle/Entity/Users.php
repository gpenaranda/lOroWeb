<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="lOro\EntityBundle\Entity\Repository\UsersRepository")
 */
class Users implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $salt;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;
    
    /**
     * @ORM\Column(name="dropbox_url", type="string", length=255, unique=false)
     */
    private $dropBoxUrl;    

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;


   /**
     * @ORM\ManyToMany(targetEntity="Role", cascade={"all"})
     * @ORM\JoinTable(name="user_roles",
     * joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    private $roles;


    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Proveedores")
     */
    private $proveedor;    
    


    public function __construct()
    {
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }



    public function getRoles() {
        return $this->roles->toArray();;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
        ) = unserialize($serialized);
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
     * Set username
     *
     * @param string $username
     * @return Users
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Users
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Users
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Users
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }


    /**
     * Set dropBoxUrl
     *
     * @param string $dropBoxUrl
     * @return Users
     */
    public function setDropBoxUrl($dropBoxUrl)
    {
        $this->dropBoxUrl = $dropBoxUrl;

        return $this;
    }

    /**
     * Get dropBoxUrl
     *
     * @return string 
     */
    public function getDropBoxUrl()
    {
        return $this->dropBoxUrl;
    }

    

    /**
     * Add role
     *
     * @param \lOro\EntityBundle\Entity\Role $role
     *
     * @return Users
     */
    public function addRole(\lOro\EntityBundle\Entity\Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \lOro\EntityBundle\Entity\Role $role
     */
    public function removeRole(\lOro\EntityBundle\Entity\Role $role)
    {
        $this->roles->removeElement($role);
    }

    /**
     * Set proveedor
     *
     * @param \lOro\EntityBundle\Entity\Proveedores $proveedor
     *
     * @return Users
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
}
