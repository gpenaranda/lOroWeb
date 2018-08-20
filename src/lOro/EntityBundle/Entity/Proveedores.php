<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Proveedores
 *
 * @ORM\Table(name="proveedores")
 * @ORM\Entity(repositoryClass="lOro\EntityBundle\Entity\Repository\ProveedoresRepository")
 */
class Proveedores
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
     * @ORM\Column(name="nb_proveedor", type="string", length=255, nullable=true)
     */
    private $nbProveedor;

    /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\Entregas", mappedBy="proveedor")
     */
    private $entregas;

    /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\VentasCierres", mappedBy="proveedorCierre")
     */
    private $ventasCierres;
    
    /**
     * @ORM\OneToMany(targetEntity="\lOro\AppBundle\Entity\EntregasMinoristas", mappedBy="minorista")
     */
    private $entregasMinoristas;    
    
    /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\EmpresasProveedores", mappedBy="proveedor")
     */
    private $empresas;   
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="compra_dolares", type="boolean", nullable=true)
     */
    private $compraDolares;    
    
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\TiposProveedores", inversedBy="proveedores")
     * @ORM\JoinColumn(name="tipo_proveedor_id", referencedColumnName="id", nullable=true) 
     */
    private $tipoProveedor;     
    
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->entregas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ventasCierres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->entregasMinoristas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empresas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nbProveedor
     *
     * @param string $nbProveedor
     * @return Proveedores
     */
    public function setNbProveedor($nbProveedor)
    {
        $this->nbProveedor = $nbProveedor;

        return $this;
    }

    /**
     * Get nbProveedor
     *
     * @return string 
     */
    public function getNbProveedor()
    {
        return $this->nbProveedor;
    }

    /**
     * Set compraDolares
     *
     * @param boolean $compraDolares
     * @return Proveedores
     */
    public function setCompraDolares($compraDolares)
    {
        $this->compraDolares = $compraDolares;

        return $this;
    }

    /**
     * Get compraDolares
     *
     * @return boolean 
     */
    public function getCompraDolares()
    {
        return $this->compraDolares;
    }

    /**
     * Add entregas
     *
     * @param \lOro\EntityBundle\Entity\Entregas $entregas
     * @return Proveedores
     */
    public function addEntrega(\lOro\EntityBundle\Entity\Entregas $entregas)
    {
        $this->entregas[] = $entregas;

        return $this;
    }

    /**
     * Remove entregas
     *
     * @param \lOro\EntityBundle\Entity\Entregas $entregas
     */
    public function removeEntrega(\lOro\EntityBundle\Entity\Entregas $entregas)
    {
        $this->entregas->removeElement($entregas);
    }

    /**
     * Get entregas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEntregas()
    {
        return $this->entregas;
    }

    /**
     * Add ventasCierres
     *
     * @param \lOro\EntityBundle\Entity\VentasCierres $ventasCierres
     * @return Proveedores
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

    /**
     * Add entregasMinoristas
     *
     * @param \lOro\AppBundle\Entity\EntregasMinoristas $entregasMinoristas
     * @return Proveedores
     */
    public function addEntregasMinorista(\lOro\AppBundle\Entity\EntregasMinoristas $entregasMinoristas)
    {
        $this->entregasMinoristas[] = $entregasMinoristas;

        return $this;
    }

    /**
     * Remove entregasMinoristas
     *
     * @param \lOro\AppBundle\Entity\EntregasMinoristas $entregasMinoristas
     */
    public function removeEntregasMinorista(\lOro\AppBundle\Entity\EntregasMinoristas $entregasMinoristas)
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

    /**
     * Add empresas
     *
     * @param \lOro\EntityBundle\Entity\EmpresasProveedores $empresas
     * @return Proveedores
     */
    public function addEmpresa(\lOro\EntityBundle\Entity\EmpresasProveedores $empresas)
    {
        $this->empresas[] = $empresas;

        return $this;
    }

    /**
     * Remove empresas
     *
     * @param \lOro\EntityBundle\Entity\EmpresasProveedores $empresas
     */
    public function removeEmpresa(\lOro\EntityBundle\Entity\EmpresasProveedores $empresas)
    {
        $this->empresas->removeElement($empresas);
    }

    /**
     * Get empresas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmpresas()
    {
        return $this->empresas;
    }

    /**
     * Set tipoProveedor
     *
     * @param \lOro\EntityBundle\Entity\TiposProveedores $tipoProveedor
     * @return Proveedores
     */
    public function setTipoProveedor(\lOro\EntityBundle\Entity\TiposProveedores $tipoProveedor = null)
    {
        $this->tipoProveedor = $tipoProveedor;

        return $this;
    }

    /**
     * Get tipoProveedor
     *
     * @return \lOro\EntityBundle\Entity\TiposProveedores 
     */
    public function getTipoProveedor()
    {
        return $this->tipoProveedor;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Proveedores
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
}
