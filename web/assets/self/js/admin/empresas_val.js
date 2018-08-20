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
        "loro_entitybundle_empresasproveedores[proveedor]": {
          row: '.col-xs-9',
          validators: {
            notEmpty: {
              message: 'Debe seleccionar una opción.'
            },
          }
        },
        "loro_entitybundle_empresasproveedores[nombreEmpresa]": {
          row: '.col-xs-9',
          validators: {
            notEmpty: {
              message: 'Debe indicar el nombre de la empresa.'
            },
            stringLength: {
              max: 40,
              message: 'El nombre de la empresa debe tener un maximo de 40 caracteres.'
            },
            regexp: {
              regexp: /^[a-zA-Z0-9 ]+$/,
              message: 'El nombre de la empresa solo puede contener letras y numeros.'                  
            }, 
          }
        },
        "loro_entitybundle_empresasproveedores[aliasEmpresa]": {
          row: '.col-xs-9',
          validators: {
            notEmpty: {
              message: 'Debe indicar el alias de la empresa.'
            },
          }
        },
         "loro_entitybundle_empresasproveedores[tipoDocumento]": {
          row: '.col-xs-9',
          validators: {
            notEmpty: {
              message: 'Debe seleccionar una opción.'
            },
          }
        },
          "loro_entitybundle_empresasproveedores[rif]": {
          row: '.col-xs-9',
          validators: {
            notEmpty: {
               message: 'Debe indicar el alias de la empresa.'
            },
          }
        },
      }
    })
});