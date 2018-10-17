    var table = $('#'+_globales.idListado).DataTable(
    {
      responsive: true,
      "order": [[ 2, "asc" ]],
      "oLanguage": {
        "sUrl": _globales.urlDatatableLang
      } , 
   
        initComplete: function () {
            var api = this.api();
            
     
            
            api.columns().indexes().flatten().each( function ( i ) {
                
                if(_globales.titulosNoFiltrados.indexOf(i) === -1) {

               
                
                var column = api.column( i );
                var select = $('<select class="form-control"><option value="">'+$(column.header()).html()+'</option></select>')
                    .appendTo( $(column.header()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' );
                } );
                
          }
                  
          });
        }
     }
   );

