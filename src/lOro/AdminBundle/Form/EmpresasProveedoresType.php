<?php

namespace lOro\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Doctrine\ORM\EntityRepository;

class EmpresasProveedoresType extends AbstractType
{
   protected $empty_value = 'Select an option';
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
                                             'choice_label' => 'nbProveedor',
                                             'placeholder' => $this->empty_value,
                                             'attr' => array('class' => 'form-control')));
        endif;


        $builder
            ->add('nombreEmpresa',TextType::class,array('label' => 'Enterprise name',
                                               'attr' => array('class' => 'form-control')))
            ->add('aliasEmpresa',TextType::class,array('label' => 'Enterprise alias',
                                               'attr' => array('class' => 'form-control')))
            ->add('tipoDocumento',EntityType::class,array('label' => 'Document type',
                                             'class' => 'lOroEntityBundle:TiposDocumentos',
                                             'choice_label' => 'nombre',
                                             'placeholder' => $this->empty_value,
                                             'attr' => array('class' => 'form-control')))
            
            ->add('rif',TextType::class,array('label' => 'Identification document',
                                               'attr' => array('class' => 'form-control')))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'lOro\EntityBundle\Entity\EmpresasProveedores'
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'loro_entitybundle_empresasproveedores';
    }
}
