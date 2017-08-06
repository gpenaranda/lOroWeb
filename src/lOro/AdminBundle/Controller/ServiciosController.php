<?php

namespace lOro\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Response;

class ServiciosController extends Controller
{
  public function ajaxSacarPromRefAction() {
    $cantidadVentasTomadas = $_POST['cantidadVentasTomadas'];
    
    $promDolarReferencia = $this->get('loro_datos_generales')->generarPromDolaresReferencia($cantidadVentasTomadas);
    
    return new JsonResponse(number_format($promDolarReferencia,2,',','.'));
  }
}



