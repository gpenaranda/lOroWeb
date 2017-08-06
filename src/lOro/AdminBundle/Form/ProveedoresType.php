<?php

namespace lOro\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProveedoresType extends AbstractType
{
    
    private $seleccione_opcion = 'Seleccione una Opción';
    
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nbProveedor','text',array('label' => 'Nombre del Proveedor',
                                               'attr' => array('class' => 'form-control')))
            ->add('compraDolares','choice',array('label' => 'Realiza compra de dolares?',
                                                 'choices' => array(1 => 'Si', 0 => 'No'),
                                               'attr' => array('class' => 'form-control')))
            ->add('tipoProveedor','entity',array('label' => 'Tipo de Proveedor',
                                             'class' => 'lOroEntityBundle:TiposProveedores',
                                             'property' => 'nbTipoProveedor',
                                             'empty_value' => $this->seleccione_opcion,
                                             'attr' => array('class' => 'form-control')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'lOro\EntityBundle\Entity\Proveedores'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'loro_entitybundle_proveedores';
    }
}
