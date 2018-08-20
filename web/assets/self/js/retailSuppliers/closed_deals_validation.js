$(function(){
  $('#'+_globales.formId).formValidation({
      onSuccess: function(e) {
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
        "loro_appbundle_rsuppliers_closed_deals[feCierre]": {
          row: '.col-xs-9',
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
        "loro_appbundle_rsuppliers_closed_deals[dolarReferenciaDia]": {
          row: '.col-xs-9',
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
        "loro_appbundle_rsuppliers_closed_deals[valorOnzaReferencia]": {
          row: '.col-xs-9',
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
        "loro_appbundle_rsuppliers_closed_deals[tipoMonedaCierre]": {
          row: '.col-xs-9',
          validators: {
            notEmpty: {
              message: 'Debe seleccionar una opción.'
            },
          }
        },
        "loro_appbundle_rsuppliers_closed_deals[pesoBrutoCierre]": {
          row: '.col-xs-9',
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
        "loro_appbundle_rsuppliers_closed_deals[ley]": {
          row: '.col-xs-9',
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
        "loro_appbundle_rsuppliers_closed_deals[pesoPuroCierre]": {
          excluded: true
        },
        "loro_appbundle_rsuppliers_closed_deals[montoBsPorGramo]": {
          row: '.col-xs-9',
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
        "loro_appbundle_rsuppliers_closed_deals[totalMontoBs]": {
          excluded: true
        },
        "loro_appbundle_rsuppliers_closed_deals[minorista]": {
          row: '.col-xs-9',
          validators: {
            notEmpty: {
              message: 'Debe seleccionar una opción.'
            },
          }
        },   
      }
    }).find('loro_appbundle_rsuppliers_closed_deals[feCierre]')
        .datepicker({
                onSelect: function(date, inst) {
                    $('#'+_globales.formId).formValidation('revalidateField', 'loro_appbundle_rsuppliers_closed_deals[feCierre]');
                },
                dateFormat: 'dd-mm-yy'
            });
});


  
function undoNumberFormatForRegister() {
    var unformattedRawMassWeight = $.number($('#'+_globales.formBaseName+'pesoBrutoCierre').val(), 2,'.','' );
    $('#'+_globales.formBaseName+'pesoBrutoCierre').number(true,2,'.','');
    $('#'+_globales.formBaseName+'pesoBrutoCierre').val(unformattedRawMassWeight);

    var unformattedFineness = $.number($('#'+_globales.formBaseName+'ley').val(), 2,'.','' );
    $('#'+_globales.formBaseName+'ley').number(true,2,'.','');
    $('#'+_globales.formBaseName+'ley').val(unformattedFineness);

    var unformattedPureMassWeight = $.number($('#'+_globales.formBaseName+'pesoPuroCierre').val(), 2,'.','' );
    $('#'+_globales.formBaseName+'pesoPuroCierre').number(true,2,'.','');
    $('#'+_globales.formBaseName+'pesoPuroCierre').val(unformattedPureMassWeight);

    var unformattedFCurrencyRefDay = $.number($('#'+_globales.formBaseName+'dolarReferenciaDia').val(), 2,'.','' );
    $('#'+_globales.formBaseName+'dolarReferenciaDia').number(true,2,'.','');
    $('#'+_globales.formBaseName+'dolarReferenciaDia').val(unformattedFCurrencyRefDay);

    var unformattedOzReferenceDay = $.number($('#'+_globales.formBaseName+'valorOnzaReferencia').val(), 2,'.','' );
    $('#'+_globales.formBaseName+'valorOnzaReferencia').number(true,2,'.','');
    $('#'+_globales.formBaseName+'valorOnzaReferencia').val(unformattedOzReferenceDay);

    var unformattedBsPerGram = $.number($('#'+_globales.formBaseName+'montoBsPorGramo').val(), 2,'.','' );
    $('#'+_globales.formBaseName+'montoBsPorGramo').number(true,2,'.','');
    $('#'+_globales.formBaseName+'montoBsPorGramo').val(unformattedBsPerGram);

    var unformattedTotalAmountPayedBs = $.number($('#'+_globales.formBaseName+'totalMontoBs').val(), 2,'.','' );
    $('#'+_globales.formBaseName+'totalMontoBs').number(true,2,'.','');
    $('#'+_globales.formBaseName+'totalMontoBs').val(unformattedTotalAmountPayedBs);        
}