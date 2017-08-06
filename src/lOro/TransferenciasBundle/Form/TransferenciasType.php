<?php

namespace lOro\TransferenciasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

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
            ->add('feTransferencia','date',array(
                  'label' => 'Fecha de la transferencia',
                  'widget' => 'single_text',
                  'format' => 'dd-MM-yyyy',
                  'data' => new \ DateTime('now'),
                  'attr' => array('class' => 'form-control')))
            ->add('tipoTransaccion','entity',array('label' => 'Tipo de Transacción',
                                                          'mapped' => true,
                                                          'class' => 'lOroEntityBundle:TipoTransaccion',
                                                           'property' => 'nbTransaccion',
                                                           'empty_value' => $this->empty_value,
                                                           'attr' => array('class' => 'form-control')))                       
            ->add('nroReferencia','text',array('label' => 'Nro de Referencia',
                                                     'attr' => array('class' => 'form-control'))) 
                
            ->add('montoTransferencia','text',array('label' => 'Monto de la Transferencia',
                                                     'attr' => array('class' => 'form-control')))
                
            ->add('beneficiario','entity',array(            'label' => $labelProveedor,
            'class' => 'lOroEntityBundle:Proveedores',
            'query_builder' => function(EntityRepository $er) use ($compradorDolares) {
              return $er->createQueryBuilder('u')
                        ->where('u.compraDolares = :compradorDolares')
                        ->setParameter('compradorDolares',$compradorDolares);
            },
            'property' => 'nbProveedor',
            'empty_value' => 'Seleccione una Opción',
            'attr' => array('class' => 'form-control',
            'style' => 'margin-bottom:10px;'),
            'mapped'        => true))
                    
            ->add('estatus','choice',array('label' => 'Estatus de la Transferencia',
                                         'choices' => array('N' => 'No Enviada a HC','C' => 'Confirmada','P' => 'Pendiente'),
                                         'empty_value' => $this->empty_value,
                                         'mapped' => true,
                                         'attr' => array('class' => 'form-control')))  
                    
            ->add('empresa','text',array('label' => 'Empresa',
                                         'mapped' => true,
                                         'attr' => array('class' => 'form-control')))  
                    
                    
                    
            ->add('tipoMonedaConversion','entity',array('label' => 'Moneda de Origen (A descontar)',
                                              'class' => 'lOroEntityBundle:TiposMoneda',
                                              'query_builder' => function(EntityRepository $er) {
                                                    return $er->createQueryBuilder('u')
                                                              ->where('u.id NOT IN (:monedaExcluida)')
                                                              ->setParameter('monedaExcluida',array(1));
                                              },
                                                'property' => 'nbMoneda',
                                                'empty_value' => 'Seleccione una Opción',
                                                'attr' => array('class' => 'form-control',
                                                'style' => 'margin-bottom:10px;'),
                                                'mapped'        => true,
                                                'required' => false))
                                                      
            ->add('montoAConvertir','text',array('label' => 'Monto a Convertir',
                                                 'mapped' => 'true',
                                                 'required' => false,
                                                 'attr' => array('class' => 'form-control')))                                                      
                    
            ->add('esConversion','choice',array('label' => 'Es una conversion de dinero?',
                                                            'choices' => array(1 => 'Si', 0 => 'No'),
                'multiple' => false,
                'expanded' => true,
                'mapped' => 'true'
                ))                    
            ->add('cotizacionReferencia','text',array('label' => 'Cotizacion de la Divisa',
                                                      'required' => false,
                                                      'mapped' => 'true',
                                                     'attr' => array('class' => 'form-control')))       
                                                      
            ->add('tipoMonedaTransf','entity',array('label' => 'Tipo de Moneda del Destino (De la Transferencia)',
                                              'class' => 'lOroEntityBundle:TiposMoneda',
                                              'query_builder' => function(EntityRepository $er) {
                                                    return $er->createQueryBuilder('u')
                                                              ->where('u.id NOT IN (:monedaExcluida)')
                                                              ->setParameter('monedaExcluida',array(1));
                                              },
                                                'property' => 'nbMoneda',
                                                'empty_value' => 'Seleccione una Opción',
                                                'attr' => array('class' => 'form-control',
                                                'style' => 'margin-bottom:10px;'),
                                                'mapped'        => true))      
                                                      
            ->add('descripcion','text',array('label' => 'Descripcion',
                                             'mapped' => 'true',
                                                     'attr' => array('class' => 'form-control')))                                                        
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'lOro\EntityBundle\Entity\Transferencias'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'loro_entitybundle_transferencias';
    }
}
