<?php

namespace lOro\BalanceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GenerarBalanceType extends AbstractType
{
    
    private $seleccione_opcion = 'Seleccione una Opción';
    
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('valorOnza','text',array('label' => 'Valor Actual de la Onza',
                                               'mapped' => false,
                                               'attr' => array('class' => 'form-control',
                                               'style' => 'margin-bottom:10px;')))
                ->add('dolarReferencia','text',array('label' => 'Euro de Referencia',
                                                              'mapped' => false,
                                                              'attr' => array('class' => 'form-control',
                                                              'style' => 'margin-bottom:10px;')))
              /*
                ->add('creditoHc','text',array('label' => 'Credito con HC',
                                                              'mapped' => false,
                                                              'attr' => array('class' => 'form-control',
                                                              'style' => 'margin-bottom:10px;'))) */ 
        ;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'loro_generar_balance';
    }
}
