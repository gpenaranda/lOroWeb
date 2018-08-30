<?php

namespace lOro\AppBundle\Form\RetailSuppliers;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use lOro\AppBundle\Entity\RetailSupplierClosedDeals;

class ClosedDealsType extends AbstractType
{
   private $seleccione_opcion = 'Seleccione una Opción';
   private $fCurrencyPromedyDay;
   
   
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $fCurrencyPromedyDay = number_format($options['fCurrencyPromedyDay'],'2',',','.');
      

      $closedDealDateFieldOption['label'] = 'Fecha';
      $closedDealDateFieldOption['widget'] = 'single_text';
      $closedDealDateFieldOption['format'] = 'dd-MM-yyyy';
      $closedDealDateFieldOption['attr'] = array('class' => 'form-control');
      $closedDealDateFieldOption['data'] = ($options['isEdit'] == null ? new \DateTime('now') : '' );
      
      
      $fCurrencyPromReferenceFielOption['label'] = 'Promedio Referencia Día';
      $fCurrencyPromReferenceFielOption['attr'] = array('class' => 'form-control');
      $fCurrencyPromReferenceFielOption['data'] = ($options['isEdit'] == null ? $fCurrencyPromedyDay : '' );

      $builder  ->add('feCierre',DateType::class,$closedDealDateFieldOption)
                ->add('dolarReferenciaDia',TextType::class,$fCurrencyPromReferenceFielOption)                           
                ->add('valorOnzaReferencia',TextType::class,array('label'=> 'Referencia Valor de la Onza',
                                                                  'attr' => array('class' => 'form-control')))
                ->add('pesoBrutoCierre',TextType::class,array('label' => 'Peso Bruto del Cierre (Grs.)',
                                                              'attr' => array('class' => 'form-control')))
                ->add('ley',TextType::class,array('label' => 'Ley',
                                                  'attr' => array('class' => 'form-control')))
                ->add('tipoMonedaCierre',EntityType::class,array('label' => 'Tipo de Moneda para el Cierre',
                                                   'class' => 'lOroEntityBundle:TiposMoneda',
                                                   'query_builder' => function(EntityRepository $er) {
                                                      return $er->createQueryBuilder('u')
                                                                ->where('u.id IN (1,2,3)');
                                                   },
                                                   'choice_label' => 'nbMoneda',
                                                   'placeholder' => $this->seleccione_opcion,
                                                   'attr' => array('class' => 'form-control')
                                                   ))   
                ->add('pesoPuroCierre',TextType::class,array('label' => 'Peso Puro del Cierre (Grs.)',
                                                             'attr' => array('class' => 'form-control',
                                                                             'readonly' => true)))
                ->add('montoBsPorGramo',TextType::class,array('label' => 'Monto Pagado Bs. x Gr.',
                                                              'attr' => array('class' => 'form-control')))
                ->add('totalMontoBs',TextType::class,array('label' => 'Total Monto Bs',
                                                           'attr' => array('class' => 'form-control',
                                                                            'readonly' => true)))
                ->add('minorista',EntityType::class,array('label' => 'Minorista',
                                                          'class' => 'lOroEntityBundle:Proveedores',
                                                          'query_builder' => function(EntityRepository $er) {
                                                                               return $er->createQueryBuilder('u')
                                                                                         ->where('u.tipoProveedor = 2')
                                                                                         ->andWhere("u.status = 'A'");
                                                                             },
                                                          'choice_label' => 'nbProveedor',
                                                          'placeholder' => '',
                                                          'attr' => array('class' => 'form-control')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => RetailSupplierClosedDeals::class,
          'fCurrencyPromedyDay' => null,
          'isEdit' => null
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'loro_appbundle_rsuppliers_closed_deals';
    }
}