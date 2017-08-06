<?php

namespace lOro\LoginBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CambiarContrasenaType extends AbstractType
{
    
    
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', "Symfony\Component\Form\Extension\Core\Type\RepeatedType", array(
                      'type' => "Symfony\Component\Form\Extension\Core\Type\PasswordType",
                      'invalid_message' => 'La contraseña debe coincidir.',
                      'options' => array('attr' => array('class' => 'password-field form-control')),
                      'required' => true,
                      'first_options'  => array('label' => 'Nueva Contraseña'),
                      'second_options' => array('label' => 'Repetir Nueva Contraseña')))               
                      ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'loro_cambiar_contrasena_form';
    }
}
