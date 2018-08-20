$(function(){
	$('#'+_globales.formId).formValidation({
      onSuccess: function(e) {
       // $('#'+_globales.formId).formValidation('validate');

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
      	"loro_appbundle_entregasminoristas[feEntrega]": {
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
        "loro_appbundle_entregasminoristas[pesoBrutoEntrega]": {
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
        "loro_appbundle_entregasminoristas[ley]": {
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
        "loro_appbundle_entregasminoristas[pesoPuroEntrega]": {
          excluded: true
        },
        "loro_appbundle_entregasminoristas[minorista]": {
          row: '.col-xs-12',
          validators: {
            notEmpty: {
              message: 'Debe seleccionar una opción.'
            },
          }
        },   
      }
    })  
     /* Using and Validating the Datepicker on Date Field */
    .find('loro_appbundle_entregasminoristas[feEntrega]')
            .datepicker({
                onSelect: function(date, inst) {
                    $('#'+_globales.formId).formValidation('revalidateField', 'loro_appbundle_entregasminoristas[feEntrega]');
                },
                dateFormat: 'dd-mm-yy'
            });

    
});


  
function undoNumberFormatForRegister() {
     var nuevoValorPesoBruto = $.number(_globales.pesoBrutoEntrega.val(), 2,'.','' );
     _globales.pesoBrutoEntrega.number(true,2,'.','');
     _globales.pesoBrutoEntrega.val(nuevoValorPesoBruto);
    
     var nuevoLey = $.number(_globales.ley.val(), 2,'.','' );
     _globales.ley.number(true,2,'.','');
     _globales.ley.val(nuevoLey);        
     

     var nuevoValorPesoPuro = parseFloat((nuevoValorPesoBruto * (nuevoLey /parseInt(1000))));
     _globales.pesoPuroEntrega.number(true,2,'.','');
     _globales.pesoPuroEntrega.val(nuevoValorPesoPuro); 
}