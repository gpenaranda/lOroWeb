<?php

namespace lOro\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VentasDolaresEmpresasCasa
 *
 * @ORM\Table(name="ventas_dolares_empresas_casa")
 * @ORM\Entity()
 */
class VentasDolaresEmpresasCasa
{
  
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\VentasDolares")
     * @ORM\JoinColumn(name="venta_dolares_id", referencedColumnName="id")
     */
    private $ventaDolares;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\lOro\EntityBundle\Entity\EmpresasProveedores")
     * @ORM\JoinColumn(name="empresa_casa_id", referencedColumnName="id")
     */
    private $empresaCasa;

    
    /**
     * @var string
     *
     * @ORM\Column(name="cantidad_transferida", type="decimal", precision=14, scale=2, nullable=true)
     */
    private $cantidadTransferida;    


    /**
     * Set cantidadTransferida
     *
     * @param string $cantidadTransferida
     * @return VentasDolaresEmpresasCasa
     */
    public function setCantidadTransferida($cantidadTransferida)
    {
        $this->cantidadTransferida = $cantidadTransferida;

        return $this;
    }

    /**
     * Get cantidadTransferida
     *
     * @return string 
     */
    public function getCantidadTransferida()
    {
        return $this->cantidadTransferida;
    }

    /**
     * Set ventaDolares
     *
     * @param \lOro\EntityBundle\Entity\VentasDolares $ventaDolares
     * @return VentasDolaresEmpresasCasa
     */
    public function setVentaDolares(\lOro\EntityBundle\Entity\VentasDolares $ventaDolares)
    {
        $this->ventaDolares = $ventaDolares;

        return $this;
    }

    /**
     * Get ventaDolares
     *
     * @return \lOro\EntityBundle\Entity\VentasDolares 
     */
    public function getVentaDolares()
    {
        return $this->ventaDolares;
    }

    /**
     * Set empresaCasa
     *
     * @param \lOro\EntityBundle\Entity\EmpresasProveedores $empresaCasa
     * @return VentasDolaresEmpresasCasa
     */
    public function setEmpresaCasa(\lOro\EntityBundle\Entity\EmpresasProveedores $empresaCasa)
    {
        $this->empresaCasa = $empresaCasa;

        return $this;
    }

    /**
     * Get empresaCasa
     *
     * @return \lOro\EntityBundle\Entity\EmpresasProveedores 
     */
    public function getEmpresaCasa()
    {
        return $this->empresaCasa;
    }
}
