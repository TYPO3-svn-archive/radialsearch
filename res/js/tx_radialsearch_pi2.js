  $(function() {
    function log( message ) {
      $( "<div>" ).text( message ).prependTo( "#log" );
      $( "#log" ).scrollTop( 0 );
    }
    $( "#city" ).autocomplete({
      source: function( request, response ) {
        $.ajax({
            url       : "http://api.geonames.org/search"
          , type      : "GET"
          , data      : {
              featureClass    : "P"
            , style           : "full"
            , maxRows         : 12
            , name_startsWith : request.term
            , type            : "json"
            , username        : "demo"
            // , country         : "DE"    // Germany
            //, adminCode1      : "15" // TH
            , lang            : "de"
          }
          , dataType  : "jsonp"
          , success: function( data ) {
            if( "geonames" in data )
            {
              response( $.map( data.geonames, function( item ) {
                return {
                  label: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
                  value: item.name
                }
              }));
            }
            return {
              label: "Fehler",
              value: 0
            }
          }
          , error: function( req, error ) {
            alert( "Request failed: " + error );
          }
        });
      }
      ,  minLength: 2
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