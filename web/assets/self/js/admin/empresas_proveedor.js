$(function(){

  // Arreglo de funciones generales
  _globales.funciones = function() {
    // Funcion para mostrar los mensajes en el modal de Show
    var mostrarMensaje = function(mensaje) {
      $('#mensaje-accion').html(mensaje);
      $('#mensaje-accion').show().delay(900).fadeOut();  
    }

    var eliminarNroCuentaEmpresa = function(idBanco,idEmpresa,nroCuenta) {
      $.ajax({
          type: "POST",
          url: _globales.urlDeleteCuentaAction,
          data: {
            idEmpresa:idEmpresa,
            idBanco:idBanco,
            nroCuenta:nroCuenta
          },
          success: function(data) {
            $('#empresaBanco_'+idBanco+'_'+idEmpresa+'_'+nroCuenta).remove();  
           
            var mensaje = 'El nro de cuenta ha sido eliminado exitosamente.';
            _globales.funciones.mostrarMensaje('<div class="alert alert-danger" style="color:white; text-shadow:none;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '+mensaje+'</div>');
          }
      });
      
    }

    return {
      mostrarMensaje: function (mensaje) {
        return mostrarMensaje(mensaje);
      },
      eliminarNroCuentaEmpresa: function(idBanco,idEmpresa,nroCuenta) {
        return eliminarNroCuentaEmpresa(idBanco,idEmpresa,nroCuenta)
      },
    }
  }();


  function eliminarNroCuentaEmpresa(idBanco,idEmpresa,nroCuenta) {
    $.ajax({
        type: "POST",
        url: _globales.urlDeleteCuentaAction,
        data: {
          idEmpresa:idEmpresa,
          idBanco:idBanco,
          nroCuenta:nroCuenta
        },
        success: function(data) {
          $('#empresaBanco_'+idBanco+'_'+idEmpresa+'_'+nroCuenta).remove();  
         
          var mensaje = 'El nro de cuenta ha sido eliminado exitosamente.';
          _globales.funciones.mostrarMensaje('<div class="alert alert-danger" style="color:white; text-shadow:none;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '+mensaje+'</div>');
        }
    });
    
  }

  // Habilitar el Boton submit del formulario una vez cargado todo el JS
  $("#loro_entitybundle_empresasproveedores_submit").prop("disabled", false);

  // Formato para el input de nro de cuenta
  $('#nro-cuenta').mask('9999-9999-9999-9999-9999');

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






  /* Funcion al momento de abrir el modal de show */
  $(".clickable-row").click(function (e) {
    _globales.idEntity = $(this).data('id');
    $("#modalVer .id-empresa").text(_globales.idEntity);
    $("#panel-form-nro-cuenta").hide();

    var urlShow = _globales.urlShowAction;
    urlShow = urlShow.replace(':idEntity',_globales.idEntity);

    var urlEdit = _globales.urlEditAction;
    urlEdit = urlEdit.replace(':idEntity',_globales.idEntity);

     
  /* Ajax para traer los datos del item seleccionado en la lista */
  $.ajax({
        type: 'get',
        url: urlShow,
        id: _globales.idEntity,
        success: function(entity) {
          if(entity) {
            $('#sin-nros-cuentas').show();
            $('#body-nros-cuenta-registrados').hide();
            
            var empresa = jQuery.parseJSON(entity);

            /* Cabecera del Modal */
            $('#nombre-empresa').text(empresa.nombreEmpresa);
            $('#documento-empresa').text(empresa.rif);

            /* Cuentas por Empresa */
            var htmlCuentas = '';
            var cuentasPorEmpresa = empresa.cuentasPorEmpresa
            if(cuentasPorEmpresa.length > 0) {
              cuentasPorEmpresa.forEach((cuenta) => {
                var bancoId = cuenta.banco.id;
                var banco = cuenta.banco.nbBanco;
                var empresaId = cuenta.empresa;
                var nroCuenta = cuenta.nroCuenta;

                htmlCuentas += '<tr id="empresaBanco_'+bancoId+'_'+empresaId+'_'+nroCuenta+'">';
                htmlCuentas += '<td>'+banco+'</td>';
                htmlCuentas += '<td>'+nroCuenta+'</td>';
                htmlCuentas += '<td><a href="#" style="color:red;" onClick="event.preventDefault(); _globales.funciones.eliminarNroCuentaEmpresa(\''+bancoId+'\',\''+empresaId+'\',\''+nroCuenta+'\');" >Eliminar Cuenta</a></td>';
                htmlCuentas += '</tr>';
              });

              $('#sin-nros-cuentas').hide();
              $('#body-nros-cuenta-registrados').show();
              $('#body-nros-cuenta-registrados').html(htmlCuentas);
            }
            
          }
          $('#edit-button').attr('href',urlEdit);
        }
    }); 
  });



  /* Ajax para gestionar el agregar los numeros de cuentas por Empresa Seleccionada */
  $('#cuenta-por-empresa-submit').click(function(e){
    e.preventDefault();
    
    $.ajax({
        type: "POST",
        url: _globales.urlRegisterCuentaAction,
        data: {
          idEmpresa: _globales.idEntity,
          banco: $('#banco').val(),
          nroCuenta: $('#nro-cuenta').val()
        },
        success: function(data) {
        
          if(data == 'registrado') {
            var mensaje = 'El Nro de Cuenta ya se encuentra asociado a la empresa.';
            _globales.funciones.mostrarMensaje('<div class="alert alert-danger" style="color:white; text-shadow:none;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '+mensaje+'</div>');
          } else {
              
              var nroCuentaRegistrado = "<tr id='empresaBanco_"+data.idBanco+"_"+data.idEmpresa+"_"+data.nroCuenta+"'>";
                  nroCuentaRegistrado += "<td>"+data.banco+"</td>";
                  nroCuentaRegistrado += "<td>"+data.nroCuenta+"</td>";
                  nroCuentaRegistrado += '<td><a style="color:red;" href="#" onClick="event.preventDefault(); eliminarNroCuentaEmpresa(\''+data.idBanco+'\',\''+_globales.idEntity+'\',\''+data.nroCuenta+'\');">Eliminar Cuenta</a></td>';
                  nroCuentaRegistrado += "</tr>";
                  
              $('#body-nros-cuenta-registrados').append(nroCuentaRegistrado);
              $('#banco').val('');
              $('#nro-cuenta').val('');
              $('#panel-form-nro-cuenta').hide();

              var mensaje = 'El nro de cuenta ha sido registrado exitosamente.';
              _globales.funciones.mostrarMensaje('<div class="alert alert-success" style="color:white; text-shadow:none;"><i class="fa fa-check" aria-hidden="true"></i> '+mensaje+'</div>');
          }
            
        }
    });
  });



  /* Al hacer click en el boton de agregar nro de cuenta, se traen mediante ajax los bancos,
     y se procede a llenar el input de bancos, adicionalmente aparece el formulario para agregar
     un numero de cuenta */
  $('#agregar-nro-cuenta').click(function(e){
    e.preventDefault();
    
    $.ajax({
        type: "POST",
        url: _globales.urlBuscarBancosCuentaAction,
        data: {
          idEmpresa: _globales.idEntity
        },
        success: function(data) {
          var bancos = jQuery.parseJSON(data);

          var optionsBancos = '<option value="" selected="selected">Seleccione una Opción</option>';
          bancos.forEach((banco) => {
            optionsBancos += '<option value="'+banco.id+'">'+banco.nbBanco+'</option>';
          });

          $('#banco').html(optionsBancos);
          $("#banco").combobox();
        }
    });

    $('#panel-form-nro-cuenta').show();
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