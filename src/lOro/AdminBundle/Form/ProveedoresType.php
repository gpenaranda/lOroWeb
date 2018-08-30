<?php

namespace lOro\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

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
            ->add('nbProveedor',TextType::class,array('label' => 'Nombre del Proveedor',
                                               'attr' => array('class' => 'form-control')))
            ->add('compraDolares',ChoiceType::class,array('label' => 'Realiza compra de dolares?',
                                                          'choices_as_values' => true,
                                                          'placeholder' => $this->seleccione_opcion,
                                                          'choices' => array('Si' => 1, 'No' => 0),
                                                          'attr' => array('class' => 'form-control')))
            ->add('tipoProveedor',EntityType::class,array('label' => 'Tipo de Proveedor',
                                             'class' => 'lOroEntityBundle:TiposProveedores',
                                             'choice_label' => 'nbTipoProveedor',
                                             'placeholder' => $this->seleccione_opcion,
                                             'attr' => array('class' => 'form-control')))
            ->add('status',ChoiceType::class,array('label' => 'Estatus del Proveedor',
                                                   'choices_as_values' => true,
                                                   'placeholder' => $this->seleccione_opcion,
                                                   'choices' => array('Activo' => 'A', 'Inactivo' => 'I'),
                                                   'attr' => array('class' => 'form-control')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'lOro\EntityBundle\Entity\Proveedores'
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'loro_entitybundle_proveedores';
    }
}
