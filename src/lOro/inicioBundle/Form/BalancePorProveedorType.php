<?php

namespace lOro\inicioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BalancePorProveedorType extends AbstractType
{
    
    private $seleccione_opcion = 'Seleccione una Opción';
    
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('proveedores','entity',array('label' => 'Proveedor',
                                                   'class' => 'lOroEntityBundle:Proveedores',
                                                   'property' => 'nbProveedor',
                                                   'empty_value' => $this->seleccione_opcion,
                                                   'attr' => array('class' => 'form-control',
                                                   'style' => 'margin-bottom:10px;')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'loro_balances_proveedores';
    }
}

