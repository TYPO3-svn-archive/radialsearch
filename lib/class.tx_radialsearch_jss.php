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

/**
* The class tx_radialsearch_jss bundles methods for javascript
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
*
* @version  0.0.1
* @since    0.0.1
*
* @package    TYPO3
* @subpackage  radialsearch
*/

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   66: class tx_radialsearch_jss
 *   82:     function __construct($parentObj)
 *
 *              SECTION: CSS
 *  135:     function class_onchange($obj_ts, $arr_ts, $number_of_items)
 *  381:     function wrap_ajax_div($template)
 *
 *              SECTION: Files
 *  505:     function load_jQuery()
 *  615:     function addFileToHead( $path, $name, $keyPathTs )
 *
 *              SECTION: Helper
 *  693:     function set_arrSegment()
 *  759:     public function addCssFiles()
 *  809:     public function addFiles()
 * 1063:     public function addCssFile($path, $ie_condition, $name, $keyPathTs, $str_type, $inline )
 *
 *              SECTION: Dynamic methods
 * 1245:     function dyn_method_load_all_modes( )
 *
 * TOTAL FUNCTIONS: 10
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_radialsearch_jss
{
    // [Object] Parent object
  private $pObj = null;

  
/**
 * addFileTo(): Add a JavaScript file to header or footer section
 *
 * @param	string		$path         : Path to the Javascript
 * @param	string		$name         : For the key of additionalHeaderData
 * @param	string		$keyPathTs    : The TypoScript element path to $path for the DRS
 * @return	boolean		True: success. False: error.
 * 
 * @internal    #50069
 * @version     0.0.1
 * @since       0.0.1
 */
  public function addFile( $conf, $path_tsConf )
  {
    $bool_success   = false; 
    $placeToFooter  = $conf['placeToFooter'];

    switch( true )
    {
      case( $placeToFooter == false ):
        $bool_success = $this->addFileToHead( $conf, $path_tsConf );
        break;
      case( $placeToFooter == true ):
      default:
        $bool_success = $this->addFileToFooter( $conf, $path_tsConf );
        break;
    }
    
    unset( $placeToFooter );

    return $bool_success;
  }

/**
 * addFileToHead(): Add a JavaScript file to the HTML head
 *
 * @param	string		$path         : relative path to the Javascript
 * @param	string		$absPath      : absolute path to the Javascript
 * @param	string		$name         : For the key of additionalHeaderData
 * @param	string		$keyPathTs    : The TypoScript element path to $path for the DRS
 * @param	boolean		$inline       : Add JSS script inline
 * @param	array		$marker       : marker array
 * @return	boolean		True: success. False: error.
 * @version 4.5.10
 * @since 3.5.0
 */
  private function addFileToHead( $conf, $path_tsConf )
  {
    $properties = explode( '.', $path_tsConf );
    $name       = $properties[ count( $properties ) - 1 ];

      // RETURN : script is included
    if( isset( $GLOBALS[ 'TSFE' ]->additionalHeaderData[ $this->pObj->extKey . '_' . $name ] ) )
    {
      return true;
    }
      // RETURN : script is included

    $script = $this->getTagScript( $conf, $path_tsConf );
    $key    = $this->pObj->extKey . '_' . $name;
    $GLOBALS[ 'TSFE' ]->additionalHeaderData[ $key ] = $script;

      // No DRS
    if( ! $this->pObj->drs->drsJavascript )
    {
      return true;
    }
      // No DRS

      // DRS
    $prompt = 'script is placed at the top.';
    t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
      // DRS
  
    return true;
  }

  
/**
 * addFileToFooter(): Add a JavaScript file at the bottom of the page (the footer section)
 *
 * @param	string		$path         : relative path to the Javascript
 * @param	string		$absPath      : absolute path to the Javascript
 * @param	string		$name         : For the key of additionalHeaderData
 * @param	string		$keyPathTs    : The TypoScript element path to $path for the DRS
 * @param	boolean		$inline       : Add JSS script inline
 * @param	array		$marker       : marker array
 * @return	boolean		True: success. False: error.
 * 
 * @internal    #50069
 * @version     4.5.10
 * @since       4.5.10
 */
  private function addFileToFooter( $conf, $path_tsConf )
  {
    $properties = explode( '.', $path_tsConf );
    $name       = $properties[ count( $properties ) - 1 ];
    
    if( isset( $GLOBALS[ 'TSFE' ]->additionalFooterData[ $this->pObj->extKey . '_' . $name ] ) )
    {
      return true;
    }

      // #50069, 130716, dwildt, 3+ 
    $script = $this->getTagScript( $conf, $path_tsConf );
    $key    = $this->pObj->extKey . '_' . $name;
    $GLOBALS[ 'TSFE' ]->additionalFooterData[ $key ] = $script;

      // No DRS
    if( ! $this->pObj->drs->drsJavascript )
    {
      return true;
    }
      // No DRS

      // DRS
    $prompt = 'script is placed to footer.';
    t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
      // DRS
  
    return true;
  }


/**
 * getPathAbsolute( ): Returns the absolute path of the given path
 *
 * @param	string		$path : relative or absolute path to Javascript or CSS
 * @return	string		$path : absolute path or false in case of an error
 * 
 * @internal    #50069
 * @since       4.5.10
 * @version     4.5.10
 */
  private function getPathAbsolute( $path )
  {
      // RETURN path is empty
    if( empty( $path ) )
    {
        // DRS
      if( $this->pObj->drs->drsWarn )
      {
        $prompt = 'file can not be included. Path is empty. Maybe it is ok.';
        t3lib_div::devlog( '[WARN/JSS] ' . $prompt, $this->pObj->extKey, 2 );
        $prompt = 'Change it? Configure: \'' . $keyPathTs . '\'';
        t3lib_div::devlog( '[HELP/JSS] ' . $prompt, $this->pObj->extKey, 1 );
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
      if ( $this->pObj->drs->drsError )
      {
        $prompt = 'Script can not be included. File doesn\'t exist: ' . $path;
        t3lib_div::devlog( '[ERROR/JSS] ' . $prompt, $this->pObj->extKey, 3 );
        $prompt = 'Solve it? Configure: \''.$keyPathTs.'\'';
        t3lib_div::devlog( '[HELP/JSS] ' . $prompt, $this->pObj->extKey, 1 );
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
 * 
 * @internal    #50069
 * @since       4.5.10
 * @version     4.5.10
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
      // #32220, uherrmann, 111202
    $matches  = array( );
    preg_match( '%^EXT:([a-z0-9_]*)/(.*)$%', $path, $matches );
    $path     = t3lib_extMgm::siteRelPath( $matches[ 1 ] ) . $matches[ 2 ];
      // /#32220

    return $path;
  }

/**
 * getTagScript( ): Returns a script tag
 *
 * @param	boolean		$inline       : include the javascript inline
 * @param	string		$absPath      : absPath to the Javascript
 * @param	string		$path         : path to the Javascript
 * @param	array		$marker       : marker array
 * @return	string		$script       : The script tag
 * 
 * @internal  #50069
 * @since     4.5.10
 * @version   4.5.10
 */
  private function getTagScript( $conf, $path_tsConf )
  {
    $script = null;
    $inline = $conf['inline'];

    switch( $inline )
    {
      case( true ):
        $script = $this->getTagScriptInline( $conf, $path_tsConf );
        break;
      case( false ):
      default:
        $script = $this->getTagScriptSrc( $conf, $path_tsConf );
        break;
    }
    
    return $script;
  }

/**
 * getTagScriptInline( ): Returns a script tag
 *
 * @param	string		$absPath      : absPath to the Javascript
 * @param	array		$marker       : marker array
 * @return	string		$script       : The script tag
 * 
 * @internal  #50069
 * @since     4.5.10
 * @version   4.5.10
 */
  private function getTagScriptInline( $conf, $path_tsConf )
  {
    $path    = $conf['path'];
    $absPath = $this->getPathAbsolute( $path );
      // RETURN : there is an error with the absolute path
    if( empty( $absPath ) )
    {
      return $this->error( $conf, $path_tsConf );
    }

    $script = 
'  <script type="text/javascript">
  <!--
' . implode ( null , file( $absPath ) ) . '
  //-->
  </script>';

    $script = $this->getTagScriptInlineMarker( $conf, $script );
    
      // No DRS
    if( ! $this->pObj->drs->drsJavascript )
    {
      return $script;
    }
      // No DRS

      // DRS
    $prompt = 'file is placed inline. Source is: ' . $absPath;
    t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
    $prompt = 'Change the configuration? See: \'' . $path_tsConf . '\'';
    t3lib_div::devlog( '[HELP/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 1 );
      // DRS
  
    return $script;
  }

/**
 * getTagScriptInlineMarker( ): 
 *
 * @param	array		$marker       : marker array
 * @return	string		$script       : The script tag
 * 
 * @internal  #50069
 * @since     4.5.10
 * @version   4.5.10
 */
  private function getTagScriptInlineMarker( $conf, $script )
  {
    $marker = $conf['marker.'];
var_dump( __METHOD__, __LINE__, $marker );

    if( ! is_array( $marker ) )
    {
      return $script;
    }
    
    foreach( array_keys( ( array ) $marker ) as $key )
    {
      if( substr( $key, -1, 1 ) != '.' )
      {
        continue;
      }
        // I.e. $key is 'title.', but we like the marker name without any dot
      $keyWoDot         = substr( $key, 0, strlen( $key ) -1 );
      $hashKey          = '###' . strtoupper( $keyWoDot ) . '###';
      $coa              = $marker[ $keyWoDot ];
      $conf             = $marker[ $key ];
      $marker[$hashKey] = $this->pObj->cObj->cObjGetSingle( $coa, $conf );
    }
    
    $script = $this->pObj->cObj->substituteMarkerArray( $script, $marker );

    return $script;
  }

/**
 * getTagScriptSrc( ): Returns a script tag
 *
 * @param	string		$path         : path to the Javascript
 * @return	string		$script       : The script tag
 * 
 * @internal  #50069
 * @since     4.5.10
 * @version   4.5.10
 */
  private function getTagScriptSrc( $conf, $path_tsConf )
  {
      // #50069, 130716, dwildt, 5+ 
      // Get relative path without 'EXT:'
    $relPath = $this->getPathRelative( $conf[ 'path' ] );
    
      // RETURN : there is an error with the relative path
    if( empty( $relPath ) )
    {
      return $this->error( $conf, $path_tsConf );
    }
      // RETURN : there is an error with the relative path

    $script = '  <script src="' . $relPath . '" type="text/javascript"></script>';

      // No DRS
    if( ! $this->pObj->drs->drsJavascript )
    {
      return $script;
    }
      // No DRS

      // DRS
    $prompt = 'file is placed as source: ' . $relPath;
    t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
    $prompt = 'Change the configuration? See: \'' . $path_tsConf . '\'';
    t3lib_div::devlog( '[HELP/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 1 );
      // DRS

    return $script;
  }

  

  /***********************************************
  *
  * Prompting
  *
  **********************************************/
  
/**
 * error(): Add a JavaScript file to the HTML head
 *
 * @param	string		$path: Path to the Javascript
 * @param	string		$name: For the key of additionalHeaderData
 * @param	string		$keyPathTs: The TypoScript element path to $path for the DRS
 * @param	boolean		$inline       : Add JSS script inline
 * @return	boolean		True: success. False: error.
 * @version   0.0.1
 * @since     0.0.1
 */
  private function error( $conf, $path_tsConf )
  {
    $script = 'alert( "' . $prompt_01 . '" ); alert( "' . $prompt_02 . '" );';
    
      // No DRS
    if ( ! $this->pObj->drs->drsError )
    {
      return $script;
    }
      // No DRS

    $path = $conf[ 'path' ];
      // DRS
    $prompt_01 = 'Script can not be included: ' . $path;
    t3lib_div::devlog( '[ERROR/JSS] ' . $prompt, $this->pObj->extKey, 3 );
    $prompt_02 = 'Solve it? Configure: \''.$path_tsConf.'\'';
    t3lib_div::devlog( '[HELP/JSS] ' . $prompt, $this->pObj->extKey, 1 );
      // DRS
    
    return $script;
  }
  
  

  /***********************************************
  *
  * Set
  *
  **********************************************/

 /**
  * setParentObject( )  : Set the parent object
  *
  * @param	object		$pObj: Parent Object
  * @return	void
  * @access public
  * @version    0.0.1
  * @since      0.0.1
  */
  public function setParentObject( $pObj )
  {
    if( ! is_object( $pObj ) )
    {
      $prompt = 'ERROR: no object!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Radial Search<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );

    }
    $this->pObj = $pObj;
  }


}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/lib/class.tx_radialsearch_jss.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/lib/class.tx_radialsearch_jss.php']);
}

?>