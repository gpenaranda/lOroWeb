<?php

namespace lOro\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class VentasCierresProveedorType extends AbstractType
{
   protected $empty_value = 'Seleccione una Opción';
   
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $labelProveedor = 'Proveedor';
        
        
        $builder
            ->add('feDesde','date',array(
                  'label' => 'Desde',
                  'widget' => 'single_text',
                  'format' => 'dd-MM-yyyy',
                  'mapped' => false,
                  'attr' => array('class' => 'form-control')))
            ->add('feHasta','date',array(
                  'label' => 'Hasta',
                  'widget' => 'single_text',
                  'format' => 'dd-MM-yyyy',
                  'mapped' => false,
                  'attr' => array('class' => 'form-control')))
            ->add('proveedor','entity',array('label' => $labelProveedor,
                                             'class' => 'lOroEntityBundle:Proveedores',
                                             'query_builder' => function(EntityRepository $er) {
                                                      return $er->createQueryBuilder('u');
                                                                //->where('u.compraDolares = :compradorDolares')
                                                                //->setParameter('compradorDolares',$compradorDolares);
                                             },
                                             'property' => 'nbProveedor',
                                             'empty_value' => $this->empty_value,
                                             'mapped' => false,
                                             'attr' => array('class' => 'form-control')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'lOro\EntityBundle\Entity\Proveedores'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'loro_seleccionar_proveedores_form';
    }
}

