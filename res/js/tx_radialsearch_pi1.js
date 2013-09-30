  /*
   * Inserted by TYPO3 Radial Search
   */

  $(function() {
    $( "###HTML_INPUT_ID###" ).autocomplete({
      source: function( request, response ) {
        $.ajax({
            url       : "index.php"
          , type      : "GET"
          , data      : {
              eID             : "tx_radialsearch_pi1"
            , name_startsWith : request.term
            , maxRows         : "###MAXROWS###"
            , type            : "json"
            , andWhere        : {
                country_code  : "###COUNTRY_CODE###"
              , admin_code1   : "###ADMIN_CODE1###"
            }            
          }
          , dataType  : "jsonp"
          , success: function( data ) {
            alert( data[ "geonames" ] );
            //alert( data.geonames.length );
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
      , minLength: "###MINLENGTH###"
      , select: function( event, ui ) {
//        log( ui.item ?
//          "Selected: " + ui.item.label :
//          "Nothing selected, input was " + this.value);
      }
      , open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      }
      , close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      }
    });
  });