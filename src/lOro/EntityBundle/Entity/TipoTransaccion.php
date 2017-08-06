<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoTransaccion
 *
 * @ORM\Table(name="tipo_transaccion")
 * @ORM\Entity
 */
class TipoTransaccion
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
     * @ORM\Column(name="nb_transaccion", type="string", length=255, nullable=true)
     */
    private $nbTransaccion;

    /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\PagosProveedores", mappedBy="tipoTransaccion")
     */
    private $pagosProveedores;       
    
    /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\PagosMinoristas", mappedBy="tipoTransaccion")
     */
    private $pagosMinoristas;      
    
    /**
     * @ORM\OneToMany(targetEntity="PagosVarios", mappedBy="tipoTransaccion")
     */
    private $pagosVarios;
            
    /**
     * @ORM\OneToMany(targetEntity="\lOro\EntityBundle\Entity\Transferencias", mappedBy="tipoTransaccion")
     */
    private $transferencias;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagosProveedores = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nbTransaccion
     *
     * @param string $nbTransaccion
     * @return TipoTransaccion
     */
    public function setNbTransaccion($nbTransaccion)
    {
        $this->nbTransaccion = $nbTransaccion;

        return $this;
    }

    /**
     * Get nbTransaccion
     *
     * @return string 
     */
    public function getNbTransaccion()
    {
        return $this->nbTransaccion;
    }

    /**
     * Add pagosProveedores
     *
     * @param \lOro\EntityBundle\Entity\PagosProveedores $pagosProveedores
     * @return TipoTransaccion
     */
    public function addPagosProveedore(\lOro\EntityBundle\Entity\PagosProveedores $pagosProveedores)
    {
        $this->pagosProveedores[] = $pagosProveedores;

        return $this;
    }

    /**
     * Remove pagosProveedores
     *
     * @param \lOro\EntityBundle\Entity\PagosProveedores $pagosProveedores
     */
    public function removePagosProveedore(\lOro\EntityBundle\Entity\PagosProveedores $pagosProveedores)
    {
        $this->pagosProveedores->removeElement($pagosProveedores);
    }

    /**
     * Get pagosProveedores
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPagosProveedores()
    {
        return $this->pagosProveedores;
    }

    /**
     * Add transferencias
     *
     * @param \lOro\EntityBundle\Entity\Transferencias $transferencias
     * @return TipoTransaccion
     */
    public function addTransferencia(\lOro\EntityBundle\Entity\Transferencias $transferencias)
    {
        $this->transferencias[] = $transferencias;

        return $this;
    }

    /**
     * Remove transferencias
     *
     * @param \lOro\EntityBundle\Entity\Transferencias $transferencias
     */
    public function removeTransferencia(\lOro\EntityBundle\Entity\Transferencias $transferencias)
    {
        $this->transferencias->removeElement($transferencias);
    }

    /**
     * Get transferencias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTransferencias()
    {
        return $this->transferencias;
    }

    /**
     * Add pagosVarios
     *
     * @param \lOro\EntityBundle\Entity\PagosVarios $pagosVarios
     * @return TipoTransaccion
     */
    public function addPagosVario(\lOro\EntityBundle\Entity\PagosVarios $pagosVarios)
    {
        $this->pagosVarios[] = $pagosVarios;

        return $this;
    }

    /**
     * Remove pagosVarios
     *
     * @param \lOro\EntityBundle\Entity\PagosVarios $pagosVarios
     */
    public function removePagosVario(\lOro\EntityBundle\Entity\PagosVarios $pagosVarios)
    {
        $this->pagosVarios->removeElement($pagosVarios);
    }

    /**
     * Get pagosVarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPagosVarios()
    {
        return $this->pagosVarios;
    }

    /**
     * Add pagosMinoristas
     *
     * @param \lOro\EntityBundle\Entity\PagosMinoristas $pagosMinoristas
     * @return TipoTransaccion
     */
    public function addPagosMinorista(\lOro\EntityBundle\Entity\PagosMinoristas $pagosMinoristas)
    {
        $this->pagosMinoristas[] = $pagosMinoristas;

        return $this;
    }

    /**
     * Remove pagosMinoristas
     *
     * @param \lOro\EntityBundle\Entity\PagosMinoristas $pagosMinoristas
     */
    public function removePagosMinorista(\lOro\EntityBundle\Entity\PagosMinoristas $pagosMinoristas)
    {
        $this->pagosMinoristas->removeElement($pagosMinoristas);
    }

    /**
     * Get pagosMinoristas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPagosMinoristas()
    {
        return $this->pagosMinoristas;
    }
}
