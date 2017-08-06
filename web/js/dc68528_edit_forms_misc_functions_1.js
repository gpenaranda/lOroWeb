$(function(){
  $( "#"+_globales.inputBaseName+"feVenta" ).datepicker(_globales.arrayOptDatePic,$.datepicker.regional[ "es" ]);

  var pesoTotalVenta = $("#"+_globales.inputBaseName+"cantidadTotalVenta");
  var valorOnza = $("#"+_globales.inputBaseName+"valorOnza");
  
  pesoTotalVenta.val($.number(pesoTotalVenta.val(), 2,',','.' ));
  pesoTotalVenta.number( true, 2,',','.' );
  valorOnza.val($.number(valorOnza.val(), 2,',','.' ));
  valorOnza.number( true, 2,',','.' );

  if ( _globales.place == 'proveedores' ) {
    var dolarReferencia = $("#"+_globales.inputBaseName+"dolarReferenciaDia");
    var montoBsCierre = $("#"+_globales.inputBaseName+"montoBsCierrePorGramo");  
      
    dolarReferencia.val($.number(dolarReferencia.val(), 2,',','.' ));
    dolarReferencia.number( true, 2,',','.' );  
    montoBsCierre.val($.number(montoBsCierre.val(), 2,',','.' ));
    montoBsCierre.number( true, 2,',','.' ); 
  }


  if ( _globales.place == 'hc' ) {
    var montoTotalDolar = $("#"+_globales.inputBaseName+"montoTotalDolar");
    montoTotalDolar.val($.number(montoTotalDolar.val(), 2,',','.' ));
    montoTotalDolar.number( true, 2,',','.' );  

    valorOnza.keyup(function(){
      if(pesoTotalVenta.val() != '')
      {
        var valorPorGramoEnDolares = (parseFloat(valorOnza.val()) / parseFloat(_globales.onzaTroyGramos));
        var resultadoMontoTotalDolar = (parseFloat(pesoTotalVenta.val()) * valorPorGramoEnDolares);
        var resultadoFinal = ((resultadoMontoTotalDolar.toFixed(2))*parseFloat(_globales.margenGanancia));
        montoTotalDolar.val(resultadoFinal);
      }
    });
      
    pesoTotalVenta.keyup(function(){
      if(valorOnza.val() != '')
      {
        var valorPorGramoEnDolares = (parseFloat(valorOnza.val()) / parseFloat(_globales.onzaTroyGramos));
        var resultadoMontoTotalDolar = (parseFloat(pesoTotalVenta.val()) * valorPorGramoEnDolares);
        var resultadoFinal = ((resultadoMontoTotalDolar.toFixed(2))*parseFloat(_globales.margenGanancia));
        montoTotalDolar.val(resultadoFinal);
      }
    });
  }

  $("#proveedores-form").submit(function(e) {
    var pesoTotalVenta = $("#"+_globales.inputBaseName+"cantidadTotalVenta");
    var nuevoValorpesoTotalVenta = $.number(pesoTotalVenta.val(), 2,'.','' );
        pesoTotalVenta.number(true,2,'.','');
        pesoTotalVenta.val(nuevoValorpesoTotalVenta);
      
    var valorOnza = $("#"+_globales.inputBaseName+"valorOnza");
    var nuevoValorOnza = $.number(valorOnza.val(), 2,'.','' );
        valorOnza.number(true,2,'.','');
        valorOnza.val(nuevoValorOnza);
    
    if ( _globales.place == 'proveedores' ) {
      var dolarReferencia = $("#"+_globales.inputBaseName+"dolarReferenciaDia");
      var nuevodolarReferencia = $.number(dolarReferencia.val(), 2,'.','' );
          dolarReferencia.number(true,2,'.','');
          dolarReferencia.val(nuevodolarReferencia);

      var montoBsCierre = $("#"+_globales.inputBaseName+"montoBsCierrePorGramo"); 
      var nuevomontoBsCierre= $.number(montoBsCierre.val(), 2,'.','' );
          montoBsCierre.number(true,2,'.','');
          montoBsCierre.val(nuevomontoBsCierre);     
    }

    if ( _globales.place == 'hc' ) {
      var montoTotalDolar = $('#loro_entitybundle_cierres_hc_montoTotalDolar');
      var nuevoMontoTotalDolar = $.number(montoTotalDolar.val(), 2,'.','' );
          montoTotalDolar.number(true,2,'.','');
          montoTotalDolar.val(nuevoMontoTotalDolar);
    }
  });
});