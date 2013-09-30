<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

require_once(PATH_tslib . 'class.tslib_pibase.php');

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   63: class tx_radialsearch_pi2 extends tslib_pibase
 *
 *              SECTION: Main
 *   97:     public function main( $content, $conf )
 *  144:     public function main2( $content, $conf )
 *  178:     function log( message )
 *
 *              SECTION: Init
 *  266:     private function init( )
 *  285:     private function initFlexform( )
 *  298:     private function initInstances( )
 *  323:     private function initTemplate( )
 *
 *              SECTION: ZZ
 *  346:     private function zz_cObjGetSingle( $cObj_name, $cObj_conf )
 *
 * TOTAL FUNCTIONS: 8
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * Plugin 'Radial Search' of the extension radialsearch
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	radialsearch
 * @version	0.0.1
 * @since       0.0.1
 */
class tx_radialsearch_pi2 extends tslib_pibase
{

  public $extKey        = 'radialsearch';
  public $prefixId      = 'tx_radialsearch_pi2';
  public $scriptRelPath = 'pi2/class.tx_radialsearch_pi2.php';


  private $dynamicMarkers   = null;
  public  $drs              = null;
  public  $flexform         = null;

  public  $local_cObj       = null;
  public  $conf             = null;
  public  $arr_extConf      = null;
  public  $tmpl             = null;



  /***********************************************
  *
  * Main
  *
  **********************************************/

  public function main( $content, $conf )
  {
      // page object
    $this->local_cObj = $GLOBALS['TSFE']->cObj;

    $this->conf = $conf;
    $this->pi_setPiVarDefaults();
    $this->pi_loadLL();
      // Init extension configuration array
    $this->arr_extConf = unserialize( $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey] );
      // 130227, dwildt, 1-
    //$this->pi_USER_INT_obj = 1;

      // Init DRS, flexform, gpvars, HTML template, service attributes
    $this->init( );

    $content = 'Welcome Radial Search!';

    $content = '
 <style>
  .ui-autocomplete-loading {
    background: white url(\'typo3conf/ext/radialsearch/lib/icons/ajax-loader.gif\') right center no-repeat;
  }
  #city { width: 25em; }
</style>
<script>
  $(function() {

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$message: ...
	 * @return	[type]		...
	 */
    function log( message ) {
      $( "<div>" ).text( message ).prependTo( "#log" );
      $( "#log" ).scrollTop( 0 );
    }
    $( "#city" ).autocomplete({
      source: function( request, response ) {
        $.ajax({
          url: "http://ws.geonames.org/searchJSON",
          //url: "http://api.geonames.org/search",
          dataType: "jsonp",
          data: {
              featureClass    : "P"
            , style           : "full"
            , maxRows         : 12
            , name_startsWith : request.term
            , type            : "json"
            , username        : "demo"
            , country         : "DE"
            , adminCode1      : "15" // TH
            , lang            : "de"
          },
          success: function( data ) {
            if( ( typeof data[ "geonames" ] == "object" ) && ( data[ "geonames" ] !== null ) )
            {
              response( $.map( data.geonames, function( item ) {
                return {
                  label: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
                  value: item.name
                }
              }));
              return;
            }
            else
            {
              alert( typeof data[ "geonames" ] );
              alert( typeof data[ "geonames" ] == "object" );
              alert( data[ "geonames" ] );
              alert( data[ "geonames" ] !== null );
              alert( ( typeof data[ "geonames" ] == "object" ) && ( data[ "geonames" ] !== null ) );
              alert( "ERROR: geonames isn\'t any element in the returned data!" );
            }
          },
          error: function( req, error ) {
            alert( "Request failed: " + error );
          }
      });
      },
      minLength: 2,
      select: function( event, ui ) {
        log( ui.item ?
          "Selected: " + ui.item.label :
          "Nothing selected, input was " + this.value);
      },
      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      }
    });
  });
</script>
<div class="ui-widget">
  <label for="city">Your city: </label>
  <input id="city" />
  Powered by <a href="http://geonames.org">geonames.org</a>
</div>
<div class="ui-widget" style="margin-top: 2em; font-family: Arial;">
  Result:
  <div id="log" style="height: 200px; width: 300px; overflow: auto;" class="ui-widget-content"></div>
</div>
';
    $content = $this->dynamicMarkers->main( $content ); // Fill dynamic locallang or typoscript markers
    $content = preg_replace( '|###.*?###|i', '', $content ); // Finally clear not filled markers
    return $this->pi_wrapInBaseClass( $content );
  }



  /***********************************************
  *
  * Init
  *
  **********************************************/

 /**
  * init( )
  *
  * @return	void
  * @access private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function init( )
  {
      // init flexform
    $this->pi_initPIflexForm();

    $this->initInstances( );
    $this->drs->init( );
    $this->initFlexform( );

  }

 /**
  * initFlexform( )
  *
  * @return	void
  * @access private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function initFlexform( )
  {
    $this->flexform->main( );
  }

 /**
  * initInstances( )
  *
  * @return	void
  * @access private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function initInstances( )
  {
    $path2lib = t3lib_extMgm::extPath( 'radialsearch' ) . 'lib/';

    require_once( $path2lib . 'class.tx_radialsearch_drs.php' );
    $this->drs            = t3lib_div::makeInstance( 'tx_radialsearch_drs' );

    require_once( $path2lib . 'class.tx_radialsearch_dynamicmarkers.php' );
    $this->dynamicMarkers = t3lib_div::makeInstance( 'tx_radialsearch_dynamicmarkers' );

    require_once( 'class.tx_radialsearch_pi2_flexform.php' );
    $this->flexform       = t3lib_div::makeInstance( 'tx_radialsearch_pi2_flexform' );

    $this->dynamicMarkers->setParentObject( $this );
    $this->flexform->setParentObject( $this );
    $this->drs->setParentObject( $this );
  }
 /**
  * initTemplate( )
  *
  * @return	void
  * @access private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function initTemplate( )
  {
  }



  /***********************************************
  *
  * ZZ
  *
  **********************************************/

 /**
  * zz_cObjGetSingle( ) : Renders a typoscript property with cObjGetSingle, if it is an array.
  *                       Otherwise returns the property unchanged.
  *
  * @param	string		$cObj_name  : value or the name of the property like TEXT, COA, IMAGE, ...
  * @param	array		$cObj_conf  : null or the configuration of the property
  * @return	string		$value      : unchanged value or rendered typoscript property
  * @access private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function zz_cObjGetSingle( $cObj_name, $cObj_conf )
  {
    switch( true )
    {
      case( is_array( $cObj_conf ) ):
        $value = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
        break;
      case( ! ( is_array( $cObj_conf ) ) ):
      default:
        $value = $cObj_name;
        break;
    }

    return $value;
  }



}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/pi2/class.tx_radialsearch_pi2.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/pi2/class.tx_radialsearch_pi2.php']);
}
?>
