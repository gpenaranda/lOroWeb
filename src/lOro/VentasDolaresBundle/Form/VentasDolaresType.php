<?php

namespace lOro\VentasDolaresBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use lOro\VentasDolaresBundle\Form\EventListener\AddEmpresasCompradorSubscriber;
use lOro\VentasDolaresBundle\Form\EventListener\AddCompradorFieldSubscriber;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class VentasDolaresType extends AbstractType
{
    
   protected $seleccione_opcion = "Seleccione una Opción";
   protected $compradorDolares;
   
   
    
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $this->compradorDolares = $options['compradorDolares'];

       $propertyPathToComprador = "empresa";
       $compradorDolares = $this->compradorDolares;
       $labelProveedor = ($compradorDolares == TRUE ? 'Comprador' : 'Proveedor');
        
       $builder
            /* Estos Eventos son los relacionados al campo entidad */
            ->add('fechaVenta',DateType::class,array('label' => 'Fecha de la Venta',
                                            'widget' => 'single_text',
                                            'format' => 'dd-MM-yyyy',
                                            'data' => new \ DateTime('now'),
                                            'attr' => array('class' => 'form-control')))
            ->add('comprador',EntityType::class,array('label' => $labelProveedor,
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
                                            'mapped'        => true,))
            ->add('tipoMoneda',EntityType::class,array('label' => 'Moneda',
                                              'class' => 'lOroEntityBundle:TiposMoneda',
                                              'query_builder' => function(EntityRepository $er) {
                                                return $er->createQueryBuilder('u')
                                                            ->where('u.id NOT IN (:monedaExcluida)')
                                                            ->setParameter('monedaExcluida',array(1));
                                              },
                                            'choice_label' => 'nbMoneda',
                                            'placeholder' => 'Seleccione una Opción',
                                            'attr' => array('class' => 'form-control',
                                            'style' => 'margin-bottom:10px;'),
                                            'mapped'        => true,))
            ->add('cantidadDolaresComprados',NumberType::class,array('label'  => 'Cantidad Comprada',
                                                            'attr'   => array('class' => 'form-control'),
                                                            'mapped' => true))
            ->add('cotizacionReferencia',NumberType::class,array('label' => 'Cotizacion de la Divisa',
                                                      'required' => false,
                                                     'attr' => array('class' => 'form-control')))
            ->add('dolarReferencia',NumberType::class,array('label' => 'Cambio de Referencia',
                                                     'attr' => array('class' => 'form-control')))
            ->add('montoVentaBolivares',NumberType::class,array('label' => 'Monto de la Venta en Bolivares',
                                                     'attr' => array('class' => 'form-control')))     
               
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'lOro\EntityBundle\Entity\VentasDolares',
            'compradorDolares' => null
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'loro_entitybundle_ventasdolares';
    }
}
