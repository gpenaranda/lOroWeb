$(function(){
    /* Aca se cambia el Formulario para un formato mas ajustado a lo que necesito, en este caso que todo se anide en Arrays */
    $('#loro_entrega_inicial_proveedor').attr('name','loro_entrega_inicial[proveedor][0]');
    $('#loro_entrega_inicial_piezaInicial').attr('name','loro_entrega_inicial[piezaInicial][0]');
    $('#loro_entrega_inicial_piezaFinal').attr('name','loro_entrega_inicial[piezaFinal][0]');       
    /* Aca se cambia el Formulario para un formato mas ajustado a lo que necesito, en este caso que todo se anide en Arrays */


    var supplierValidators = {
            row: '.col-xs-3',   // The title is placed inside a <div class="col-xs-4"> element
            validators: {
              notEmpty: {
                message: 'Debe seleccionar una opción.'
              }
            }
        },
        initialPieceValidators = {
            row: '.col-xs-3',
            validators: {
                notEmpty: {
                  message: 'El campo no puede estar vacio.'
                },
                integer: {
                  message: 'Solo se aceptan num. enteros.'
                },
                regexp: {
                  regexp: /^[+]?\d+([.]\d+)?$/,
                  message: 'Solo se aceptan num. positivos.'                  
                },
            }
        },
        finalPieceValidators = {
            row: '.col-xs-3',
            validators: {
                notEmpty: {
                    message: 'El campo no puede estar vacio.'
                },
                integer: {
                  message: 'Solo se aceptan num. enteros.'
                },
                regexp: {
                  regexp: /^[+]?\d+([.]\d+)?$/,
                  message: 'Solo se aceptan num. positivos.'                  
                },
            }
        },
        piecesIndex = 0;


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
      fields: {
        "loro_entrega_inicial[feEntrega]": {
          row: '.col-xs-11',
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
        "loro_entrega_inicial[tipoMonedaEntrega]": {
          row: '.col-xs-11',
          validators: {
            notEmpty: {
              message: 'Debe seleccionar una opción.'
            }
          }
        },
        'loro_entrega_inicial[proveedor][0]': supplierValidators,
        'loro_entrega_inicial[piezaInicial][0]': initialPieceValidators,
        'loro_entrega_inicial[piezaFinal][0]': finalPieceValidators   
      }
    })

    // Add button click handler
        .on('click', '.addButton', function() {
            piecesIndex++;

            var $options = $("#loro_entrega_inicial_proveedor > option").clone();
            $("select[name='proveedor']").append($options);  
            
            var $template = $('#piecesTemplate'),
                $clone    = $template
                                .clone()
                                .removeClass('hide')
                                .removeAttr('id')
                                .attr('data-pieces-index', piecesIndex)
                                .insertBefore($template);

            // Update the name attributes
            $clone 
                .find('[name="proveedor"]').attr('name', 'loro_entrega_inicial[proveedor][' + piecesIndex + ']').end()
                .find('[name="piezaInicial"]').attr('name', 'loro_entrega_inicial[piezaInicial][' + piecesIndex + ']').end()
                .find('[name="piezaFinal"]').attr('name', 'loro_entrega_inicial[piezaFinal][' + piecesIndex + ']').end();

            // Add new fields
            // Note that we also pass the validator rules for new field as the third parameter
            $('#'+_globales.formId)
                .formValidation('addField', 'loro_entrega_inicial[proveedor][' + piecesIndex + ']', supplierValidators)
                .formValidation('addField', 'loro_entrega_inicial[piezaInicial][' + piecesIndex + ']', initialPieceValidators)
                .formValidation('addField', 'loro_entrega_inicial[piezaFinal][' + piecesIndex + ']', finalPieceValidators);
        })

        // Remove button click handler
        .on('click', '.removeButton', function() {
            var $row  = $(this).parents('.form-group'),
                index = $row.attr('data-pieces-index');

            // Remove fields
            $('#'+_globales.formId)
                .formValidation('removeField', $row.find('loro_entrega_inicial[proveedor][' + index + ']'))
                .formValidation('removeField', $row.find('loro_entrega_inicial[piezaInicial][' + index + ']'))
                .formValidation('removeField', $row.find('loro_entrega_inicial[piezaFinal][' + index + ']'));

            // Remove element containing the fields
            $row.remove();
        })



    .find('[name="loro_entrega_inicial[feEntrega]"]')
            .datepicker({
                onSelect: function(date, inst) {
                    /* Revalidate the field when choosing it from the datepicker */
                    $('#'+_globales.formId).formValidation('revalidateField', 'loro_entrega_inicial[feEntrega]');
                },
                dateFormat: 'dd-mm-yy'
            });
});