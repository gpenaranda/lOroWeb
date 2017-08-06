<?php

namespace lOro\PagosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PagosMinoristasType extends AbstractType
{
    
    protected $empty_value = 'Seleccione una Opción';
    protected $proveedor_seleccionado = null;
    
    public function __construct($proveedor = null) 
    {
      $this->proveedor_seleccionado = $proveedor;
    }
    
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $compradorDolares = FALSE;
       
        $builder
            ->add('fePago','date',array(
                  'label' => 'Fecha del Pago',
                  'widget' => 'single_text',
                  'format' => 'dd-MM-yyyy',
                  'attr' => array('class' => 'form-control')))
            ->add('montoPagado','text',array('label' => 'Monto del Pago',
                                                     'attr' => array('class' => 'form-control')))
            ->add('tipoTransaccion','entity',array('label' => 'Tipo de Transacción',
                                                          'mapped' => true,
                                                          'class' => 'lOroEntityBundle:TipoTransaccion',
                                                           'property' => 'nbTransaccion',
                                                           'empty_value' => $this->empty_value,
                                                           'attr' => array('class' => 'form-control')))  
            ->add('tipoPago','choice',array('label' => 'Tipo de Pago',
                                                          'mapped' => true,
                                                          'choices' => array('B' => 'Bolivares', 'E' => 'Euros'),
                                                           'empty_value' => $this->empty_value,
                                                           'attr' => array('class' => 'form-control')))                
            ->add('empresaCasa','entity',array('label' => 'Pagado por la Empresa',
                                                          'class' => 'lOroEntityBundle:EmpresasProveedores',
                                                          'query_builder' => function(EntityRepository $er) {
                                                            return $er->createQueryBuilder('u')
                                                                      ->where('u.esEmpresaCasa = TRUE');
                                                           },                
                                                           'property' => 'nombreEmpresa',
                                                'multiple' => false,
                                                'expanded' => false,
                                                'empty_value' => $this->empty_value,
                                                'attr' => array('class' => 'form-control')))   
            ->add('banco','entity',array('label' => 'Banco',
                                                          'class' => 'lOroEntityBundle:Bancos',
                                                          'property' => 'nbBanco',
                                                'multiple' => false,
                                                'expanded' => false,
                                                'empty_value' => $this->empty_value,
                                                'attr' => array('class' => 'form-control')))                                                                    
            ->add('nroReferencia','text',array('label' => 'Nro de Referencia',
                                                     'attr' => array('class' => 'form-control')))             
            ->add('proveedor','entity',array('label' => 'Proveedor',
                                                          'mapped' => false,
                                                          'class' => 'lOroEntityBundle:Proveedores',
                                                          'query_builder' => function(EntityRepository $er) {
                                                            return $er->createQueryBuilder('u')
                                                                      ->where('u.tipoProveedor = 2');
                                                           },                
                                                           'property' => 'nbProveedor',
                                                           'empty_value' => $this->empty_value,
                                                           'attr' => array('class' => 'form-control')))
            ->add('empresaPago','entity',array('label' => 'Empresas del Proveedor',
                                                          'class' => 'lOroEntityBundle:EmpresasProveedores',
                                                          'query_builder' => function(EntityRepository $er) {
                                                            return $er->createQueryBuilder('u')
                                                                      ->where('u.esEmpresaCasa = FALSE');
                                                           },                
                                                           'property' => 'nombreEmpresa',
                                                'multiple' => false,
                                                'expanded' => false,
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
            'data_class' => 'lOro\EntityBundle\Entity\PagosMinoristas'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'loro_entitybundle_pagos_proveedores';
    }
}

