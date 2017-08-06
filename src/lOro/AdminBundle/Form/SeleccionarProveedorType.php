<?php

namespace lOro\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class SeleccionarProveedorType extends AbstractType
{
   protected $empty_value = 'Seleccione una Opción';
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
        $compradorDolares = TRUE;//$this->compradorDolares;
        $labelProveedor = ($compradorDolares == TRUE ? 'Comprador' : 'Proveedor');
        
        
        $builder
            ->add('proveedor','entity',array('label' => $labelProveedor,
                                             'class' => 'lOroEntityBundle:Proveedores',
                                             'query_builder' => function(EntityRepository $er) use ($compradorDolares) {
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
