<?php

namespace lOro\TransferenciasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConversionTransferenciasType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('feCambio','date',array(
                  'label' => 'Fecha',
                  'widget' => 'single_text',
                  'format' => 'dd-MM-yyyy',
                  'data' => new \ DateTime('now'),
                  'attr' => array('class' => 'form-control')))
            ->add('montoCambiado','text',array('label' => 'Monto a Cambiar',
                                                     'attr' => array('class' => 'form-control')))
            ->add('dolarReferencia','text',array('label' => 'Dolar de Referencia',
                                                     'attr' => array('class' => 'form-control')))
            ->add('montoFinalBolivares','text',array('label' => 'Monto Resultante de la Conversión',
                                                     'read_only' => true,
                                                     'attr' => array('class' => 'form-control')))                
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'lOro\EntityBundle\Entity\CambioTransferenciasBs'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'loro_entitybundle_conversion_transferencias';
    }
}

