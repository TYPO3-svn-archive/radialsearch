  /*
   * Inserted by TYPO3 Radial Search
   */

  $(function() {
    function log( message ) {
      $( "###HTML_INPUT_ID###" ).text( message ).prependTo( "#log" );
    }
    var cache = {};
    $( "###HTML_INPUT_ID###" ).autocomplete({
      source: function( request, response ) {
        var term = request.term;
        if ( term in cache )
        {
          //alert( term );
          response( $.map( cache[ term ], function( item ) {
            return {
              label: item.postal_code + " " + item.place_name,
              value: item.postal_code + " " + item.place_name
            }
          }));
          return;
        }
        $.ajax({
            url   : "index.php"
          , type  : "GET"
          , data  : {
              eID   : "tx_radialsearch_pi1"
            , type  : "json"
            , sql   : {
                sword     : request.term
              , andWhere  : {
                  country_code  : "###COUNTRY_CODE###"
                , admin_code1   : "###ADMIN_CODE1###"
              }
              , orderBy   : "country_code, postal_code, place_name"
              , limit     : "###LIMIT###"
            }            
          }
          , dataType  : "jsonp"
          , success   : function( data ) {
            if( ( typeof data[ "places" ] !== "undefined" ) )
            {
              cache[ term ] = data.places;
              //alert( term );
              //alert( cache[ term ] );
              response( $.map( data.places, function( item ) {
                return {
                  label: item.postal_code + " " + item.place_name,
                  value: item.postal_code + " " + item.place_name
                }
              }));
              return;
            }
            alert( "Sorry, the server didn't return the object \"places\"." );
          }
          , error : function( req, error ) {
            alert( "Request failed: " + error + "\n\nPlease enable the DRS and check the Developer Log.\n\nSorry for the trouble. TYPO3 Radial Search.");
          }
        });
      }
      , minLength: "###MINLENGTH###"
//      , select: function( event, ui ) {
//        log( ui.item ?
//          "Selected: " + ui.item.label :
//          "Nothing selected, input was " + this.value);
//      }
//      , open: function() {
//        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
//      }
//      , close: function() {
//        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
//      }
    });
  });