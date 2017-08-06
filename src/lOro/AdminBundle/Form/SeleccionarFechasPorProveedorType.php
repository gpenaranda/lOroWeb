<?php

namespace lOro\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class SeleccionarFechasPorProveedorType extends AbstractType
{
   protected $empty_value = 'Seleccione una Opción';
   protected $compradorDolares;
   protected $em;
   
   public function __construct($em = null) {
     $this->em = $em;
   }
    
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $compradorDolares = TRUE;//$this->compradorDolares;
        $labelProveedor = 'Proveedor';
        $arregloProveedores = array();
        
        $proveedores = $this->em->getRepository('lOroEntityBundle:Proveedores')->findAll();
        
        if($proveedores):
          
          foreach($proveedores as $row):
           
            $arrayProveedores[$row->getId()] = ucfirst(strtolower($row->getNbProveedor()));
          endforeach;
          $arrayProveedores[9999] = 'Todos los Proveedores';
        endif;
        
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
            ->add('proveedor','choice',array('label' => $labelProveedor,
                                             'choices' => $arrayProveedores,
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

