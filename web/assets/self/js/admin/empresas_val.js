$(function(){
  // Habilitar el Boton submit del formulario una vez cargado todo el JS
  $("#loro_entitybundle_empresasproveedores_submit").prop("disabled", false);

  // Combobox para selects Proveedor y Tipo Documento
  $("#loro_entitybundle_empresasproveedores_proveedor, #loro_entitybundle_empresasproveedores_tipoDocumento").combobox();

  // Evitar que se usen caracteres fuera de letras y numeros en Nombre de Empresa y Alias Empresa
  $('#loro_entitybundle_empresasproveedores_nombreEmpresa, #loro_entitybundle_empresasproveedores_aliasEmpresa').keypress(function (e) {
    var regex = new RegExp("^[a-zA-Z0-9]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        return true;
    }

    e.preventDefault();
    return false;
  });


  // Validaciones para el Formulario
	$('#'+_globales.formId).formValidation({
      framework: 'bootstrap',
      icon: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      excluded: ':disabled',
      fields: {
        "loro_entitybundle_empresasproveedores[proveedor]": {
          row: '.col-xs-12',
          validators: {
            notEmpty: {
              message: 'Debe seleccionar una opción.'
            },
          }
        },
        "loro_entitybundle_empresasproveedores[nombreEmpresa]": {
          row: '.col-xs-12',
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
          row: '.col-xs-12',
          validators: {
            notEmpty: {
              message: 'Debe indicar el alias de la empresa.'
            },
          }
        },
         "loro_entitybundle_empresasproveedores[tipoDocumento]": {
          row: '.col-xs-12',
          validators: {
            notEmpty: {
              message: 'Debe seleccionar una opción.'
            },
          }
        },
          "loro_entitybundle_empresasproveedores[rif]": {
          row: '.col-xs-12',
          validators: {
            notEmpty: {
               message: 'Debe indicar el alias de la empresa.'
            },
          }
        },
      }
    });

});