$(function(){
  $( "#"+_globales.inputBaseName+"feVenta" ).datepicker(_globales.arrayOptDatePic,$.datepicker.regional[ "es" ]);

  var pesoTotalVentaTagId = "#"+_globales.inputBaseName+"cantidadTotalVenta";
  var valorOnzaTagId = "#"+_globales.inputBaseName+"valorOnza";
  
  
  if ( _globales.place == 'hc' ) {
    var montoTotalDolar = $("#"+_globales.inputBaseName+"montoTotalDolar");
  
    $(pesoTotalVentaTagId+", "+valorOnzaTagId).keyup(function(){
      montoTotalDolar.val(calcurlarMontoTotal($(valorOnzaTagId),$(pesoTotalVentaTagId)));
    });

  }

 
  /* Calculo del campo de Monto total en Cierres HC */
  function calcurlarMontoTotal(valorOnza,pesoTotalVenta) 
  {
    var resultadoFinal = 0;

    if(valorOnza.val() != '')
    {
      var valorPorGramoEnDolares = (parseFloat(valorOnza.val()) / parseFloat(_globales.onzaTroyGramos));
      var resultadoMontoTotalDolar = (parseFloat(pesoTotalVenta.val()) * valorPorGramoEnDolares);
      var resultadoFinal = parseFloat(((resultadoMontoTotalDolar.toFixed(2))*parseFloat(_globales.margenGanancia)))  || 0;
    }

    return resultadoFinal.toFixed(2);
  }
});


