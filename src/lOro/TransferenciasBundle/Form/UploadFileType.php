<?php

namespace lOro\TransferenciasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class UploadFileType extends AbstractType
{
       
   /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        
        $builder->add('inputFile', 'file');
    }
    

    /**
     * @return string
     */
    public function getName()
    {
        return 'loro_entitybundle_updload_files';
    }
}
