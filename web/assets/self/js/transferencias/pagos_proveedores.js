  /* Funcion al momento de abrir el modal de show */
  $(".clickable-row").click(function (e) {
    _globales.idEntity = $(this).data('id');
    $("#modalVer .entity-id").text(_globales.idEntity);

    $('#loading-icon').show();
    $('#contenido').hide();
    $('#edit-button').attr('disable',true);
    $('#delete-button').attr('disable',true);

    var urlShow = _globales.urlShowAction;
    urlShow = urlShow.replace(':idEntity',_globales.idEntity);

    
    var urlEdit = _globales.urlEditAction;
    urlEdit = urlEdit.replace(':idEntity',_globales.idEntity);

    var urlDelete = _globales.urlDeleteAction;
    urlDelete = urlDelete.replace(':idEntity',_globales.idEntity);
     
  /* Ajax para traer los datos del item seleccionado en la lista */
  $.ajax({
        type: 'get',
        url: urlShow,
        id: _globales.idEntity,
        success: function(entity) {
          if(entity) {
            var pago = jQuery.parseJSON(entity);

            $('#fecha-pago').text(pago.fePago);
            $('#pagado-por').text(pago.empresaCasa.nombreEmpresa);
            $('#banco').text(pago.banco.nbBanco);
            $('#tipo-moneda').text(pago.tipoMoneda.nbMoneda);
            $('#monto-pagado').text(pago.montoPagado+' '+pago.tipoMoneda.simboloMoneda);
            $('#nro-referencia').text(pago.nroReferencia);
            $('#tipo-transaccion').text(pago.tipoTransaccion.nbTransaccion);
            $('#cliente').text(pago.empresaPago.proveedor.nbProveedor);
            $('#empresa-pago').text(pago.empresaPago.nombreEmpresa);

            $('#edit-button').attr('href',urlEdit);
            $('#delete-button').attr('href',urlDelete);

            $('#edit-button').delay( 600 ).attr('disable',false);
            $('#delete-button').delay( 600 ).attr('disable',false);
            $('#loading-icon').delay( 800 ).hide();
            $('#contenido').delay( 800 ).show();
          }
        }
       
    }); 
  });