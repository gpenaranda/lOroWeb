  /* Funcion al momento de abrir el modal de show */
  $(".clickable-row").click(function (e) {
    _globales.idEntity = $(this).data('id');
    $("#modalVer .entity-id").text(_globales.idEntity);

    var urlShow = _globales.urlShowAction;
    urlShow = urlShow.replace(':idEntity',_globales.idEntity);
alert(urlShow);
    /*
    var urlEdit = _globales.urlEditAction;
    urlEdit = urlEdit.replace(':idEntity',_globales.idEntity);
    */
     
  /* Ajax para traer los datos del item seleccionado en la lista */
  $.ajax({
        type: 'get',
        url: urlShow,
        id: _globales.idEntity,
        success: function(entity) {
            console.log(entity);
        /*
          if(entity) {
            $('#sin-nros-cuentas').show();
            $('#body-nros-cuenta-registrados').hide();
            
            var empresa = jQuery.parseJSON(entity);

            /* Cabecera del Modal 
            $('#nombre-empresa').text(empresa.nombreEmpresa);
            $('#documento-empresa').text(empresa.rif);

            /* Cuentas por Empresa 
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
            
          } */
          $('#edit-button').attr('href',urlEdit);
        }
       
    }); 
  });