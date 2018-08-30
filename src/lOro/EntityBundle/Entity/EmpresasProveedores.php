<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmpresasProveedores
 *
 * @ORM\Table(name="empresas_proveedores")
 * @ORM\Entity
 */
class EmpresasProveedores
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
     * @ORM\Column(name="nombre_empresa", type="string", length=60, nullable=true)
     */
    private $nombreEmpresa;

    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\TiposDocumentos")
     * @ORM\JoinColumn(name="tipo_documento_id", referencedColumnName="id", nullable=true) 
     */
    private $tipoDocumento;

    /**
     * @var string
     *
     * @ORM\Column(name="rif", type="string", length=12, nullable=true)
     */
    private $rif;
    
    /**
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\Proveedores", inversedBy="empresas")
     * @ORM\JoinColumn(name="proveedor_id", referencedColumnName="id", nullable=true) 
     */
    private $proveedor;
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="es_empresa_casa", type="boolean", nullable=true) 
     */
    private $esEmpresaCasa;   

    /**
     * @var boolean
     * 
     * @ORM\Column(name="is_worker", type="boolean", nullable=true) 
     */
    private $isWorker;      
    
     /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\PagosProveedores", mappedBy="empresaPago")
     */
    private $pagosRealizados; 
    
     /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\PagosProveedores", mappedBy="empresaCasa")
     */
    private $pagosRealizadosCasa;
    
     /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\PagosVarios", mappedBy="empresaCasa")
     */
    private $pagosVariosRealizadosCasa;    
    
     /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\PagosMinoristas", mappedBy="empresaPago")
     */
    private $pagosRealizadosMinoristas; 
    
     /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\PagosMinoristas", mappedBy="empresaCasa")
     */
    private $pagosRealizadosCasaMinoristas;    
    
    /**
     * @var string
     * 
     * @ORM\Column(name="estatus", type="string",length=1, nullable=true) 
     */
    private $estatus;  
    
    /**
     * @var string
     * 
     * @ORM\Column(name="alias_empresa", type="string",length=40, nullable=true) 
     */
    private $aliasEmpresa;     
    
    
    public function __toString() {
      return $this->nombreEmpresa;
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
     * Set nombreEmpresa
     *
     * @param string $nombreEmpresa
     * @return EmpresasProveedores
     */
    public function setNombreEmpresa($nombreEmpresa)
    {
        $this->nombreEmpresa = $nombreEmpresa;

        return $this;
    }

    /**
     * Get nombreEmpresa
     *
     * @return string 
     */
    public function getNombreEmpresa()
    {
        return $this->nombreEmpresa;
    }
    
    
   

    /**
     * Set proveedor
     *
     * @param \lOro\EntityBundle\Entity\Proveedores $proveedor
     * @return EmpresasProveedores
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
     * Constructor
     */
    public function __construct()
    {
        $this->pagosRealizados = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pagosRealizados
     *
     * @param \lOro\EntityBundle\Entity\PagosProveedores $pagosRealizados
     * @return EmpresasProveedores
     */
    public function addPagosRealizado(\lOro\EntityBundle\Entity\PagosProveedores $pagosRealizados)
    {
        $this->pagosRealizados[] = $pagosRealizados;

        return $this;
    }

    /**
     * Remove pagosRealizados
     *
     * @param \lOro\EntityBundle\Entity\PagosProveedores $pagosRealizados
     */
    public function removePagosRealizado(\lOro\EntityBundle\Entity\PagosProveedores $pagosRealizados)
    {
        $this->pagosRealizados->removeElement($pagosRealizados);
    }

    /**
     * Get pagosRealizados
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPagosRealizados()
    {
        return $this->pagosRealizados;
    }

    /**
     * Set esEmpresaCasa
     *
     * @param boolean $esEmpresaCasa
     * @return EmpresasProveedores
     */
    public function setEsEmpresaCasa($esEmpresaCasa)
    {
        $this->esEmpresaCasa = $esEmpresaCasa;

        return $this;
    }



    /**
     * Get esEmpresaCasa
     *
     * @return boolean 
     */
    public function getEsEmpresaCasa()
    {
        return $this->esEmpresaCasa;
    }    

    /**
     * Get isWorker
     *
     * @return boolean 
     */
    public function getIsWorker()
    {
        return $this->isWorker;
    }

    /**
     * Set isWorker
     *
     * @param boolean $isWorker
     * @return EmpresasProveedores
     */
    public function setIsWorker($isWorker)
    {
        $this->isWorker = $isWorker;

        return $this;
    }    

    /**
     * Set estatus
     *
     * @param string $estatus
     * @return EmpresasProveedores
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
     * Add pagosRealizadosCasa
     *
     * @param \lOro\EntityBundle\Entity\PagosProveedores $pagosRealizadosCasa
     * @return EmpresasProveedores
     */
    public function addPagosRealizadosCasa(\lOro\EntityBundle\Entity\PagosProveedores $pagosRealizadosCasa)
    {
        $this->pagosRealizadosCasa[] = $pagosRealizadosCasa;

        return $this;
    }

    /**
     * Remove pagosRealizadosCasa
     *
     * @param \lOro\EntityBundle\Entity\PagosProveedores $pagosRealizadosCasa
     */
    public function removePagosRealizadosCasa(\lOro\EntityBundle\Entity\PagosProveedores $pagosRealizadosCasa)
    {
        $this->pagosRealizadosCasa->removeElement($pagosRealizadosCasa);
    }

    /**
     * Get pagosRealizadosCasa
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPagosRealizadosCasa()
    {
        return $this->pagosRealizadosCasa;
    }

    /**
     * Add pagosVariosRealizadosCasa
     *
     * @param \lOro\EntityBundle\Entity\PagosVarios $pagosVariosRealizadosCasa
     * @return EmpresasProveedores
     */
    public function addPagosVariosRealizadosCasa(\lOro\EntityBundle\Entity\PagosVarios $pagosVariosRealizadosCasa)
    {
        $this->pagosVariosRealizadosCasa[] = $pagosVariosRealizadosCasa;

        return $this;
    }

    /**
     * Remove pagosVariosRealizadosCasa
     *
     * @param \lOro\EntityBundle\Entity\PagosVarios $pagosVariosRealizadosCasa
     */
    public function removePagosVariosRealizadosCasa(\lOro\EntityBundle\Entity\PagosVarios $pagosVariosRealizadosCasa)
    {
        $this->pagosVariosRealizadosCasa->removeElement($pagosVariosRealizadosCasa);
    }

    /**
     * Get pagosVariosRealizadosCasa
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPagosVariosRealizadosCasa()
    {
        return $this->pagosVariosRealizadosCasa;
    }

    /**
     * Add pagosRealizadosMinoristas
     *
     * @param \lOro\EntityBundle\Entity\PagosMinoristas $pagosRealizadosMinoristas
     * @return EmpresasProveedores
     */
    public function addPagosRealizadosMinorista(\lOro\EntityBundle\Entity\PagosMinoristas $pagosRealizadosMinoristas)
    {
        $this->pagosRealizadosMinoristas[] = $pagosRealizadosMinoristas;

        return $this;
    }

    /**
     * Remove pagosRealizadosMinoristas
     *
     * @param \lOro\EntityBundle\Entity\PagosMinoristas $pagosRealizadosMinoristas
     */
    public function removePagosRealizadosMinorista(\lOro\EntityBundle\Entity\PagosMinoristas $pagosRealizadosMinoristas)
    {
        $this->pagosRealizadosMinoristas->removeElement($pagosRealizadosMinoristas);
    }

    /**
     * Get pagosRealizadosMinoristas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPagosRealizadosMinoristas()
    {
        return $this->pagosRealizadosMinoristas;
    }

    /**
     * Add pagosRealizadosCasaMinoristas
     *
     * @param \lOro\EntityBundle\Entity\PagosMinoristas $pagosRealizadosCasaMinoristas
     * @return EmpresasProveedores
     */
    public function addPagosRealizadosCasaMinorista(\lOro\EntityBundle\Entity\PagosMinoristas $pagosRealizadosCasaMinoristas)
    {
        $this->pagosRealizadosCasaMinoristas[] = $pagosRealizadosCasaMinoristas;

        return $this;
    }

    /**
     * Remove pagosRealizadosCasaMinoristas
     *
     * @param \lOro\EntityBundle\Entity\PagosMinoristas $pagosRealizadosCasaMinoristas
     */
    public function removePagosRealizadosCasaMinorista(\lOro\EntityBundle\Entity\PagosMinoristas $pagosRealizadosCasaMinoristas)
    {
        $this->pagosRealizadosCasaMinoristas->removeElement($pagosRealizadosCasaMinoristas);
    }

    /**
     * Get pagosRealizadosCasaMinoristas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPagosRealizadosCasaMinoristas()
    {
        return $this->pagosRealizadosCasaMinoristas;
    }

    /**
     * Set rif
     *
     * @param string $rif
     *
     * @return EmpresasProveedores
     */
    public function setRif($rif)
    {
        $this->rif = $rif;

        return $this;
    }

    /**
     * Get rif
     *
     * @return string
     */
    public function getRif()
    {
        return $this->rif;
    }

    /**
     * Set tipoDocumento
     *
     * @param \lOro\EntityBundle\Entity\TiposDocumentos $tipoDocumento
     *
     * @return EmpresasProveedores
     */
    public function setTipoDocumento(\lOro\EntityBundle\Entity\TiposDocumentos $tipoDocumento = null)
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    /**
     * Get tipoDocumento
     *
     * @return \lOro\EntityBundle\Entity\TiposDocumentos
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * Set aliasEmpresa
     *
     * @param string $aliasEmpresa
     *
     * @return EmpresasProveedores
     */
    public function setAliasEmpresa($aliasEmpresa)
    {
        $this->aliasEmpresa = $aliasEmpresa;

        return $this;
    }

    /**
     * Get aliasEmpresa
     *
     * @return string
     */
    public function getAliasEmpresa()
    {
        return $this->aliasEmpresa;
    }
}
