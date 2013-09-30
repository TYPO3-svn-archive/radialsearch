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
 *   66: class tx_radialsearch_pi1 extends tslib_pibase
 *
 *              SECTION: Main
 *  100:     public function main( $content, $conf )
 *  148:     public function main2( $content, $conf )
 *  182:     function log( message )
 *
 *              SECTION: Init
 *  270:     private function init( )
 *  288:     private function initFlexform( )
 *  301:     private function initInstances( )
 *  331:     private function initTemplate( )
 *
 *              SECTION: Javascript
 *  351:     private function jss( )
 *
 *              SECTION: ZZ
 *  380:     private function zz_cObjGetSingle( $cObj_name, $cObj_conf )
 *
 * TOTAL FUNCTIONS: 9
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
class tx_radialsearch_pi1 extends tslib_pibase
{

  public $extKey        = 'radialsearch';
  public $prefixId      = 'tx_radialsearch_pi1';
  public $scriptRelPath = 'pi1/class.tx_radialsearch_pi1.php';


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

 /**
  * the main method of the PlugIn
  *
  * @param	string		$content: The PlugIn content
  * @param	array		$conf: The PlugIn configuration
  * @return	The		content that is displayed on the website
  * @version    0.0.1
  * @since      0.0.1
  */
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
    $this->jss( );
    $this->css( );

    $content = '
 <style>
  .ui-autocomplete-loading {
    background: white url(\'typo3conf/ext/radialsearch/lib/icons/ajax-loader.gif\') right center no-repeat;
  }
  #city { width: 25em; }
</style>
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
    $content = $this->html( );
    
      // Fill dynamic locallang or typoscript markers
    $content  = $this->dynamicMarkers->main( $content ); 
      // Finally clear not filled markers
    $content  = preg_replace( '|###.*?###|i', '', $content ); 
    return $this->pi_wrapInBaseClass( $content );
  }

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$$content: ...
	 * @param	[type]		$conf: ...
	 * @return	[type]		...
	 */
  public function main2( $content, $conf )
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
  * CSS
  *
  **********************************************/

 /**
  * css( )  :
  *
  * @return	The		content that is displayed on the website
  * @access     private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function css( )
  {
    $conf         = $this->conf['res.']['css.']['tx_radialsearch_pi1.'];
    $path_tsConf  = 'res.css.tx_radialsearch_pi1';
    $content      = $this->cssInline( $conf, $path_tsConf );

    return $content;
  }

 /**
  * cssInlne( )  :
  *
  * @return	The		content that is displayed on the website
  * @access     private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function cssInline( $conf, $path_tsConf )
  {
    $properties = explode( '.', $path_tsConf );
    $name       = 'css_' . $properties[ count( $properties ) - 1 ];
    $path       = $conf[ 'path' ];

var_dump( __METHOD__, __LINE__ );    
      // RETURN file is loaded
    if( isset( $GLOBALS['TSFE']->additionalHeaderData[ $this->extKey . '_' . $name ] ) )
    {
      if( $this->drs->drsCss )
      {
        $prompt = 'file isn\'t added again: '. $path;
        t3lib_div::devlog( '[INFO/CSS] ' . $prompt, $this->extKey, 0 );
      }
      return true;
    }
      // RETURN file is loaded
var_dump( __METHOD__, __LINE__ );    

    $absPath = $this->getPathAbsolute( $conf, $path_tsConf );
    if( $absPath == false )
    {
      if( $this->drs->drsError )
      {
        t3lib_div::devlog('[ERROR/CSS] unproper path: ' . $path, $this->extKey, 3 );
      }
      return false;
    }

var_dump( __METHOD__, __LINE__ );    

    $content =
'  <style type="text/css">
' . implode( '', file( $absPath ) ) . '
  </style>';

      // Fill dynamic locallang or typoscript markers
    $content  = $this->dynamicMarkers->main( $content ); 
      // Finally clear not filled markers
    $content  = preg_replace( '|###.*?###|i', '', $content ); 

    $GLOBALS['TSFE']->additionalHeaderData[$this->extKey . '_' . $name ] = $content;

      // No DRS
    if( ! $this->drs->drsCss )
    {
      return true;
    }
      // No DRS

      // DRS
    $prompt = 'file is included: ' . $path;
    t3lib_div::devlog( '[INFO/CSS] ' . $prompt, $this->extKey, 0 );
    $prompt = 'Change it? Configure: \''.$path_tsConf.'\'';
    t3lib_div::devlog( '[HELP/CSS] ' . $prompt, $this->extKey, 1 );
      // DRS

    return true;
  }

/**
 * getPathAbsolute( ): Returns the absolute path of the given path
 *
 * @param	string		$path : relative or absolute path to Javascript or CSS
 * @return	string		$path : absolute path or false in case of an error
 * @access      private
 * @since       0.0.1
 * @version     0.0.1
 */
  private function getPathAbsolute( $conf, $path_tsConf )
  {
    $path = $conf[ 'path' ];
      // RETURN path is empty
    if( empty( $path ) )
    {
        // DRS
      if( $this->drs->drsWarn )
      {
        $prompt = 'file can not be included. Path is empty. Maybe it is ok.';
        t3lib_div::devlog( '[WARN/JSS] ' . $prompt, $this->extKey, 2 );
        $prompt = 'Change it? Configure: \'' . $path_tsConf . '\'';
        t3lib_div::devlog( '[HELP/JSS] ' . $prompt, $this->extKey, 1 );
      }
        // DRS
      return false;
    }
      // RETURN path is empty

      // URL or EXT:...
    $arr_parsed_url = parse_url( $path );
    if( isset( $arr_parsed_url[ 'scheme' ] ) )
    {
      if( $arr_parsed_url[ 'scheme' ] == 'EXT' )
      {
        unset( $arr_parsed_url[ 'scheme' ] );
      }
    }
      // URL or EXT:...

      // link to a file
    $bool_file_exists = true;
    if( ! isset( $arr_parsed_url['scheme'] ) )
    {
      $onlyRelative       = 1;
      $relToTYPO3_mainDir = 0;
      $absPath  = t3lib_div::getFileAbsFileName( $path, $onlyRelative, $relToTYPO3_mainDir );
      if ( ! file_exists( $absPath ) )
      {
        $bool_file_exists = false;
      }
        // relative path
      $path = preg_replace('%' . PATH_site . '%', null, $absPath);
    }
      // link to a file


      // RETURN : false, file does not exist
    if( ! $bool_file_exists )
    {
        // DRS
      if ( $this->drs->drsError )
      {
        $prompt = 'Script can not be included. File doesn\'t exist: ' . $path;
        t3lib_div::devlog( '[ERROR/JSS] ' . $prompt, $this->extKey, 3 );
        $prompt = 'Solve it? Configure: \'' . $path_tsConf . '\'';
        t3lib_div::devlog( '[HELP/JSS] ' . $prompt, $this->extKey, 1 );
      }
        // DRS
      return false;
    }
      // RETURN : false, file does not exist

    return $path;
  }
  
/**
 * getPathRelative( ): Returns the relative path. Prefix 'EXT:' will handled
 *
 * @param	string		$path : relative path with or without prefix 'EXT:'
 * @return	string		$path : relative path without prefix 'EXT:'
 * @access      private
 * @since       0.0.1
 * @version     0.0.1
 */
  private function getPathRelative( $path )
  {
      // RETURN : path hasn't any prefix EXT:
    if( substr( $path, 0, 4 ) != 'EXT:' )
    {
      return $path;
    }
      // RETURN : path hasn't any prefix EXT:

      // relative path to the JssFile as measured from the PATH_site (frontend)
    $matches  = array( );
    preg_match( '%^EXT:([a-z0-9_]*)/(.*)$%', $path, $matches );
    $path     = t3lib_extMgm::siteRelPath( $matches[ 1 ] ) . $matches[ 2 ];

    return $path;
  }




  /***********************************************
  *
  * HTML
  *
  **********************************************/

 /**
  * html( )
  *
  * @return	The		content that is displayed on the website
  * @access     private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function html( )
  {
    $content = '
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
    return $content;
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

    require_once( 'class.tx_radialsearch_pi1_flexform.php' );
    $this->flexform       = t3lib_div::makeInstance( 'tx_radialsearch_pi1_flexform' );

    require_once( $path2lib . 'class.tx_radialsearch_jss.php' );
    $this->jss            = t3lib_div::makeInstance( 'tx_radialsearch_jss' );

    $this->dynamicMarkers->setParentObject( $this );
    $this->flexform->setParentObject( $this );
    $this->jss->setParentObject( $this );
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
  * Javascript
  *
  **********************************************/

 /**
  * jss( )
  *
  * @return	void
  * @access private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function jss( )
  {
    $conf         = $this->conf['res.']['js.']['tx_radialsearch_pi1.'];
    $path_tsConf  = 'res.js.tx_radialsearch_pi1';
    $success      = $this->jss->addFile( $conf, $path_tsConf );
    unset( $success );
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

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/pi1/class.tx_radialsearch_pi1.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/pi1/class.tx_radialsearch_pi1.php']);
}
?>
