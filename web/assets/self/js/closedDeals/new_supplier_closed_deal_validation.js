$(function(){
	$('#'+_globales.formId).formValidation({
      onSuccess: function(e) {
       // undoNumberFormatForRegister();
      },
      framework: 'bootstrap',
      icon: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      excluded: ':disabled',
      fields: {
      	"loro_entitybundle_ventascierres[feVenta]": {
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
        "loro_entitybundle_ventascierres[dolarReferenciaDia]": {
          row: '.col-xs-9',
          validators: {
            notEmpty: {
              message: 'Debe ingresar un monto.'
            },
            numeric: {
              message: 'El valor no es un numero válido.'
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
        "loro_entitybundle_ventascierres[montoBsCierrePorGramo]": {
          row: '.col-xs-9',
          validators: {
            notEmpty: {
              message: 'Debe ingresar un monto.'
            },
            numeric: {
              message: 'El valor no es un numero válido.'
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
        "loro_entitybundle_ventascierres[tipoMonedaCierre]": {
          row: '.col-xs-9',
          validators: {
            notEmpty: {
              message: 'Debe seleccionar una opción.'
            }
          }
        },
        "loro_entitybundle_ventascierres[cantidadTotalVenta]": {
          row: '.col-xs-9',
          validators: {
            notEmpty: {
              message: 'Debe ingresar un monto.'
            },
            numeric: {
              message: 'El valor no es un numero válido.'
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
        "loro_entitybundle_ventascierres[valorOnza]": {
          row: '.col-xs-9',
          validators: {
            notEmpty: {
              message: 'Debe ingresar un monto.'
            },
            numeric: {
              message: 'El valor no es un numero válido.'
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
        "loro_entitybundle_ventascierres[proveedorCierre]": {
          row: '.col-xs-9',
          validators: {
            notEmpty: {
              message: 'Debe seleccionar una opción.'
            }
          }
        },      
      }
    })
     /* Using and Validating the Datepicker on Date Field */
    .find('[name="loro_entitybundle_ventascierres[feVenta]"]')
            .datepicker({
                onSelect: function(date, inst) {
                    $('#'+_globales.formId).formValidation('revalidateField', 'loro_entitybundle_ventascierres[feVenta]');
                },
                dateFormat: 'dd-mm-yy'
            })
    /* Using Combobox for Suppliers */
    .find('[name="loro_entitybundle_ventascierres[proveedorCierre]"]')
    .combobox()
    .end();
});