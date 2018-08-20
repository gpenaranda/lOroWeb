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
        "loro_cambiar_contrasena_form[password][first]": {
          row: '.col-lg-4',
          validators: {
            notEmpty: {
              message: 'Debe ingresar la nueva clave.'
            },
          }
        },
        "loro_cambiar_contrasena_form[password][second]": {
          row: '.col-lg-4',
          validators: {
            notEmpty: {
              message: 'Debe repetir la nueva clave.'
            },
          }
        },
       
       
      }
    })
});

