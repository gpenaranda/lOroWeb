<?php

namespace lOro\EntityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();

		/*************** INICIO DE XML ******************/
        $rootNode = new \SimpleXMLElement( "<?xml version='1.0' encoding='UTF-8' standalone='yes'?><balance-general></balance-general>" );


        /********** NODO ULTIMA PIEZA ENTREGADA ***********/
        	$ultimaPiezaEntregada = $em->getRepository('lOroEntityBundle:Piezas')->getUltimaPieza();
        	$rootNode->addChild( 'ultima-pieza-entregada',($ultimaPiezaEntregada ? $ultimaPiezaEntregada['codPieza'] : 'N/A'));
        /********** NODO ULTIMA PIEZA ENTREGADA ***********/


        /** CREACION DEL NODO PRINCIPAL DE LOS DATOS GENERALES **/
	        $generalDataNode = $rootNode->addChild('datos-generales');

	        /********** NODO DE BOLIVARES ***************/        
	        $balanceGeneralBolivares = $this->get('loro_datos_generales')->relacionBolivaresDolares();

			$balanceGeneral = $generalDataNode->addChild('bolivares');
			$balanceGeneral->addChild( 'acumulado',$balanceGeneralBolivares['balance_acumulado_bolivares']);
			$balanceGeneral->addChild( 'deuda-proveedores',$balanceGeneralBolivares['deuda_proveedores']);
			$balanceGeneral->addChild( 'deuda-minoristas',$balanceGeneralBolivares['deuda_minoristas']);
			/********** NODO DE BOLIVARES ***************/



	        /*************** NODO DE  MATERIAL ******************/
	        $balanceGramosCerradosEntregados = $em->getRepository('lOroEntityBundle:Balances')->buscarBalanceGramosCerradosEntregados();
	        
	        $totalEntregadoMes = $balanceGramosCerradosEntregados['gramos_entregados_mes'];
			$balanceTotalProveedores = $balanceGramosCerradosEntregados['balance_acumulado_proveedores'] + ($balanceGramosCerradosEntregados['gramos_cerrados_proveedores_mes'] - $totalEntregadoMes);
			$balanceTotalHc = $balanceGramosCerradosEntregados['balance_acumulado_hc'] + ($balanceGramosCerradosEntregados['gramos_cerrados_hc_mes'] - $totalEntregadoMes);

	        $balanceGeneral = $generalDataNode->addChild('material');
			$balanceGeneral->addChild( 'balance-hc', $balanceTotalHc);
			$balanceGeneral->addChild( 'balance-proveedores', $balanceTotalProveedores);
	        /*************** NODO DE  MATERIAL ******************/
		/** CREACION DEL NODO PRINCIPAL DE LOS DATOS GENERALES **/



		$response = new Response();
		$response->setContent($rootNode->asXML());
		$response->setStatusCode(Response::HTTP_OK);
		$response->headers->set('Content-Type', 'text/xml');
        return $response;
    }
}
