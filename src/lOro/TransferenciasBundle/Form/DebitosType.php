<?php

namespace lOro\TransferenciasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class DebitosType extends AbstractType
{
    
    protected $empty_value = 'Seleccione una Opción';
    
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('feDebito','date',array(
                  'label' => 'Fecha del Debito',
                  'widget' => 'single_text',
                  'format' => 'dd-MM-yyyy',
                  'data' => new \ DateTime('now'),
                  'attr' => array('class' => 'form-control')))
            ->add('monto','text',array('label' => 'Monto del Debito',
                                                     'attr' => array('class' => 'form-control')))
            ->add('tipoTransaccion','entity',array('label' => 'Tipo de Transacción',
                                                          'mapped' => true,
                                                          'class' => 'lOroEntityBundle:TipoTransaccion',
                                                           'property' => 'nbTransaccion',
                                                           'empty_value' => $this->empty_value,
                                                           'attr' => array('class' => 'form-control')))
            ->add('tipoPago','choice',array('label' => 'Tipo de Debito',
                                                          'mapped' => true,
                                                          'choices' => array('B' => 'Bolivares', 'D' => 'Dolares', 'E' => 'Euros'),
                                                           'empty_value' => $this->empty_value,
                                                           'attr' => array('class' => 'form-control')))                
            ->add('descripcion','text',array('label' => 'Descripción del Debito',
                                                     'attr' => array('class' => 'form-control')))
                
           ->add('nroReferencia','text',array('label' => 'N° de Referencia',
                                              'required' => false,
                                              'attr' => array('class' => 'form-control')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'lOro\EntityBundle\Entity\Debitos'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'loro_transferenciasbundle_debitos';
    }
}

