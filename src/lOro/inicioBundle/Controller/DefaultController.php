<?php

namespace lOro\inicioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use lOro\inicioBundle\Form\BalancePorProveedorType;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data = array();
        $fechaActual = new \ DateTime('now');
      
        ini_set('memory_limit', '2048M');
        
                         
        /* Servicio para sacar el promedio de los dolares de referencia */
        $data['averageReferenceCurrency'] = $this->get('loro_datos_generales')->generarPromDolaresReferencia();
        
        
        /* Buscar la Ultima Pieza Entregada en el Sistema */
        $ultimaPiezaEntregada = $em->getRepository('lOroEntityBundle:Piezas')->getUltimaPieza();
        $data['ultimaPiezaEntregada'] = ($ultimaPiezaEntregada ? $ultimaPiezaEntregada['codPieza'] : 'N/A');

        /* Cantidad Cierres del Dia */
        $data['cantCierresDelDia'] = $this->get('loro_datos_generales')->cantCierresDelDia();
        
        

        
        return $this->render('lOroinicioBundle:Default:index.html.twig',$data);
    }
    

    /*
     Funcion mediante la cual se genera el balance en Bs y por Medio de AJAX se Presenta en el homepage
    */
    public function balanceBolivaresAction() {
      $data = array();

      $balanceGeneralBolivares = $this->get('loro_datos_generales')->relacionBolivaresDolares();

      
      $data['balanceAcumuladoBolivares'] = number_format($balanceGeneralBolivares['balance_acumulado_bolivares'],'2',',','.')." Bs.";
      $data['deudaConProveedores'] = number_format($balanceGeneralBolivares['deuda_proveedores'],'2',',','.')." Bs.";
      $data['deudaConMinoristas'] =  number_format($balanceGeneralBolivares['deuda_minoristas'],'2',',','.')." Bs.";  
      $totalBalance = ($balanceGeneralBolivares['balance_acumulado_bolivares'] - ($balanceGeneralBolivares['deuda_proveedores'] +  $balanceGeneralBolivares['deuda_minoristas']));
      $data['totalBalance'] = number_format($totalBalance,'2',',','.')." Bs.";

     return new JsonResponse($data);
    }

    /*
      Funcion que permite Generar los Datos del Balance de Material del Sistema y
      presentarlos mediante AJAX
    */
    public function balanceMaterialAction() {
      $data = array();

      $em = $this->getDoctrine()->getManager();

      
      $balanceGramosCerradosEntregados = $em->getRepository('lOroEntityBundle:Balances')->buscarBalanceGramosCerradosEntregados();

      /* Balance Proveedores */  
      $data['balanceAcummulatedGramsSuppliers'] = number_format($balanceGramosCerradosEntregados['balance_acumulado_proveedores'],'2',',','.')." Grs.";
      $data['closedGramsByMonthSuppliers'] = number_format($balanceGramosCerradosEntregados['gramos_cerrados_proveedores_mes'],'2',',','.')." Grs.";
      $data['gramsDeliveredByMonthSuppliers'] = number_format($balanceGramosCerradosEntregados['gramos_entregados_prov_mes'],'2',',','.')." Grs.";
      
      $balanceProveedoresMaterial = ($balanceGramosCerradosEntregados['balance_acumulado_proveedores'] + ($balanceGramosCerradosEntregados['gramos_cerrados_proveedores_mes'] - $balanceGramosCerradosEntregados['gramos_entregados_prov_mes']));
      $data['balanceMatSuppliers'] = number_format($balanceProveedoresMaterial,'2',',','.')." Grs.";
      /* Balance Proveedores */  
      
      /* Balance HC */
      $data['balanceAcummulatedGramsHc'] = number_format($balanceGramosCerradosEntregados['balance_acumulado_hc'],'2',',','.')." Grs.";
      $data['closedGramsByMonthHc'] = number_format($balanceGramosCerradosEntregados['gramos_cerrados_hc_mes'],'2',',','.')." Grs.";
      $data['gramsDeliveredByMonthHc'] = number_format($balanceGramosCerradosEntregados['gramos_entregados_hc_mes'],'2',',','.')." Grs.";

      $balanceHcMaterial = ($balanceGramosCerradosEntregados['balance_acumulado_hc'] + ($balanceGramosCerradosEntregados['gramos_cerrados_hc_mes'] - $balanceGramosCerradosEntregados['gramos_entregados_hc_mes']));
      $data['balanceMatHc'] = number_format($balanceHcMaterial,'2',',','.')." Grs.";
      /* Balance HC */

      return new JsonResponse($data);
    }

    /*
     Function that allows generate and display the data of each Supplier as a Balance
    */
    public function balanceBySuppliersAction() {
      $suppliersArray['balanceData'] = $this->get('loro_datos_generales')->getBalanceProveedoresGeneral();


      $totalsData = array();
      $totalsData['totalMat'] = 0;
      $totalsData['totalBs'] = 0;
      $totalsData['totalDol'] = 0;
      $totalsData['totalEu'] = 0;

      foreach($this->get('loro_datos_generales')->getBalanceProveedoresGeneral() as $supplierBalance):
        $totalsData['totalMat'] += $supplierBalance['rawDebtMat'];
        $totalsData['totalBs'] += $supplierBalance['rawDebtBs'];
        $totalsData['totalDol'] += $supplierBalance['rawDebtDol'];
        $totalsData['totalEu'] += $supplierBalance['rawDebtEu'];
      endforeach;

        $totalsData['totalMat'] = number_format($totalsData['totalMat'],'2',',','.')." Grs.";
        $totalsData['totalBs'] = number_format($totalsData['totalBs'],'2',',','.')." Bs.";
        $totalsData['totalDol'] = number_format($totalsData['totalDol'],'2',',','.')." $";
        $totalsData['totalEu'] = number_format($totalsData['totalEu'],'2',',','.')." €";

      $suppliersArray['totalsData'] = $totalsData;
      return new JsonResponse($suppliersArray);
    }

    /*
      Function that allows generate and display the data of each Retail Supplier as a Balance
    */
    public function balanceByRetailSuppliersAction() {

      /*************** BALANCE LISTADO MINORISTAS ******************/
      $data['arregloMinoristas'] = $this->get('loro_datos_generales')->getBalanceMinoristasGeneral();
      /*************** BALANCE LISTADO MINORISTAS ******************/
      
      return new JsonResponse($data['arregloMinoristas']);
    }

    public function cierresDelDiaAction() {
      $em = $this->getDoctrine()->getManager();
      
      $cierresDelDia = $em->getRepository('lOroEntityBundle:VentasCierres')->traerCierresDelDiaProveedores();
      
      if($cierresDelDia):
        $response = array();
        foreach($cierresDelDia as $row):
          $feVenta = $row->getFeVenta();
          $dataResponse['feVenta'] = $feVenta->format('d/m/y');
          $dataResponse['proveedor'] = $row->getProveedorCierre()->getNbProveedor();
          $dataResponse['cantidadTotalVenta'] = number_format($row->getCantidadTotalVenta(),2,',','.');
          $dataResponse['montoBsCierrePorGramo'] = number_format($row->getMontoBsCierrePorGramo(),2,',','.');
          $dataResponse['valorOnza'] = number_format($row->getValorOnza(),2,',','.');
          $dataResponse['dolarReferencia'] = number_format($row->getDolarReferencia(),2,',','.');
          
          $response[] = $dataResponse;
        endforeach;    
      else:
        $response = 'vacio';
      endif;
      
      return new JsonResponse($response);
    }    
    
    public function buscarInfoProveedorAction() {
      $request = $this->getRequest();
      $em = $this->getDoctrine()->getManager();
      
      $idProveedor = $request->get('idProveedor'); 
      
      $proveedor = $em->getRepository('lOroEntityBundle:Proveedores')->find($idProveedor);
      
      //Balance Activo
      $balanceActivo = $em->getRepository('lOroEntityBundle:Balances')->findOneBy(array('estatus' => 'A'));
      
      //Cierres por Proveedor
      $cierres = $this->generarArregloCierres($proveedor,$balanceActivo);
      
      if(!$cierres):
        $cierres = 'vacio';
      endif;
      
      //Entregas por el proveedor 
      $arregloEntregas = $this->generarArregloEntregas($proveedor,$balanceActivo);
      
      if(!$arregloEntregas):
        $arregloEntregas = 'vacio';
      endif;
      
      if($arregloEntregas != 'vacio'):
        $entregas = $arregloEntregas['arregloEntregas'];
      else:
        $entregas = $arregloEntregas;
      endif;
      
      $pesoTotalCierres = 0;
      foreach($cierres as $row):
        $pesoTotalCierres += $row['cantidadTotalVentaNoFormat'];   
      endforeach;
      
      $pesoTotalEntregas = 0;
      foreach($entregas as $row):
        $pesoTotalEntregas += $row['pesoPuroEntregaNoFormat'];
      endforeach;
      
      
      /*************/
      $arregloDetalleAdeudado = array();
      $totalDolaresAdeudados = 0;
      $totalBolivaresQueDimos = 0;
      $totalDolaresQueDariaHc = 0;
      /************/
      $totalInicialAdeudado = ($pesoTotalCierres - $pesoTotalEntregas);
      $totalAdeudado = ($pesoTotalCierres - $pesoTotalEntregas);
      
      foreach($cierres as $row):
        if($totalAdeudado > 0):
          $totalCierre = $row['cantidadTotalVentaNoFormat'];
          
          $totalAdeudado = ($totalAdeudado - $row['cantidadTotalVentaNoFormat']);
          $gramosTomados = $row['cantidadTotalVentaNoFormat'];
          $dolaresPorGramoProveedor = (($row['valorOnza']/31.1035)*0.95);
          $dolaresPorGramoHc = (($row['valorOnza']/31.1035)*0.97);
          
          $dolaresAdeudados = $row['montoDolaresNoFormat'];
          $bolivaresQueDimos = $row['montoBsCierreNoFormat'];
          $dolaresQueDariaHc = ((($row['valorOnza']/31.1035)*0.97) * $gramosTomados);
          
          if($totalAdeudado < 0):
            $gramosTomados = abs($totalAdeudado);
            $totalAdeudado = 0;
            
            $dolaresAdeudados = ((($row['valorOnza']/31.1035)*0.95) * $gramosTomados);
            $bolivaresQueDimos = ($gramosTomados * $row['montoBsCierrePorGramoNoFormat']);
            $dolaresQueDariaHc = ((($row['valorOnza']/31.1035)*0.97) * $gramosTomados);
          endif;
          
          $datosArreglo['feCierre'] = $row['feVenta'];
          $datosArreglo['gramosCerrados'] = $totalCierre;
          $datosArreglo['valorOnza'] = $row['valorOnza'];
          $datosArreglo['bsPorGramo'] = $row['montoBsCierrePorGramoNoFormat'];
          $datosArreglo['dolarReferencia'] = $row['dolarReferenciaCierre'];
          $datosArreglo['dolaresPorGramoProveedor'] = $dolaresPorGramoProveedor;
          $datosArreglo['dolaresPorGramoHc'] = $dolaresPorGramoHc;
          
          $datosArreglo['gramosTomados'] = $gramosTomados;
          $datosArreglo['dolaresAdeudados'] = $dolaresAdeudados;
          $datosArreglo['bolivaresQueDimos'] = $bolivaresQueDimos;
          $datosArreglo['dolaresQueDariaHc'] = $dolaresQueDariaHc;
          
          $arregloDetalleAdeudado[] = $datosArreglo;
          
          $totalDolaresAdeudados += $datosArreglo['dolaresAdeudados'];
          $totalBolivaresQueDimos += $datosArreglo['bolivaresQueDimos'];
          $totalDolaresQueDariaHc += $datosArreglo['dolaresQueDariaHc'];
        endif;   
      endforeach;      
      
      $arregloBalance['totalDolaresAdeudados'] = $totalDolaresAdeudados;
      $arregloBalance['totalBolivaresQueDimos'] = $totalBolivaresQueDimos;
      $arregloBalance['totalDolaresQueDariaHc'] = $totalDolaresQueDariaHc;
      $arregloBalance['totalAdeudado'] = $totalInicialAdeudado;
      $arregloBalance['nbProveedor'] = $proveedor->getNbProveedor();
      $arregloBalance['arregloDetalleAdeudado'] = $arregloDetalleAdeudado;
      
      /*****************************************************/
      $arregloBalance['entregas'] = $entregas;
      $arregloBalance['cierres'] = $cierres;
      /***************************************************/
     
      
      $arregloJson = json_encode($arregloBalance);
      
      return new Response($arregloJson);
    }
    
    protected function generarArregloEntregas($proveedor,$balanceActivo) {
      $em = $this->getDoctrine()->getManager();
      $arreglo = array();
      $sumatoriaPesoPuroEntregas = 0;
      $arregloFinal = array();
      
      $entregas = $em->getRepository('lOroEntityBundle:Entregas')->findBy(array('balance' => $balanceActivo,'proveedor' => $proveedor));
      
      if($entregas):
        
        foreach($entregas as $row):
          $datos['id'] = $row->getId();
          $datos['feEntrega'] = $row->getFeEntrega()->format('d-m-Y');
          $datos['pesoPuroEntrega'] = number_format($row->getPesoPuroEntrega(),2,',','.').' Grs.';
          $datos['pesoPuroEntregaNoFormat'] = $row->getPesoPuroEntrega();
                  
          $sumatoriaPesoPuroEntregas += $row->getPesoPuroEntrega();
          
         $arreglo[$row->getId()] = $datos;  
        endforeach;    
        $arregloFinal['arregloEntregas'] = $arreglo;
        $arregloFinal['sumatoriaPesoPuroEntrega'] = $sumatoriaPesoPuroEntregas;
      else:
        $arregloFinal = 'vacio';
      endif;
      
      
      return $arregloFinal;
        
    }

    protected function generarArregloCierres($proveedor,$balanceActivo) {
      $em = $this->getDoctrine()->getManager();
      $arregloCierres = array();
      
      $cierres = $em->getRepository('lOroEntityBundle:VentasCierres')->findBy(array('balance' => $balanceActivo,'proveedorCierre' => $proveedor),array('feVenta' => 'DESC'));
      
      if($cierres):
        foreach($cierres as $row):
          $datosCierres['id'] = $row->getId();
          $datosCierres['feVenta'] = $row->getFeVenta()->format('d-m-Y');
          $datosCierres['montoDolares'] = number_format($row->getMontoTotalDolar(),2,',','.');
          $datosCierres['montoBsFormula'] = number_format($row->getMontoBsFormula(),2,',','.');
          $datosCierres['montoBsCierre'] = number_format($row->getMontoBsCierre(),2,',','.');
          $datosCierres['cantidadTotalVenta'] = number_format($row->getCantidadTotalVenta(),2,',','.');
          $datosCierres['montoDolaresNoFormat'] = $row->getMontoTotalDolar();
          $datosCierres['montoBsFormulaNoFormat'] = $row->getMontoBsFormula();
          $datosCierres['montoBsCierreNoFormat'] = $row->getMontoBsCierre();
          $datosCierres['cantidadTotalVentaNoFormat'] = $row->getCantidadTotalVenta();
          $datosCierres['montoBsCierrePorGramo'] = number_format($row->getMontoBsCierrePorGramo(),2,',','.');
          $datosCierres['montoBsCierrePorGramoNoFormat'] = $row->getMontoBsCierrePorGramo();
          $datosCierres['valorOnza'] = $row->getValorOnza();
          $datosCierres['dolarReferenciaCierre'] = $row->getDolarReferencia();
          
         $arregloCierres[$row->getId()] = $datosCierres;  
        endforeach;    
      endif;
      
      return $arregloCierres;
    }
}
