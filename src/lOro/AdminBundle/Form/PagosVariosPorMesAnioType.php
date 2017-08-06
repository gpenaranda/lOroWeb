<?php

namespace lOro\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PagosVariosPorMesAnioType extends AbstractType
{
   protected $empty_value = 'Seleccione una Opción';
   
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $labelProveedor = 'Proveedor';
        $arregloMeses = array(1 => 'Enero',
                              2 => 'Febrero',
                              3 => 'Marzo',
                              4 => 'Abril',
                              5 => 'Mayo',
                              6 => 'Junio',
                              7 => 'Julio',
                              8 => 'Agosto',
                              9 => 'Septiembre',
                              10 => 'Octubre',
                              11 => 'Noviembre',
                              12 => 'Diciembre',
                              999 => 'Todos los Meses'
                             );
        
        $arregloAnios = array(2014 => '2014',
                              2015 => '2015',
                              999 => 'Todos los Años'
                             );
        
        $builder
            ->add('anio','choice',array(
                  'label' => 'Año',
                  'choices' => $arregloAnios,
                  'mapped' => false,
                  'empty_value' => $this->empty_value,
                  'attr' => array('class' => 'form-control')))
            ->add('mes','choice',array(
                  'label' => 'Mes',
                  'choices' => $arregloMeses,
                  'mapped' => false,
                  'empty_value' => $this->empty_value,
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
