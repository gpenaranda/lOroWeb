<?php

namespace lOro\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MargenesGananciasType extends AbstractType
{
   private $opciones_estatus = array('A' => 'Activo', 'I' => 'Inactivo');
   
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
       //     ->add('nbMargenGanancia')
            ->add('tipoMargen','text',array('label' => 'Nombre de la Empresa',
                                               'attr' => array('class' => 'form-control')))
      //      ->add('estatus','choice',array(
       //           'choices'   => $this->opciones_estatus,))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'lOro\EntityBundle\Entity\MargenesGanancias'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'loro_entitybundle_margenesganancias';
    }
}
