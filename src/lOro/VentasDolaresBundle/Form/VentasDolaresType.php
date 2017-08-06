<?php

namespace lOro\VentasDolaresBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use lOro\VentasDolaresBundle\Form\EventListener\AddEmpresasCompradorSubscriber;
use lOro\VentasDolaresBundle\Form\EventListener\AddCompradorFieldSubscriber;


class VentasDolaresType extends AbstractType
{
    
   protected $seleccione_opcion = "Seleccione una Opción";
   protected $compradorDolares;
   
   public function __construct($compradorDolares) {
     $this->compradorDolares = $compradorDolares;    
   }   
    
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $propertyPathToComprador = "empresa";
       $compradorDolares = $this->compradorDolares;
       $labelProveedor = ($compradorDolares == TRUE ? 'Comprador' : 'Proveedor');
        
       $builder
            /* Estos Eventos son los relacionados al campo entidad */
            ->add('fechaVenta','date',array(
                  'label' => 'Fecha de la Venta',
                  'widget' => 'single_text',
                  'format' => 'dd-MM-yyyy',
                  'data' => new \ DateTime('now'),
                  'attr' => array('class' => 'form-control')))
            ->add('comprador','entity',array(            'label' => $labelProveedor,
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
            'mapped'        => true,))
            ->add('tipoMoneda','entity',array('label' => 'Moneda',
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
            'mapped'        => true,))
            ->add('cantidadDolaresComprados','number',array('label'  => 'Cantidad Comprada',
                                                            'attr'   => array('class' => 'form-control'),
                                                            'mapped' => true))
            ->add('cotizacionReferencia','text',array('label' => 'Cotizacion de la Divisa',
                                                      'required' => false,
                                                     'attr' => array('class' => 'form-control')))
            ->add('dolarReferencia','text',array('label' => 'Cambio de Referencia',
                                                     'attr' => array('class' => 'form-control')))
            ->add('montoVentaBolivares','text',array('label' => 'Monto de la Venta en Bolivares',
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
            'data_class' => 'lOro\EntityBundle\Entity\VentasDolares'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'loro_entitybundle_ventasdolares';
    }
}
