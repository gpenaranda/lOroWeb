<?php

namespace lOro\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PagosCargaMasivaType extends AbstractType
{
    
    protected $empty_value = 'Seleccione una Opción';

    
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    { 
      $builder->add('feEjecucion',DateType::class,array( 'label' => 'Fecha de Ejecución',
                                                          'widget' => 'single_text',
                                                          'format' => 'dd-MM-yyyy',
                                                          'attr' => array('class' => 'form-control')))
              ->add('empresaCasa',EntityType::class,array('label' => 'Empresa',
                                                          'class' => 'lOroEntityBundle:EmpresasProveedores',
                                                          'query_builder' => function(EntityRepository $er) {
                                                            return $er->createQueryBuilder('u')
                                                                      ->where('u.esEmpresaCasa = TRUE');
                                                          },                
                                                          'choice_label' => 'nombreEmpresa',
                                                          'multiple' => false,
                                                          'expanded' => false,
                                                          'placeholder' => $this->empty_value,
                                                          'attr' => array('class' => 'form-control')))
              ->add('nrosCuenta',ChoiceType::class,array( 'label' => 'Cuenta Origen',
                                                          'choices' => array(),
                                                          'choices_as_values' => true,      
                                                          'multiple' => false,
                                                          'expanded' => false,
                                                          'placeholder' => $this->empty_value,
                                                          'attr' => array('class' => 'form-control')));
    }
    

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'loro_pagos_carga_masiva';
    }
}

