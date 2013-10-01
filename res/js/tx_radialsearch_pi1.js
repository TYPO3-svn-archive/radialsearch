  /*
   * Inserted by TYPO3 Radial Search
   */

  $(function() {
    function log( message ) {
      $( "###HTML_INPUT_ID###" ).text( message ).prependTo( "#log" );
    }
    $( "###HTML_INPUT_ID###" ).autocomplete({
      source: function( request, response ) {
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
              , limit     : "###LIMIT###"
            }            
          }
          , dataType  : "jsonp"
          , success   : function( data ) {
            if( ( typeof data[ "places" ] !== "undefined" ) )
            {
              response( $.map( data.places, function( item ) {
                return {
                  label: item.postal_code + " " + item.place_name,
                  value: item.postal_code + " " + item.place_name
//                  value: item.postal_code
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