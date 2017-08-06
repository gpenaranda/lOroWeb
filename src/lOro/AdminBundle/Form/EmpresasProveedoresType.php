<?php

namespace lOro\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EmpresasProveedoresType extends AbstractType
{
   protected $empty_value = 'Seleccione una Opción';
   protected $esEmpresaCasa;
   
   public function __construct($esEmpresaCasa = null) {
     $this->esEmpresaCasa = $esEmpresaCasa;
   }
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(!$this->esEmpresaCasa):
        $builder
            ->add('proveedor','entity',array('label' => 'Proveedor',
                                             'class' => 'lOroEntityBundle:Proveedores',
                                             'property' => 'nbProveedor',
                                             'empty_value' => $this->empty_value,
                                             'attr' => array('class' => 'form-control')));
        endif;
        $builder
            ->add('nombreEmpresa','text',array('label' => 'Nombre de la Empresa',
                                               'attr' => array('class' => 'form-control')))
            ->add('aliasEmpresa','text',array('label' => 'Alias de la Empresa',
                                               'attr' => array('class' => 'form-control')))
            ->add('tipoDocumento','entity',array('label' => 'Tipo de Documento',
                                             'class' => 'lOroEntityBundle:TiposDocumentos',
                                             'property' => 'nombre',
                                             'empty_value' => $this->empty_value,
                                             'attr' => array('class' => 'form-control')))
            
            ->add('rif','text',array('label' => 'Documento de Identificacion',
                                               'attr' => array('class' => 'form-control')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'lOro\EntityBundle\Entity\EmpresasProveedores'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'loro_entitybundle_empresasproveedores';
    }
}
