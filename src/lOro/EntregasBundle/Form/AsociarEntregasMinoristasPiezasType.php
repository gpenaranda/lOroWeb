<?php

namespace lOro\EntregasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class AsociarEntregasMinoristasPiezasType extends AbstractType
{
   protected $empty_value = 'Seleccione una Opción';
   protected $compradorDolares;
   
   public function __construct() {
   }
    
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $labelProveedor = 'Piezas OFICINA';
        
        $builder->add('piezas','entity',array('label' => $labelProveedor,
                                             'class' => 'lOroEntityBundle:Piezas',
                                             'query_builder' => function(EntityRepository $er) {
                                                      return $er->createQueryBuilder('u')
                                                                ->join('u.entrega', 'e')
                                                                ->where('e.proveedor = :proveedorId')
                                                                ->andWhere('u.piezaAsociada = :piezaAsociada')
                                                                ->setParameter('proveedorId',20)
                                                                ->setParameter('piezaAsociada',FALSE)
                                                                ;
                                             },
                                             'property' => 'selectPieza',
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

