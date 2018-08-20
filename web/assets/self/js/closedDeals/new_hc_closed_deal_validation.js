$(function(){
    $('#'+_globales.formId).formValidation({
      onSuccess: function(e) {
        //undoNumberFormatForRegister();
      },
      framework: 'bootstrap',
      icon: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      fields: {
        "loro_entitybundle_cierres_hc[feVenta]": {
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
        "loro_entitybundle_cierres_hc[montoTotalDolar]": {
          row: '.col-xs-9',
          excluded: true 
        },
        "loro_entitybundle_cierres_hc[tipoMonedaCierre]": {
          row: '.col-xs-9',
          validators: {
            notEmpty: {
              message: 'Debe seleccionar una opción.'
            }
          }
        },
        "loro_entitybundle_cierres_hc[cantidadTotalVenta]": {
          row: '.col-xs-9',
          validators: {
            notEmpty: {
              message: 'Debe ingresar un monto.'
            },
            numeric: {
              message: 'El valor no es un numero válido.'
            }
          }
        },
        "loro_entitybundle_cierres_hc[valorOnza]": {
          row: '.col-xs-9',
          validators: {
            notEmpty: {
              message: 'Debe ingresar un monto.'
            },
            numeric: {
              message: 'El valor no es un numero válido.'
            }
          }
        },     
      }
    }).find('[name="loro_entitybundle_cierres_hc[feVenta]"]')
      .datepicker({
        onSelect: function(date, inst) {
          /* Revalidate the field when choosing it from the datepicker */
          $('#'+_globales.formId).formValidation('revalidateField', 'loro_entitybundle_cierres_hc[feVenta]');
        },
        dateFormat: 'dd-mm-yy'
      });
});