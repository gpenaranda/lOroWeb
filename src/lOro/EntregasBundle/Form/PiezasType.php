<?php

namespace lOro\EntregasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;



class PiezasType extends AbstractType
{
   private $seleccione_opcion = 'Seleccione una Opción';
   
   private $clasesCssGenerales = "form-control inputs-prueba";
   
   private $em;
   private $esEdicion;
   

   
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $this->em = $options['em'];    
      $this->esEdicion = $options['esEdicion']; 
        
        if($this->esEdicion != TRUE):
          $idUltimaPieza = $this->em->getRepository('lOroEntityBundle:Piezas')->buscarUltimoUltimaPiezaRegistrada();
          
          $arregloAtributosCodPieza = array('label' => 'Codigo de la Pieza',
                                              'data' => $idUltimaPieza['id_siguiente'],
                                              'required' => true,
                                              'attr' => array('class' => $this->clasesCssGenerales));
        else:
          $arregloAtributosCodPieza = array('label' => 'Codigo de la Pieza',
                                            'required' => true,
                                            'attr' => array('class' => $this->clasesCssGenerales));
        endif;
        
        $builder->add('codPieza',TextType::class,$arregloAtributosCodPieza)               
                ->add('pesoBrutoPieza',TextType::class,array('label' => 'Peso Bruto de la Pieza (Gr.)',
                                                    'required' => false,
                                                    'attr' => array('class' => $this->clasesCssGenerales.' numeros')))
                ->add('leyPieza',TextType::class,array('label' => 'Ley de la Pieza',
                                                     'attr' => array('class' => $this->clasesCssGenerales)))
                ->add('pesoPuroPieza',TextType::class,array('label' => 'Peso Puro de la Pieza (Gr.)',
                                                            'attr' => array('class' => $this->clasesCssGenerales,
                                                                            'required' => false,
                                                                            'style' => 'margin-bottom:10px;',
                                                                            'read_only' => true),
                                                            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'lOro\EntityBundle\Entity\Piezas',
            'em' => null,
            'esEdicion' => null
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'loro_entregasbundle_piezas';
    }
}
