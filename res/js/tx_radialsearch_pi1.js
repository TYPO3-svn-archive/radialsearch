  $(function() {
    function log( message ) {
      $( "<div>" ).text( message ).prependTo( "#log" );
      $( "#log" ).scrollTop( 0 );
    }
    $( "#city" ).autocomplete({
      source: function( request, response ) {
        $.ajax({
            url       : "index.php"
          , type      : "GET"
          , data      : {
              eID             : "tx_radialsearch_pi1"
            , name_startsWith : request.term
            , maxRows         : 12
            , type            : "json"
            , andWhere        : {
                country_code  : "DE"
              , admin_code1   : "TH"
            }            
          }
          , dataType  : "jsonp"
          , success: function( data ) {
            if( "key" in data )
            {
              
            }
//            if( data.status.length > 0 ) {
//              response( $.map( data.status, function( item ) {
//                return {
//                  label: item,
//                  value: item
//                }
//              }));
//            }
            response( $.map( data.geonames, function( item ) {
              return {
                label: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
                value: item.name
              }
            }));
          }
          , error: function( req, error ) {
            alert( "Request failed: " + error );
          }
        });
      }
      , minLength: 2
      , select: function( event, ui ) {
        log( ui.item ?
          "Selected: " + ui.item.label :
          "Nothing selected, input was " + this.value);
      }
      , open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      }
      , close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      }
    });
  });