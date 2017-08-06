<?php

namespace lOro\EntregasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class EntregasHcType extends AbstractType
{
   private $clasesCssGenerales = "form-control inputs-prueba";
   
   private $em;
   private $esEdicion;
   
   public function __construct($em = null,$esEdicion = null){
     $this->em = $em;    
     $this->esEdicion = $esEdicion;  
   }
   
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $opcionesFeEntrega =  array(
                  'label' => 'Fecha de la  Entrega',
                  'widget' => 'single_text',
                  'format' => 'dd-MM-yyyy',
                  'attr' => array('class' => $this->clasesCssGenerales));
        
        if($this->esEdicion != TRUE):
          $opcionesFeEntrega['data'] = new \ DateTime('now');    
        endif;
        
        
        
        $builder
            ->add('feEntrega','date',$opcionesFeEntrega)
            ->add('codPiezaInicial','text',array('label' => 'Pieza Inicial',
                                                 'attr' => array('class' => 'form-control')))
            ->add('codPiezaFinal','text',array('label' => 'Pieza Final',
                                                 'attr' => array('class' => 'form-control'))) 
            ;
    }
    
   /**
     * @return string
     */
    public function getName()
    {
        return 'loro_entitybundle_entregas_hc';
    }
}

