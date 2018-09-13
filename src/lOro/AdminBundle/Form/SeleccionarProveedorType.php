<?php

namespace lOro\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class SeleccionarProveedorType extends AbstractType
{
   protected $empty_value = 'Seleccione una Opción';
   //protected $compradorDolares;
   
    
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    { 
        $compradorDolares = $options['compradorDolares'];
        $labelProveedor = ($compradorDolares  == TRUE ? 'Comprador' : 'Proveedor');
        
        
        $builder
            ->add('proveedor',EntityType::class,array('label' => $labelProveedor,
                                             'class' => 'lOroEntityBundle:Proveedores',
                                             'query_builder' => function(EntityRepository $er) use ($compradorDolares ) {
                                                      return $er->createQueryBuilder('u');
                                                                //->where('u.compraDolares = :compradorDolares')
                                                                //->setParameter('compradorDolares',$compradorDolares);
                                             },
                                             'choice_label' => 'nbProveedor',
                                             'placeholder' => $this->empty_value,
                                             'mapped' => false,
                                             'attr' => array('class' => 'form-control')))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'lOro\EntityBundle\Entity\Proveedores',
            'compradorDolares' => null
        ));
    } 

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'loro_seleccionar_proveedores_form';
    }
}
