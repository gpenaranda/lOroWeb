<?php

namespace lOro\VentasDolaresBundle\Form\EventListener;
 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;
 
class AddEmpresasCompradorSubscriber implements EventSubscriberInterface
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
            FormEvents::PRE_SET_DATA  => 'preSetData',
            FormEvents::PRE_SUBMIT    => 'preSubmit'
        );
    }
 
    private function addEmpresasCompradorForm($form, $comprador)
    {
        $formOptions = array(
            'class'         => 'lOroEntityBundle:EmpresasProveedores',
            'empty_value'   => 'Seleccione una Opción',
            'label'         => 'Empresas',
            'required'      => true,
            'mapped'        => true,
            'attr'          => array(
                'class' => 'entidades_selector form-control',
            ),
            'query_builder' => function (EntityRepository $repository) use ($comprador) {
            
                $qb = $repository->createQueryBuilder('ei')
                                 ->where('ei.proveedor = :proveedor')
                                 ->setParameter('proveedor',$comprador)
                ;
                
                
                return $qb;
            }
        );
 
        $form->add($this->propertyPathToComprador, 'entity', $formOptions);
    }
    
    
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) {
            return;
        }
 
        $accessor    = PropertyAccess::createPropertyAccessor();
 
        $entidad = $accessor->getValue($data,$this->propertyPathToComprador);
        
        $comprador_id = ($entidad) ? $entidad->getProveedor()->getId() : null;
 
        $this->addEmpresasCompradorForm($form, $comprador_id);
    }
 
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
        
        $comprador_id = array_key_exists('comprador', $data) ? $data['comprador'] : null;
        
        $this->addEmpresasCompradorForm($form, $comprador_id);
    }
}
