<?php

namespace lOro\TransferenciasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class PagosVariosType extends AbstractType
{
    
    protected $empty_value = 'Seleccione una Opción';
    
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fePago',DateType::class,array(
                  'label' => 'Fecha del Pago',
                  'widget' => 'single_text',
                  'format' => 'dd-MM-yyyy',
                  'data' => new \ DateTime('now'),
                  'attr' => array('class' => 'form-control')))
            ->add('montoPago',TextType::class,array('label' => 'Monto del Pago',
                                                     'attr' => array('class' => 'form-control')))
            ->add('tipoPagoVario',EntityType::class,array('label' => 'Concepto del Pago Vario',
                                                          'mapped' => true,
                                                          'class' => 'lOroEntityBundle:TiposPagosVarios',
                                                           'choice_label' => 'descripcion',
                                                           'placeholder' => $this->empty_value,
                                                           'attr' => array('class' => 'form-control')))                
            ->add('tipoTransaccion',EntityType::class,array('label' => 'Tipo de Transacción',
                                                          'mapped' => true,
                                                          'class' => 'lOroEntityBundle:TipoTransaccion',
                                                           'choice_label' => 'nbTransaccion',
                                                           'placeholder' => $this->empty_value,
                                                           'attr' => array('class' => 'form-control')))
            ->add('tipoPago',ChoiceType::class,array('label' => 'Tipo de Pago',
                                                          'mapped' => true,
                                                          'choices_as_values' => true,
                                                          'choices' => array('Bolivares' => 'B', 'Euros' => 'E'),
                                                           'placeholder' => $this->empty_value,
                                                           'attr' => array('class' => 'form-control')))                
            ->add('descripcionPago',TextType::class,array('label' => 'Descripción del Pago',
                                                     'attr' => array('class' => 'form-control')))
                
           ->add('nroReferencia',TextType::class,array('label' => 'N° de Referencia',
                                              'required' => false,
                                              'attr' => array('class' => 'form-control')))     
              ->add('empresaCasa',EntityType::class,array('label' => 'Pagado por la Empresa',
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
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'lOro\EntityBundle\Entity\PagosVarios'
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'loro_transferenciasbundle_pagosvarios';
    }
}
