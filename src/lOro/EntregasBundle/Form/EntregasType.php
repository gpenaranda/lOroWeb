<?php

namespace lOro\EntregasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use lOro\EntregasBundle\Form\PiezasType;


class EntregasType extends AbstractType
{
   private $seleccione_opcion = 'Seleccione una Opción';
   
   private $clasesCssGenerales = "form-control";
   
   private $em;
   private $esEdicion;
   

   
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $this->em = $options['em'];
      $this->esEdicion = $options['esEdicion'];

        $opcionesFeEntrega =  array(
                  'label' => 'Fecha de la Entrega',
                  'widget' => 'single_text',
                  'format' => 'dd-MM-yyyy',
                  'attr' => array('class' => $this->clasesCssGenerales));
        
        if($this->esEdicion != TRUE):
          $opcionesFeEntrega['data'] = new \ DateTime('now');    
        endif;
        
        
        
        $builder
            ->add('feEntrega',DateType::class,$opcionesFeEntrega)
            ->add('proveedor',EntityType::class,array('class' => 'lOroEntityBundle:Proveedores',
                                             'query_builder' => function(EntityRepository $er) {
                                                      return $er->createQueryBuilder('u')
                                                                ->where('u.tipoProveedor = 1');
                                             },
                                             'choice_label' => 'nbProveedor',
                                             'placeholder' => $this->seleccione_opcion,
                                             'attr' => array('class' => $this->clasesCssGenerales)))   
            ->add('tipoMonedaEntrega',EntityType::class,array('label' => 'Tipo de Moneda para el Cierre',
                                                   'class' => 'lOroEntityBundle:TiposMoneda',
                                                   'query_builder' => function(EntityRepository $er) {
                                                      return $er->createQueryBuilder('u')
                                                                ->where('u.id IN (2,3)');
                                                   },
                                                   'choice_label' => 'nbMoneda',
                                                   'mapped' => true,
                                                   'placeholder' => $this->seleccione_opcion,
                                                   'attr' => array('class' => $this->clasesCssGenerales,
                                                   'style' => 'margin-bottom:10px;')))                                                         
            ->add('piezasEntregadas',CollectionType::class, array(
                                                  'label' => ' ',
                                                  'entry_type' => PiezasType::class,
                                                  'entry_options' => array(
                                                                  'em' => $this->em,
                                                                  'esEdicion' => $this->esEdicion
                                                                       ),
                                                  'allow_add'    => true,
                                                  'allow_delete' => true,
                                                  'by_reference' => false,
                                                  ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => 'lOro\EntityBundle\Entity\Entregas',
          'em' => null,
          'esEdicion' => null
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'loro_entitybundle_entregas';
    }
}
