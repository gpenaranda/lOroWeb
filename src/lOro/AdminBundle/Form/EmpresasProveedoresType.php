<?php

namespace lOro\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\EntityRepository;

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
            ->add('proveedor',EntityType::class,array('label' => 'Proveedor',
                                             'class' => 'lOroEntityBundle:Proveedores',
                                             'query_builder' => function(EntityRepository $er) {
                                                      return $er->createQueryBuilder('u')
                                                                ->where('u.tipoProveedor IN (1,2)');
                                              },
                                             'group_by' => function($choiceValue, $key, $value) {
                                                    if ($choiceValue->getTipoProveedor()->getId() == 1) {
                                                        return 'MAYORISTAS';
                                                    } elseif ($choiceValue->getTipoProveedor()->getId() == 2)  {
                                                        return 'MINORISTAS';
                                                    }
                                             },
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
