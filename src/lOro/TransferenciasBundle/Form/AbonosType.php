<?php

namespace lOro\TransferenciasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class AbonosType extends AbstractType
{
    
    protected $empty_value = 'Seleccione una Opción';
    
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('feAbono','date',array(
                  'label' => 'Fecha del Abono',
                  'widget' => 'single_text',
                  'format' => 'dd-MM-yyyy',
                  'data' => new \ DateTime('now'),
                  'attr' => array('class' => 'form-control')))
            ->add('monto','text',array('label' => 'Monto del Abono',
                                                     'attr' => array('class' => 'form-control')))
            ->add('tipoTransaccion','entity',array('label' => 'Tipo de Transacción',
                                                          'mapped' => true,
                                                          'class' => 'lOroEntityBundle:TipoTransaccion',
                                                           'property' => 'nbTransaccion',
                                                           'empty_value' => $this->empty_value,
                                                           'attr' => array('class' => 'form-control')))
            ->add('tipoPago','choice',array('label' => 'Tipo de Abono',
                                                          'mapped' => true,
                                                          'choices' => array('B' => 'Bolivares', 'V' => 'Verdes'),
                                                           'empty_value' => $this->empty_value,
                                                           'attr' => array('class' => 'form-control')))                
            ->add('descripcion','text',array('label' => 'Descripción del Abono',
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
            'data_class' => 'lOro\EntityBundle\Entity\Abonos'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'loro_transferenciasbundle_abonos';
    }
}
