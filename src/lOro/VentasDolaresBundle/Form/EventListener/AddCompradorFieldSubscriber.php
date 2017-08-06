<?php

namespace lOro\VentasDolaresBundle\Form\EventListener;
 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityRepository;

 
class AddCompradorFieldSubscriber implements EventSubscriberInterface
{
    private $propertyPathToComprador;
    private $compradorDolares;
    
    public function __construct($propertyPathToComprador,$compradorDolares)
    {
        $this->propertyPathToComprador = $propertyPathToComprador;
        $this->compradorDolares = $compradorDolares;
    }
 
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT   => 'preSubmit'
        );
    }
 
    private function addCompradorForm($form, $comprador = null)
    {
        $compradorDolares = $this->compradorDolares;
        $labelProveedor = ($compradorDolares == TRUE ? 'Comprador' : 'Proveedor');
        
        $formOptions = array(
            'label' => $labelProveedor,
            'class' => 'lOroEntityBundle:Proveedores',
            'query_builder' => function(EntityRepository $er) use ($compradorDolares) {
              return $er->createQueryBuilder('u')
                        ->where('u.compraDolares = :compradorDolares')
                        ->setParameter('compradorDolares',$compradorDolares);
            },
            'property' => 'nbProveedor',
            'empty_value' => 'Seleccione una Opción',
            'attr' => array('class' => 'form-control',
            'style' => 'margin-bottom:10px;'),
            'mapped'        => true,
        );
 
        if ($comprador) {
            $formOptions['data'] = $comprador;
        }
 
        $form->add('comprador', 'entity', $formOptions);
    }
 
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) {
            return;
        }
 
        $accessor = PropertyAccess::getPropertyAccessor();
 
        $entidad    = $accessor->getValue($data, $this->propertyPathToComprador);
        $comprador = ($entidad) ? $entidad->getProveedor() : null;

        $this->addCompradorForm($form, $comprador);
    }
 
    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
 
        $this->addCompradorForm($form);
    }
}