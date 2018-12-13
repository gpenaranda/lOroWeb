$(function(){
  $( "#"+_globales.inputBaseName+"feVenta" ).datepicker(_globales.arrayOptDatePic,$.datepicker.regional[ "es" ]);

  var pesoTotalVentaTagId = "#"+_globales.inputBaseName+"cantidadTotalVenta";
  var valorOnzaTagId = "#"+_globales.inputBaseName+"valorOnza";
  var descuentoOnzaProveedorTagId = "#"+_globales.inputBaseName+"descuentoOnzaProveedor";


  if ( _globales.place == 'hc' ) {
    var montoTotalDolar = $("#"+_globales.inputBaseName+"montoTotalDolar");
  
    $(pesoTotalVentaTagId+", "+valorOnzaTagId+", "+descuentoOnzaProveedorTagId).keyup(function(){
      montoTotalDolar.val(calcularMontoTotal($(valorOnzaTagId),$(pesoTotalVentaTagId),$(descuentoOnzaProveedorTagId)));
    });
  }

  /* Calculo del campo de Monto total en Cierres HC */
  function calcularMontoTotal(valorOnza,pesoTotalVenta,descuentoOnzaProveedor) 
  {
    var resultadoFinal = 0;

    if(valorOnza.val() != '')
    {
      var valorPorGramoEnDolares = (parseFloat(valorOnza.val()) / parseFloat(_globales.onzaTroyGramos));
      var resultadoMontoTotalDolar = (parseFloat(pesoTotalVenta.val()) * valorPorGramoEnDolares);
      var resultadoFinal = parseFloat(((resultadoMontoTotalDolar.toFixed(2))*parseFloat(descuentoOnzaProveedor.val())))  || 0;
    }

    return resultadoFinal.toFixed(2);
  }  
});


