$(function(){
	$('#'+_globales.formId).formValidation({
      onSuccess: function(e) {
        $('#'+_globales.formId).formValidation('validate');

        undoNumberFormatForRegister();
      },
      framework: 'bootstrap',
      icon: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      excluded: ':disabled',
      fields: {
      	"loro_entitybundle_entregas_minoristas[feEntrega]": {
          row: '.col-xs-12',
          validators: {
            notEmpty: {
              message: 'Debe ingresar una fecha.'
            },
            date: {
              format: 'DD-MM-YYYY',
              message: 'El valor introducido no es una fecha valida.'
            }
          }
        },
        "loro_entitybundle_entregas_minoristas[dolarReferenciaDia]": {
          row: '.col-xs-12',
          validators: {
            notEmpty: {
              message: 'Debe ingresar un monto.'
            },
            numeric: {
              message: 'El valor no es un numero válido.',
              thousandsSeparator: '.',
              decimalSeparator: ','
            },
            regexp: {
              regexp: /^[+]?\d+([.]\d+)?$/,
              message: 'Solo se aceptan num. positivos.'                  
            },
            regexp: {
              regexp: /^([0-9]\.\d+)|([1-9]\d*\.?\d*)$/,
              message: 'No puede ser 0.'
            },
          }
        },
        "loro_entitybundle_entregas_minoristas[valorOnza]": {
          row: '.col-xs-12',
          validators: {
            notEmpty: {
              message: 'Debe ingresar un monto.'
            },
            numeric: {
              message: 'El valor no es un numero válido.',
              thousandsSeparator: '.',
              decimalSeparator: ','
            },
            regexp: {
              regexp: /^[+]?\d+([.]\d+)?$/,
              message: 'Solo se aceptan num. positivos.'                  
            },
            regexp: {
              regexp: /^([0-9]\.\d+)|([1-9]\d*\.?\d*)$/,
              message: 'No puede ser 0.'
            }, 
          }
        },
        "loro_entitybundle_entregas_minoristas[pesoBrutoEntrega]": {
          row: '.col-xs-12',
          validators: {
            notEmpty: {
              message: 'Debe ingresar un monto.'
            },
            numeric: {
              message: 'El valor no es un numero válido.',
              thousandsSeparator: '.',
              decimalSeparator: ','
            },
            regexp: {
              regexp: /^[+]?\d+([.]\d+)?$/,
              message: 'Solo se aceptan num. positivos.'                  
            },
            regexp: {
              regexp: /^([0-9]\.\d+)|([1-9]\d*\.?\d*)$/,
              message: 'No puede ser 0.'
            }, 
          }
        },
        "loro_entitybundle_entregas_minoristas[ley]": {
          row: '.col-xs-12',
          validators: {
            notEmpty: {
              message: 'Debe ingresar un monto.'
            },
            numeric: {
              message: 'El valor no es un numero válido.',
              thousandsSeparator: '.',
              decimalSeparator: ','
            },
            regexp: {
              regexp: /^[+]?\d+([.]\d+)?$/,
              message: 'Solo se aceptan num. positivos.'                  
            },
            regexp: {
              regexp: /^([0-9]\.\d+)|([1-9]\d*\.?\d*)$/,
              message: 'No puede ser 0.'
            },
          }
        },
        "loro_entitybundle_entregas_minoristas[pesoPuroEntrega]": {
          excluded: true
        },
        "loro_entitybundle_entregas_minoristas[montoBsPorGramo]": {
          row: '.col-xs-12',
          validators: {
            notEmpty: {
              message: 'Debe ingresar un monto.'
            },
            numeric: {
              message: 'El valor no es un numero válido.',
              thousandsSeparator: '.',
              decimalSeparator: ','
            },
            regexp: {
              regexp: /^[+]?\d+([.]\d+)?$/,
              message: 'Solo se aceptan num. positivos.'                  
            },
            regexp: {
              regexp: /^([0-9]\.\d+)|([1-9]\d*\.?\d*)$/,
              message: 'No puede ser 0.'
            },
          }
        },
        "loro_entitybundle_entregas_minoristas[totalMontoBs]": {
          excluded: true
        },
        "loro_entitybundle_entregas_minoristas[minorista]": {
          row: '.col-xs-12',
          validators: {
            notEmpty: {
              message: 'Debe seleccionar una opción.'
            },
          }
        },   
      }
    })  
     /* Using and Validating the Datepicker on Date Field 
    .find('loro_entitybundle_entregas_minoristas[feEntrega]')
            .datepicker({
                onSelect: function(date, inst) {
                    $('#'+_globales.formId).formValidation('revalidateField', 'loro_entitybundle_entregas_minoristas[feEntrega]');
                },
                dateFormat: 'dd-mm-yy'
            })*/
    /* Using Combobox for Suppliers */
    .find(_globales.formBaseName+'[minorista]')
    .combobox()
    .end();

    $.material.init();
});


  
function undoNumberFormatForRegister() {
     var nuevoValorPesoBruto = $.number(_globales.pesoBrutoEntrega.val(), 2,'.','' );
     _globales.pesoBrutoEntrega.number(true,2,'.','');
     _globales.pesoBrutoEntrega.val(nuevoValorPesoBruto);
    
     var nuevoLey = $.number(_globales.ley.val(), 2,'.','' );
     _globales.ley.number(true,2,'.','');
     _globales.ley.val(nuevoLey);        
     

     var nuevoValorPesoPuro = parseFloat((nuevoValorPesoBruto * nuevoLey) /parseInt(1000));
     _globales.pesoPuroEntrega.number(true,2,'.','');
     _globales.pesoPuroEntrega.val(nuevoValorPesoPuro); 
     
     var nuevoValorMontoBsPorGramo = parseFloat(_globales.montoBsPorGramo.val());
     _globales.montoBsPorGramo.number(true,2,'.','');
     _globales.montoBsPorGramo.val(nuevoValorMontoBsPorGramo); 
     
     var nuevoValorTotalMontoBs = parseFloat((nuevoValorMontoBsPorGramo * nuevoValorPesoBruto));
     _globales.totalMontoBs.number(true,2,'.','');
     _globales.totalMontoBs.val(nuevoValorTotalMontoBs);  
     
      var nuevoValorOnza = parseFloat(_globales.valorOnza.val());
     _globales.valorOnza.number(true,2,'.','');
     _globales.valorOnza.val(nuevoValorOnza);  
     
     var nuevoValorDolarReferenciaDia = parseFloat(_globales.dolarReferenciaDia.val());
     _globales.dolarReferenciaDia.number(true,2,'.','');
     _globales.dolarReferenciaDia.val(nuevoValorDolarReferenciaDia);  
}