<?php

namespace lOro\PagosBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use lOro\PagosBundle\Form\PagosMinoristasType;
use lOro\TransferenciasBundle\Form\UploadFileType;

use lOro\EntityBundle\Entity\PagosMinoristas;
use lOro\EntityBundle\Entity\Banco;
use lOro\EntityBundle\Entity\Balances;
use lOro\EntityBundle\Entity\EmpresasProveedores;




/**
 * Pagos a Minoristas controller.
 *
 */
class PagosMinoristasController extends Controller
{

    
    /**
     * Lists all Transferencias entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $pagosMinoristas = $em->getRepository('lOroEntityBundle:PagosMinoristas')->findAll();
        
        $data['pagosMinoristas'] = $pagosMinoristas;
        return $this->render('lOroPagosBundle:PagosMinoristas:index.html.twig', $data);
    }
    
    
/**
     * Displays a form to create a new Transferencias entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new PagosMinoristas();
        $form   = $this->createCreateForm($entity);
        $formUpload   = $this->createUploadForm();
        
        if($request->isMethod('POST')):

          /* Carga de Archivo Minoristas TipoProveedor ID 2 */
          $datosArchivoPdf = $this->get('loro_datos_generales')->getUploadedFile($request,$form,2);
          
          if($datosArchivoPdf && $datosArchivoPdf['error'] == false):
              
            $montoPagado = str_replace(',','.',str_replace('.','',$datosArchivoPdf['montoPagado']));
          
            $form->get('nroReferencia')->setData($datosArchivoPdf['nroReferencia']);
            $form->get('tipoPago')->setData('B');
            $form->get('proveedor')->setData($datosArchivoPdf['proveedor']);
            $form->get('montoPagado')->setData($montoPagado);
            $form->get('fePago')->setData($datosArchivoPdf['fechaPago']);
            $form->get('tipoTransaccion')->setData($datosArchivoPdf['tipoTransaccion']);
            $form->get('empresaCasa')->setData($datosArchivoPdf['objEmpresaPago']);
          else:
            return $this->redirect($this->generateUrl('pagos_minoristas_new'));   
          endif;
          //var_dump($datosArchivoPdf);
        endif;
        
        return $this->render('lOroPagosBundle:PagosMinoristas:new.html.twig', array(
              'entity' => $entity,
              'form'   => $form->createView(),
              'formUpload' => $formUpload->createView(),
              'pdf' => (isset($datosArchivoPdf) ? $datosArchivoPdf['pdf'] : null),
              'nbArchivo' => (isset($datosArchivoPdf) ? $datosArchivoPdf['nbArchivo'] : null),
              'nbProveedor' => (isset($datosArchivoPdf) ? $datosArchivoPdf['nbProveedor'] : null)
        ));
    }   
    
    
    /**
     * Creates a new Transferencias entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new PagosMinoristas();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
            
        
        if ($form->isValid()) {
            
            $nroReferencia = $form->get('nroReferencia')->getData();
            
            $transferenciaRealizada = $em->getRepository('lOroEntityBundle:PagosMinoristas')->findOneBy(array('nroReferencia' => $nroReferencia));
            
            if($transferenciaRealizada):
              $this->get('session')->getFlashBag()->set('error', 'El pago N° '.$nroReferencia.' ya ha sido registrado en el sistema.');
              return $this->redirect($this->generateUrl('pagos_minoristas_new')); 
            endif;
            
            $feRegistro = new \DateTime('now');
            $usuarioRegistrador = $this->getUser();
            
            $entity->setFeRegistro($feRegistro);
            $entity->setUsuarioRegistrador($usuarioRegistrador);
            
            $empresaProveedor = $form->get('empresaPago')->getData();
            $tipoTransaccion = $form->get('tipoTransaccion')->getData();
            

            
            $entity->setEmpresaPago($empresaProveedor);
            $entity->setTipoTransaccion($tipoTransaccion);
            $em->persist($entity);
            $em->flush();

            
            return $this->redirect($this->generateUrl('pagos_minoristas'));
        }

        return $this->render('lOroPagosBundle:PagosMinoristas:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }    
    
/**
    * Creates a form to create a Transferencias entity.
    *
    * @param Transferencias $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(PagosMinoristas $entity)
    {
        $form = $this->createForm(new PagosMinoristasType(), $entity, array(
            'action' => $this->generateUrl('pagos_minoristas_create'),
            'method' => 'POST',
            'attr' => array('id' => 'form-pagos-proveedores')
        ));

        $form->add('submit', 'submit', array('label' => 'Agregar',
                                             'attr' => array('class' => 'btn btn-lg btn-success')));

        return $form;
    } 
    
    private function createUploadForm() {
      $form = $this->createForm(new UploadFileType(), null, array(
            'action' => $this->generateUrl('pagos_minoristas_new'),
            'method' => 'POST',
            'attr' => array('id' => 'form-pagos-proveedores')
        ));

        $form->add('submit', 'submit', array('label' => 'Cargar Archivo',
                                             'attr' => array('class' => 'btn btn-sm btn-success')));

        return $form;        
    }    
}

