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
      excluded: ':disabled',
      fields: {
        "loro_entitybundle_proveedores[nbProveedor]": {
          row: '.col-xs-9',
          validators: {
            notEmpty: {
              message: 'Debe ingresar el nombre del proveedor.'
            },
          }
        },
        "loro_entitybundle_proveedores[compraDolares]": {
          row: '.col-xs-9',
          validators: {
            notEmpty: {
              message: 'Debe seleccionar una opción.'
            }
          }
        },
        "loro_entitybundle_proveedores[tipoProveedor]": {
          row: '.col-xs-9',
          validators: {
            notEmpty: {
              message: 'Debe seleccionar una opción.'
            },
          }
        },
      }
    })
});

