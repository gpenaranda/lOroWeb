<?php

namespace lOro\VentasCierreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class VentasCierresType extends AbstractType
{
    
    private $seleccione_opcion = 'Seleccione una Opción';
    private $tipoAccion;
    private $promReferencia;
    private $em;
    


   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->em = $options['em']; 
        $this->tipoAccion = $options['tipoAccion'];  
        $this->promReferencia = number_format($options['promReferencia'],'2',',','.');
       

        $attrFecha['label'] = 'Fecha del Cierre';
        $attrFecha['widget'] = 'single_text';
        $attrFecha['format'] = 'dd-MM-yyyy';
        $attrFecha['attr'] = array('class' => 'form-control');

        if($this->tipoAccion):
          $attrDolarRefDia = array('label' => 'Promedio Referencia Día',
                                    'attr' => array('class' => 'form-control'),
                              );
        else:
          $attrFecha['data'] = new \ DateTime('now');   

          $attrDolarRefDia = array('label' => 'Promedio Referencia Día',
                                    'attr' => array('class' => 'form-control'),
                                    'data' => $this->promReferencia
                              );        
        endif;
        
        $builder
            ->add('feVenta',DateType::class,$attrFecha)
            ->add('dolarReferenciaDia',TextType::class,$attrDolarRefDia)   
                                                     
            ->add('tipoMonedaCierre',EntityType::class,array('label' => 'Tipo de Moneda para el Cierre',
                                                   'class' => 'lOroEntityBundle:TiposMoneda',
                                                   'query_builder' => function(EntityRepository $er) {
                                                      return $er->createQueryBuilder('u')
                                                                ->where('u.id IN (1,2,3)');
                                                   },
                                                   'choice_label' => 'nbMoneda',
                                                   'placeholder' => $this->seleccione_opcion,
                                                   'attr' => array('class' => 'form-control',
                                                   'mapped'        => true,
                                                   'style' => 'margin-bottom:10px;')))                
            ->add('cantidadTotalVenta',TextType::class,array('label' => 'Peso Total (Gr.)',
                                                     'attr' => array('class' => 'form-control')))
            ->add('valorOnza',TextType::class,array('label'=> 'Valor de la Onza',
                                           'attr' => array('class' => 'form-control')))              
            ->add('montoBsCierrePorGramo',TextType::class,array('label' => 'Monto Pagado x Gr.',
                                               'attr' => array('class' => 'form-control')))
            ->add('proveedorCierre',EntityType::class,array('label' => 'Proveedor',
                                                   'class' => 'lOroEntityBundle:Proveedores',
                                                   'query_builder' => function(EntityRepository $er) {
                                                      return $er->createQueryBuilder('u')
                                                                ->where('u.tipoProveedor = 1');
                                                   },
                                                   'choice_label' => 'nbProveedor',
                                                   'placeholder' => '',
                                                   'attr' => array('class' => 'form-control',
                                                   'style' => 'margin-bottom:10px;')))
        ;
    }
    

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'loro_entitybundle_ventascierres';
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'lOro\EntityBundle\Entity\VentasCierres',
            'em' => null,
            'tipoAccion' => null,
            'promReferencia' => null
        ));
    }    
}
