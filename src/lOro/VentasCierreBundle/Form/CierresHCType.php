<?php

namespace lOro\VentasCierreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class CierresHCType extends AbstractType
{
    
    private $seleccione_opcion = 'Seleccione una Opción';
    
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('feVenta',DateType::class,array(
                  'label' => 'Fecha del Cierre',
                  'widget' => 'single_text',
                  'format' => 'dd-MM-yyyy',
                  'data' => new \ DateTime('now'),
                  'attr' => array('class' => 'form-control'))
                 )
            ->add('cantidadTotalVenta',TextType::class,array('label' => 'Peso Total (Gr.)',
                                                     'attr' => array('class' => 'form-control')))
            ->add('valorOnza',TextType::class,array('label'=> 'Valor de la Onza',
                                           'attr' => array('class' => 'form-control')))
            ->add('montoTotalDolar',TextType::class,array('label' => 'Monto Total (por formula)',
                                                 'attr' => array('class' => 'form-control',
                                                   'style' => 'margin-bottom:10px;',
                                                    'read_only' => true)))
            ->add('tipoMonedaCierre',EntityType::class,array('label' => 'Tipo de Moneda para el Cierre',
                                              'class' => 'lOroEntityBundle:TiposMoneda',
                                              'query_builder' => function(EntityRepository $er) {
                                                    return $er->createQueryBuilder('u')
                                                              ->where('u.id NOT IN (:monedaExcluida)')
                                                              ->setParameter('monedaExcluida',array(1));
                                              },
                                                'choice_label' => 'nbMoneda',
                                                'placeholder' => '',
                                                'attr' => array('class' => 'form-control',
                                                'style' => 'margin-bottom:10px;'),
                                                'mapped'        => true,
                                                'required' => false))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'lOro\EntityBundle\Entity\VentasCierres'
        ));
    } 

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'loro_entitybundle_cierres_hc';
    }
}


