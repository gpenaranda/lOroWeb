$(function(){


    /**/
    var $supplierOptions = $("#proveedor_1 > option").clone();
    $('#paymentsTemplate select[name="proveedor"]').append($supplierOptions); 

    /* INITIALIZATION OF THE DATEPICKER */
    $('#'+_globales.formName+'feEjecucion').datepicker(_globales.arregloOpcionesDP,$.datepicker.regional[ "es" ]);

    /* CALL FOR THE FUNCTION THAT BRINGS THE ACCOUNT NUMBERS ASSOCIATED TO ANY COMPANY OF OUR OWN */
    $('#'+_globales.formName+'empresaCasa').change(function() { _myFunctions.findOwnAccNumbersByCompany(this); });  

    /* CREATION OF THE FIRST COMBOBOX IN THE INSTRUCTIONS SECTION */
    $('#empresasProveedor_1').combobox();
    $('#nroCuenta_1').combobox();
    $("#proveedor_1").combobox().change(function() { 
       var numId = _myFunctions.buscarEmpresasProveedor($(this)); 
    });

    

    /* NUMBER FORMAT WITH JQUERY.NUMBER */
    var cantidadAEnviar = $("#cantidadAEnviar_1");
    cantidadAEnviar.number( true, 2,',','.' );

    /* FORM VALIDATION */
    var supplierValidators = {
            row: '.col-xs-2',
            validators: {
              notEmpty: {
                message: 'Debe seleccionar una opción.'
              },
            }
        },
        companiesBySupplierValidators = {
            row: '.col-xs-3',
            validators: {
              notEmpty: {
                message: 'Debe seleccionar una opción.'
              },
            }
        },
        accNumberValidators = {
            row: '.col-xs-3',
            validators: {
                notEmpty: {
                    message: 'El campo no puede estar vacio.'
                },
                regexp: {
                  regexp: /^[0-9]+$/,
                  message: 'Solo se aceptan num. positivos.'                  
                },
                stringLength: {
                    max: 20,
                    min: 20,
                    message: 'Deben ser 20 numeros exactos.'
                },
            }
        },
        paymentAmountValidators = {
            row: '.col-xs-2',
            validators: {
                notEmpty: {
                    message: 'El campo no puede estar vacio.'
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
            }
        },
        paymentIndex = 1;


    $('#'+_globales.formId).formValidation({
        onSuccess: function(e) {
            $('#paymentsTemplate').remove();
        // undoNumberFormatForRegister();
        },
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            "loro_pagos_carga_masiva[feEjecucion]": {
              row: '.col-md-2',
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
            "loro_pagos_carga_masiva[empresaCasa]": {
              row: '.col-md-2',
              validators: {
                notEmpty: {
                  message: 'Debe seleccionar una opción.'
                }
              }
            },
            "loro_pagos_carga_masiva[nrosCuenta]": {
              row: '.col-md-2',
              validators: {
                notEmpty: {
                  message: 'Debe seleccionar una opción.'
                }
              }
            },
            'loro_pagos_carga_masiva[proveedor][1]': supplierValidators,
            'loro_pagos_carga_masiva[empresasProveedor][1]': companiesBySupplierValidators,
            'loro_pagos_carga_masiva[nroCuenta][1]': accNumberValidators, 
            'loro_pagos_carga_masiva[cantidadAEnviar][1]': paymentAmountValidators,
        }
    })
    /* Using Combobox for Suppliers 
    .find('[name="payment[1].proveedor"]')
    .combobox()
    .end()
    /* Using Combobox for Suppliers Companies 
    .find('[name="payment[1].empresasProveedor"]')
    .combobox()
    .end()*/
// Add button click handler
    .on('click', '.addButton', function() {
            paymentIndex++;

                    

            var $template = $('#paymentsTemplate'),
                $clone    = $template
                                .clone()
                                .removeClass('hide')
                                .removeAttr('id')
                                .attr('data-payment-index', paymentIndex)
                                .insertBefore($template);

            // Update the name attributes
            $clone                  
                .find('[name="proveedor"]')         .attr('name', 'loro_pagos_carga_masiva[proveedor][' + paymentIndex + ']')
                                                    .attr('id','proveedor_'+paymentIndex).end()
                .find('[name="empresasProveedor"]') .attr('name', 'loro_pagos_carga_masiva[empresasProveedor][' + paymentIndex + ']')
                                                    .attr('id','empresasProveedor_'+paymentIndex).combobox().end()
                .find('[name="nroCuenta"]')         .attr('name', 'loro_pagos_carga_masiva[nroCuenta][' + paymentIndex + ']')
                                                    .attr('id','nroCuenta_'+paymentIndex).combobox().end()
                .find('[name="cantidadAEnviar"]')   .attr('name', 'loro_pagos_carga_masiva[cantidadAEnviar][' + paymentIndex + ']')
                                                    .attr('id','cantidadAEnviar_' + paymentIndex).end();


            
            /* Jquery.number for every amount input */
            var t = "var cantidadAEnviar"+paymentIndex +" = $('#cantidadAEnviar_"+ paymentIndex +"'); cantidadAEnviar"+paymentIndex +".number( true, 2,',','.' );";
            eval(t);
                
                

            /* Companies by Supplier registration of Combobox */
            var s = "$('#proveedor_"+paymentIndex+"').combobox().change(function() { _myFunctions.buscarEmpresasProveedor($(this)); });";
            eval(s);


 
            // Add new fields
            // Note that we also pass the validator rules for new field as the third parameter
            $('#'+_globales.formId)
                .formValidation('addField', 'loro_pagos_carga_masiva[proveedor][' + paymentIndex + ']', supplierValidators)
                .formValidation('addField', 'loro_pagos_carga_masiva[empresasProveedor][' + paymentIndex + ']', companiesBySupplierValidators)
                .formValidation('addField', 'loro_pagos_carga_masiva[nroCuenta][' + paymentIndex + ']', accNumberValidators)
                .formValidation('addField', 'loro_pagos_carga_masiva[cantidadAEnviar][' + paymentIndex + ']', paymentAmountValidators);
        })

        // Remove button click handler
    .on('click', '.removeButton', function() {
            var $row  = $(this).parents('.form-group'),
                index = $row.attr('data-payment-index');

            // Remove fields
            $('#'+_globales.formId)
                .formValidation('removeField', $row.find('[name="loro_pagos_carga_masiva[proveedor][' + paymentIndex + ']"]'))
                .formValidation('removeField', $row.find('[name="loro_pagos_carga_masiva[empresasProveedor][' + paymentIndex + ']"]'))
                .formValidation('removeField', $row.find('[name="loro_pagos_carga_masiva[nroCuenta][' + paymentIndex + ']"]'))
                .formValidation('removeField', $row.find('[name="loro_pagos_carga_masiva[cantidadAEnviar][' + paymentIndex + ']"]'));

            // Remove element containing the fields
            $row.remove();
    })
    .find('[name="loro_pagos_carga_masiva[feEjecucion]"]')
    .datepicker({
        onSelect: function(date, inst) {
            /* Revalidate the field when choosing it from the datepicker */
            $('#'+_globales.formId).formValidation('revalidateField', 'loro_pagos_carga_masiva[feEjecucion]');
        },
        dateFormat: 'dd-mm-yy'
    });
    /* FORM VALIDATION */



 



/* REGISTRATION AND DEFINITION OF THE FUNCTIONS USED GLOBALLY */
_myFunctions = {
    buscarEmpresasProveedor : function (self) {
        var self = self[ 0 ];
        var supplierVal = self.value;
        var numId = self.id.split('_');
        var inputsEmpresas = null;



        if(!!supplierVal.trim()) {
            $.ajax({
              type: "POST",
              url: _globales.pathFindCompaniesBySupplier,
              data: {
                idProveedor: supplierVal
              },
              success: function(data) {
                inputsEmpresas = '<option value=""></option>';
                if(data != 'vacio') {
                  for(var r in data) {
                    inputsEmpresas += '<option value="'+data[r].id+'">'+data[r].nbEmpresa+'</option>';
                  } 
                  
                  $('#empresasProveedor_'+numId[1]).html(inputsEmpresas);

                }
                

                var t = "var selectAccNumber = $('#nroCuenta_"+numId[1]+"'); selectAccNumber.combobox(); selectAccNumber.combobox('refresh'); selectAccNumber.combobox('clearElement'); selectAccNumber.combobox('clearTarget'); var select = $('#empresasProveedor_"+numId[1]+"'); select.combobox(); select.combobox('refresh'); select.combobox('clearElement'); select.combobox('clearTarget'); select.combobox().change(function() { _myFunctions.findAccNumbersByCompany($(this));});";
                eval(t); 
              }
            });
        } else {
            $('#empresasProveedor_'+numId[1]+' > option').remove();
            $('#empresasProveedor_'+numId[1]).html('<option value=""></option>');
          }

        return numId[1];
    },
    findAccNumbersByCompany: function(self) {
        var self = self[ 0 ];
        var companyVal = self.value;
        var numId = self.id.split('_');

        if(!!companyVal.trim()) {
            $.ajax({
              type: "POST",
              url: _globales.pathFindAccNumbersByCompany,
              data: { idEmpresa: companyVal
                    },
              success: function(data) {
                var optionsAccNumbers = '<option value=""></option>';
                if(data != 'vacio') {
                  for(var r in data) {
                    optionsAccNumbers += '<option value="'+data[r].id+'">'+data[r].value+'</option>';
                  } 
                  
                  $('#nroCuenta_'+numId[1]).html(optionsAccNumbers);


                  var s = "var select = $('#nroCuenta_"+numId[1]+"'); select.combobox(); select.combobox('refresh'); select.combobox('clearElement'); select.combobox('clearTarget');";

                  eval(s);
                }  
              }
            });
        } else {
            $('#nroCuenta_'+numId[1]+' > option').remove();
            $('#nroCuenta_'+numId[1]).html('<option value=""></option>');
          }
    },
    findOwnAccNumbersByCompany: function(self) {
        var inputsNrosCuenta = '<option value="" selected="selected">Seleccione una Opción</option>';
        $('#'+_globales.formName+'nrosCuenta').html(inputsNrosCuenta);
        _globales.idEmpresa = $(self).val();

        $.ajax({
            type: "POST",
            url:  _globales.urlBusquedaCuentaPorEmpresa,
            data: { idEmpresa: $(self).val()},
            success: function(data) {
              if(data != 'vacio') {
                for(var r in data) {
                  inputsNrosCuenta += '<option value="'+data[r].nroCuenta+'">'+data[r].banco+' - '+data[r].nroCuenta+'</option>';
                } 
                
                $('#'+_globales.formName+'nrosCuenta').html(inputsNrosCuenta);
                var s = "var select = $('#'+_globales.formName+'nrosCuenta'); select.combobox('refresh');  select.combobox('clearElement');  select.combobox('clearTarget');";

                eval(s);
              }  
            }
        });
    },
}
/* REGISTRATION AND DEFINITION OF THE FUNCTIONS USED GLOBALLY */


});
    
    
    
   
    
   
