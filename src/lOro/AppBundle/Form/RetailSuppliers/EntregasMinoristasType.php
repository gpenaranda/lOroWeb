<?php

namespace lOro\AppBundle\Form\RetailSuppliers;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EntregasMinoristasType extends AbstractType
{
   private $seleccione_opcion = 'Seleccione una Opción';
   
   private $clasesCssGenerales = "form-control inputs-prueba";
   
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
        
        $opcionesLey = array('label' => 'Ley',
                             'attr' => array('class' => 'form-control'));
        
        if($this->esEdicion != TRUE):
          $opcionesFeEntrega['data'] = new \ DateTime('now');
          $opcionesLey['data'] = number_format(700,2,',','.');
        endif;

        
        
        
        $builder
            ->add('feEntrega',DateType::class,$opcionesFeEntrega)
            ->add('minorista',EntityType::class,array('label' => 'Minorista',
                                             'class' => 'lOroEntityBundle:Proveedores',
                                             'query_builder' => function(EntityRepository $er) {
                                                      return $er->createQueryBuilder('u')
                                                                ->where('u.tipoProveedor = 2');
                                             },
                                             'choice_label' => 'nbProveedor',
                                             'placeholder' => '',
                                             'attr' => array('class' => 'form-control')))               
            ->add('pesoBrutoEntrega',TextType::class,array('label' => 'Peso Bruto de Entrega (Gr.)',
                                                  'attr' => array('class' => 'form-control')))
            ->add('ley',TextType::class,$opcionesLey)
            ->add('pesoPuroEntrega',TextType::class,array('label' => 'Peso Puro de Entrega (Gr.)',
                                                 'attr' => array('class' => 'form-control',
                                                                 'style' => 'margin-bottom:10px;',
                                                                 'readonly' => true)
                                                 ))
            ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => 'lOro\AppBundle\Entity\EntregasMinoristas',
          'em' => null,
          'esEdicion' => null
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'loro_appbundle_entregasminoristas';
    }
}