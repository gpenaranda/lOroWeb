<?php

namespace lOro\EntregasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EntregaInicialType extends AbstractType
{
   private $seleccione_opcion = 'Seleccione una Opción';
   
   private $clasesCssGenerales = "form-control";
   
   private $em;

   
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $this->em = $options['em']; 
        

      $builder
            ->add('feEntrega',DateType::class,array('label' => 'Fecha de la Entrega',
                                                    'widget' => 'single_text',
                                                    'format' => 'dd-MM-yyyy',
                                                    'attr' => array('class' => $this->clasesCssGenerales),
                                                    'data' => new \ DateTime('now')))   
            ->add('tipoMonedaEntrega',EntityType::class,array('label' => 'Tipo de Moneda para el Cierre',
                                                   'class' => 'lOroEntityBundle:TiposMoneda',
                                                   'query_builder' => function(EntityRepository $er) {
                                                      return $er->createQueryBuilder('u')
                                                                ->where('u.id IN (2,3)');
                                                   },
                                                   'choice_label' => 'nbMoneda',
                                                   'data' => $this->em->getRepository('lOroEntityBundle:TiposMoneda')->find(1),
                                                   'placeholder' => $this->seleccione_opcion,
                                                   'attr' => array('class' => $this->clasesCssGenerales,
                                                   'style' => 'margin-bottom:10px;')))         
            ->add('proveedor',EntityType::class,array('class' => 'lOroEntityBundle:Proveedores',
                                             'query_builder' => function(EntityRepository $er) {
                                                      return $er->createQueryBuilder('u')
                                                                ->where('u.tipoProveedor = 1');
                                             },
                                             'choice_label' => 'nbProveedor',
                                             'placeholder' => $this->seleccione_opcion,
                                             'attr' => array('class' => $this->clasesCssGenerales)))                                                                                       
            ->add('piezaInicial',TextType::class, array(
                                                  'label' => 'Pieza Inicial',
                                                'mapped' => false,
                                                'attr' => array('class' => $this->clasesCssGenerales)
                                                  ))
           ->add('piezaFinal',TextType::class, array(
                                                  'label' => 'Pieza Final',
                                                  'mapped' => false,
                                                  'attr' => array('class' => $this->clasesCssGenerales)
                                                  ))
        ;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'loro_entrega_inicial';
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'em' => null
        ));
    }
}