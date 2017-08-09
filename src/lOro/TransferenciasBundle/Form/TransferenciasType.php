<?php

namespace lOro\TransferenciasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class TransferenciasType extends AbstractType
{
    
    protected $empty_value = 'Seleccione una Opción';
    
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
       $compradorDolares = TRUE;
       $labelProveedor = ($compradorDolares == TRUE ? 'Comprador' : 'Proveedor');
       
       
        $builder
            ->add('feTransferencia',DateType::class,array(
                  'label' => 'Fecha de la transferencia',
                  'widget' => 'single_text',
                  'format' => 'dd-MM-yyyy',
                  'data' => new \ DateTime('now'),
                  'attr' => array('class' => 'form-control')))
            ->add('tipoTransaccion',EntityType::class,array('label' => 'Tipo de Transacción',
                                                          'mapped' => true,
                                                          'class' => 'lOroEntityBundle:TipoTransaccion',
                                                           'choice_label' => 'nbTransaccion',
                                                           'placeholder' => $this->empty_value,
                                                           'attr' => array('class' => 'form-control')))                       
            ->add('nroReferencia',TextType::class,array('label' => 'Nro de Referencia',
                                                     'attr' => array('class' => 'form-control'))) 
                
            ->add('montoTransferencia',TextType::class,array('label' => 'Monto de la Transferencia',
                                                     'attr' => array('class' => 'form-control')))
                
            ->add('beneficiario',EntityType::class,array(            'label' => $labelProveedor,
            'class' => 'lOroEntityBundle:Proveedores',
            'query_builder' => function(EntityRepository $er) use ($compradorDolares) {
              return $er->createQueryBuilder('u')
                        ->where('u.compraDolares = :compradorDolares')
                        ->setParameter('compradorDolares',$compradorDolares);
            },
            'choice_label' => 'nbProveedor',
            'placeholder' => 'Seleccione una Opción',
            'attr' => array('class' => 'form-control',
            'style' => 'margin-bottom:10px;'),
            'mapped'        => true))
                    
            ->add('estatus',ChoiceType::class,array( 'label' => 'Wired Transfer Status',
                                                     'choices_as_values' => true,
                                                     'choices' => array('No Enviada a HC' => 'N',
                                                                        'Confirmada' => 'C',
                                                                        'Pendiente' => 'P'),
                                                     'placeholder' => $this->empty_value,
                                                     'mapped' => true,
                                                     'attr' => array('class' => 'form-control')
                                                    ))  
                    
            ->add('empresa',TextType::class,array('label' => 'Empresa',
                                         'mapped' => true,
                                         'attr' => array('class' => 'form-control')))  
                    
                    
                    
            ->add('tipoMonedaConversion',EntityType::class,array('label' => 'Moneda de Origen (A descontar)',
                                              'class' => 'lOroEntityBundle:TiposMoneda',
                                              'query_builder' => function(EntityRepository $er) {
                                                    return $er->createQueryBuilder('u')
                                                              ->where('u.id NOT IN (:monedaExcluida)')
                                                              ->setParameter('monedaExcluida',array(1));
                                              },
                                                'choice_label' => 'nbMoneda',
                                                'placeholder' => $this->empty_value,
                                                'attr' => array('class' => 'form-control',
                                                'style' => 'margin-bottom:10px;'),
                                                'mapped'        => true,
                                                'required' => false))
                                                      
            ->add('montoAConvertir',TextType::class,array('label' => 'Monto a Convertir',
                                                 'mapped' => 'true',
                                                 'required' => false,
                                                 'attr' => array('class' => 'form-control')))                                                      
                    
            ->add('esConversion',ChoiceType::class,array( 'label' => 'Es una conversion de dinero?',
                                                          'choices_as_values' => true,
                                                          'choices' => array('Si' => 1, 'No' => 0),
                                                          'multiple' => false,
                                                          'expanded' => true,
                                                          'mapped' => 'true'
                                                        ))                    
            ->add('cotizacionReferencia',TextType::class,array('label' => 'Cotizacion de la Divisa',
                                                      'required' => false,
                                                      'mapped' => 'true',
                                                     'attr' => array('class' => 'form-control')))       
                                                      
            ->add('tipoMonedaTransf',EntityType::class,array('label' => 'Tipo de Moneda del Destino (De la Transferencia)',
                                              'class' => 'lOroEntityBundle:TiposMoneda',
                                              'query_builder' => function(EntityRepository $er) {
                                                    return $er->createQueryBuilder('u')
                                                              ->where('u.id NOT IN (:monedaExcluida)')
                                                              ->setParameter('monedaExcluida',array(1));
                                              },
                                                'choice_label' => 'nbMoneda',
                                                'placeholder' => $this->empty_value,
                                                'attr' => array('class' => 'form-control',
                                                'style' => 'margin-bottom:10px;'),
                                                'mapped'        => true))      
                                                      
            ->add('descripcion',TextType::class,array('label' => 'Descripcion',
                                             'mapped' => 'true',
                                                     'attr' => array('class' => 'form-control')))                                                        
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'lOro\EntityBundle\Entity\Transferencias'
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'loro_entitybundle_transferencias';
    }
}
