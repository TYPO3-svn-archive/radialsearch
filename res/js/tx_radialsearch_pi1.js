  /*
   * Inserted by TYPO3 Radial Search
   */

  $(function() {
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
            if( ( typeof data[ "places" ] == "object" ) && ( data[ "places" ] !== null ))
            {
              response( $.map( data.places, function( item ) {
                return {
                  label: item.postal_code + " " + item.place_name,
                  value: item.postal_code
                }
              }));
              return;
            }
            if( ( typeof data[ "geonames" ] == "object" ) && ( data[ "geonames" ] !== null ))
            {
              response( $.map( data.geonames, function( item ) {
                return {
                  label: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
                  value: item.name
                }
              }));
              return;
            }
            if( ( typeof data[ "_GET" ] == "object" ) && ( data[ "_GET" ] !== null ) )
            {
              $( "###HTML_INPUT_ID###" ).removeClass( "ui-autocomplete-loading" );
              alert( "Server prompts: " + data[ "_GET" ]["andWhere"]["country_code"] );
              return;
            }
            alert( "Sorry, the server returns an undefined result." );
          }
          , error : function( req, error ) {
            alert( "Request failed: " + error );
          }
        });
      }
      , minLength: "###MINLENGTH###"
      , select: function( event, ui ) {
        lookfromplace( ui.item ?
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