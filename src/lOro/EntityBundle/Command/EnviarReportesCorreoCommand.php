<?php

namespace lOro\EntityBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class EnviarReportesCorreoCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('textileslolo:enviar-reportes')
            ->setDescription('Enviar correos con los Reportes')
            ->addArgument(
                'tipoReporte',
                InputArgument::OPTIONAL,
                'El nombre del reporte que desea que sea enviado'
            )
            ->addArgument(
                'feDesde',
                InputArgument::OPTIONAL,
                'Fecha de inicio del reporte (yyyy-dd-mm)'
            )
            ->addArgument(
                'feHasta',
                InputArgument::OPTIONAL,
                'Fecha final del reporte (yyyy-dd-mm)'
            )                
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $reporte = $input->getArgument('tipoReporte');
      $feDesde = ($input->getArgument('feDesde') ? $input->getArgument('feDesde') : null);
      $feHasta = ($input->getArgument('feHasta') ? $input->getArgument('feHasta') : null);
      
      
      
      $output->writeln('<comment>Enviando los correos para el reporte '.$reporte.'</comment>');
      
      $resultadoEnvio = $this->generarReporteSolicitado($reporte,$feDesde,$feHasta);  
        
      $output->writeln($resultadoEnvio);
    }
    
    protected function generarReporteSolicitado($reporte,$feDesde,$feHasta) {
      $em = $this->getContainer()->get('doctrine.orm.entity_manager');
      
      $servDatosGenerales = $this->getContainer()->get('loro_datos_generales');
      $correoDestinatario = 'gabriel.e.p.gonzalez@gmail.com,edicson26@gmail.com,manuel@textileslolo.com'; //
      
      switch ($reporte) {
            case 'ganancias-cierres-dia':
               $feInicio = new \DateTime('now');
               $feFinal = new \DateTime('now');
               
               $cierres = $em->getRepository('lOroEntityBundle:VentasCierres')->traerCierresPorFechasProveedor(null,$feInicio,$feFinal);

               $gananciaTotalReporte = 0;
               if($cierres):
                 
                 foreach($cierres as $row):
                    $dolarReferenciaDia = ($row['dolar_referencia_dia'] ? $row['dolar_referencia_dia'] : 0);

                    $dolarCierreHc = ($row['cantidad_total_venta'] *(($row['valor_onza']/31.1035)*0.97));
                    $dolarCierreProveedor = ($row['cantidad_total_venta'] *(($row['valor_onza']/31.1035)*0.95));

                    $dolarBruto = (($dolarReferenciaDia * (($row['valor_onza']/31.1035)*0.97)));
                    $bsPorGramoDados = $row['monto_bs_cierre_por_gramo'];
         
                    $diferenciaBrutoGramosDados = (($dolarBruto - $bsPorGramoDados));

                    $calculoLineal = ($dolarCierreHc - $dolarCierreProveedor);

                    if($dolarReferenciaDia != 0):
                    $totalDiferenciaBrutoGramosDados = ($diferenciaBrutoGramosDados * $row['cantidad_total_venta']);
                    $gananciaBruta = (($totalDiferenciaBrutoGramosDados/$dolarReferenciaDia) - $calculoLineal);
                    else:
                      $gananciaBruta = 0;   
                    endif;
          
                    $totalGanancia = ($calculoLineal + $gananciaBruta);

                    $datosArreglo['feVenta'] = $row['fe_venta'];
                    $datosArreglo['nbProveedor'] = $row['nb_proveedor'];
                    $datosArreglo['gramosCerrados'] = $row['cantidad_total_venta'];
                    $datosArreglo['valorOnza'] = $row['valor_onza'];
                    $datosArreglo['dolarReferenciaDia'] = ($dolarReferenciaDia != 0 ? $dolarReferenciaDia : 'N/A');
                    $datosArreglo['montoBsCierrePorGramo'] = $row['monto_bs_cierre_por_gramo'];            
                    $datosArreglo['calculoLineal'] = $calculoLineal;
                    $datosArreglo['gananciaBruta'] = $gananciaBruta;
                    $datosArreglo['totalGanancia'] = $totalGanancia;

                    $arregloCierresProveedor[] = $datosArreglo;
                    
                    $gananciaTotalReporte += $totalGanancia;
                  endforeach;
                  
                  $textoMensaje = $this->getContainer()->get('templating')->render('/Emails/correo_ganancias_cierres_proveedores.html.twig',
                        array('feInicio' => $feInicio,
                              'feFinal' => $feFinal,
                              'gananciaTotalReporte' => $gananciaTotalReporte,
                              'arregloEntity' => $arregloCierresProveedor));

                endif;
                
                $servDatosGenerales->enviarCorreo('Ganancias del Dia por Cierres a Proveedores', $correoDestinatario, $textoMensaje);
                
                
               
      
                $resultado = 'Correo con el reporte: '.$reporte.' enviado de manera satisfactoria';
                break;
            case 'ganancias-cierres-mes':
               $feActual = new \DateTime('now');
               $feInicio = new \DateTime($feActual->format('Y').'-'.$feActual->format('m').'-01');
               $feFinal = new \DateTime($feActual->format('Y').'-'.$feActual->format('m').'-31');
               
               $cierres = $em->getRepository('lOroEntityBundle:VentasCierres')->traerCierresPorFechasProveedor(null,$feInicio,$feFinal);

               $gananciaTotalReporte = 0;
               if($cierres):
                 
                 foreach($cierres as $row):
                    $dolarReferenciaDia = ($row['dolar_referencia_dia'] ? $row['dolar_referencia_dia'] : 0);

                    $dolarCierreHc = ($row['cantidad_total_venta'] *(($row['valor_onza']/31.1035)*0.97));
                    $dolarCierreProveedor = ($row['cantidad_total_venta'] *(($row['valor_onza']/31.1035)*0.95));

                    $dolarBruto = (($dolarReferenciaDia * (($row['valor_onza']/31.1035)*0.97)));
                    $bsPorGramoDados = $row['monto_bs_cierre_por_gramo'];
         
                    $diferenciaBrutoGramosDados = (($dolarBruto - $bsPorGramoDados));

                    $calculoLineal = ($dolarCierreHc - $dolarCierreProveedor);

                    if($dolarReferenciaDia != 0):
                    $totalDiferenciaBrutoGramosDados = ($diferenciaBrutoGramosDados * $row['cantidad_total_venta']);
                    $gananciaBruta = (($totalDiferenciaBrutoGramosDados/$dolarReferenciaDia) - $calculoLineal);
                    else:
                      $gananciaBruta = 0;   
                    endif;
          
                    $totalGanancia = ($calculoLineal + $gananciaBruta);

                    $datosArreglo['feVenta'] = $row['fe_venta'];
                    $datosArreglo['nbProveedor'] = $row['nb_proveedor'];
                    $datosArreglo['gramosCerrados'] = $row['cantidad_total_venta'];
                    $datosArreglo['valorOnza'] = $row['valor_onza'];
                    $datosArreglo['dolarReferenciaDia'] = ($dolarReferenciaDia != 0 ? $dolarReferenciaDia : 'N/A');
                    $datosArreglo['montoBsCierrePorGramo'] = $row['monto_bs_cierre_por_gramo'];            
                    $datosArreglo['calculoLineal'] = $calculoLineal;
                    $datosArreglo['gananciaBruta'] = $gananciaBruta;
                    $datosArreglo['totalGanancia'] = $totalGanancia;

                    $arregloCierresProveedor[] = $datosArreglo;
                    
                    $gananciaTotalReporte += $totalGanancia;
                  endforeach;
                  
                  $textoMensaje = $this->getContainer()->get('templating')->render('/Emails/correo_ganancias_cierres_proveedores.html.twig',
                        array('feInicio' => $feInicio,
                              'feFinal' => $feFinal,
                              'gananciaTotalReporte' => $gananciaTotalReporte,
                              'arregloEntity' => $arregloCierresProveedor));

                endif;
                
                $servDatosGenerales->enviarCorreo('Ganancias del Dia por Cierres a Proveedores', $correoDestinatario, $textoMensaje);
                
                
               
      
                $resultado = 'Correo con el reporte: '.$reporte.' enviado de manera satisfactoria';                
                
                break;
            case 'ganancias-cierres-por-fechas':
                
               $feInicio = new \DateTime(($feDesde ? $feDesde : 'now'));
               $feFinal = new \DateTime(($feHasta ? $feHasta : 'now'));
               
               $cierres = $em->getRepository('lOroEntityBundle:VentasCierres')->traerCierresPorFechasProveedor(null,$feInicio,$feFinal);

               $gananciaTotalReporte = 0;
               if($cierres):
                 
                 foreach($cierres as $row):
                    $dolarReferenciaDia = ($row['dolar_referencia_dia'] ? $row['dolar_referencia_dia'] : 0);

                    $dolarCierreHc = ($row['cantidad_total_venta'] *(($row['valor_onza']/31.1035)*0.97));
                    $dolarCierreProveedor = ($row['cantidad_total_venta'] *(($row['valor_onza']/31.1035)*0.95));

                    $dolarBruto = (($dolarReferenciaDia * (($row['valor_onza']/31.1035)*0.97)));
                    $bsPorGramoDados = $row['monto_bs_cierre_por_gramo'];
         
                    $diferenciaBrutoGramosDados = (($dolarBruto - $bsPorGramoDados));

                    $calculoLineal = ($dolarCierreHc - $dolarCierreProveedor);

                    if($dolarReferenciaDia != 0):
                    $totalDiferenciaBrutoGramosDados = ($diferenciaBrutoGramosDados * $row['cantidad_total_venta']);
                    $gananciaBruta = (($totalDiferenciaBrutoGramosDados/$dolarReferenciaDia) - $calculoLineal);
                    else:
                      $gananciaBruta = 0;   
                    endif;
          
                    $totalGanancia = ($calculoLineal + $gananciaBruta);

                    $datosArreglo['feVenta'] = $row['fe_venta'];
                    $datosArreglo['nbProveedor'] = $row['nb_proveedor'];
                    $datosArreglo['gramosCerrados'] = $row['cantidad_total_venta'];
                    $datosArreglo['valorOnza'] = $row['valor_onza'];
                    $datosArreglo['dolarReferenciaDia'] = ($dolarReferenciaDia != 0 ? $dolarReferenciaDia : 'N/A');
                    $datosArreglo['montoBsCierrePorGramo'] = $row['monto_bs_cierre_por_gramo'];            
                    $datosArreglo['calculoLineal'] = $calculoLineal;
                    $datosArreglo['gananciaBruta'] = $gananciaBruta;
                    $datosArreglo['totalGanancia'] = $totalGanancia;

                    $arregloCierresProveedor[] = $datosArreglo;
                    
                    $gananciaTotalReporte += $totalGanancia;
                  endforeach;
                  
                  $textoMensaje = $this->getContainer()->get('templating')->render('/Emails/correo_ganancias_cierres_proveedores.html.twig',
                        array('feInicio' => $feInicio,
                              'feFinal' => $feFinal,
                              'gananciaTotalReporte' => $gananciaTotalReporte,
                              'arregloEntity' => $arregloCierresProveedor));

                endif;
                
                $servDatosGenerales->enviarCorreo('Ganancias del Dia por Cierres a Proveedores', $correoDestinatario, $textoMensaje);
                
                
               
      
                $resultado = 'Correo con el reporte: '.$reporte.' enviado de manera satisfactoria';                
                break;
            default:
                $resultado = 'No ha seleccionado ningun reporte valido';
                break;
        }

        return $resultado;
    }
}

