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

    // [Object]
  private $dynamicMarkers = null;
    // [Object]
  public  $drs            = null;
    // [Object]
  public  $flexform       = null;
    // [Object]
  private $jss            = null;

  public  $local_cObj     = null;
  public  $conf           = null;
  public  $arr_extConf    = null;

    // [Array] template subparts
  public  $subparts       = null;
    // [String] sword || radiusbox
  private $userfunc       = null;



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

      // Init DRS, flexform, gpvars, HTML template, service attributes
    $this->init( );
    $this->jss( );
    $this->css( );

    $content  = $this->html( );
    return $content;
  }

 /**
  * the main method of the PlugIn
  *
  * @param	string		$content: The PlugIn content
  * @param	array		$conf: The PlugIn configuration
  * @return	The		content that is displayed on the website
  * @version    0.0.1
  * @since      0.0.1
  */
  public function sword( $content, $conf )
  {
    $this->userfunc = 'sword';
    return $this->main( $content, $conf );
  }

 /**
  * the main method of the PlugIn
  *
  * @param	string		$content: The PlugIn content
  * @param	array		$conf: The PlugIn configuration
  * @return	The		content that is displayed on the website
  * @version    0.0.1
  * @since      0.0.1
  */
  public function radiusbox( $content, $conf )
  {
    $this->userfunc = 'radiusbox';
    return $this->main( $content, $conf );
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

//    $absPath = $this->getPathAbsolute( $conf, $path_tsConf );
//    if( $absPath == false )
//    {
//      if( $this->drs->drsError )
//      {
//        t3lib_div::devlog('[ERROR/CSS] unproper path: ' . $path, $this->extKey, 3 );
//      }
//      return false;
//    }
//    
//    $content =
//'  <style type="text/css">
//' . implode( '', file( $absPath ) ) . '
//  </style>';

    $css = $this->cObj->fileResource( $path );
    if( $css == false )
    {
      if( $this->drs->drsError )
      {
        t3lib_div::devlog('[ERROR/CSS] unproper path: ' . $path, $this->extKey, 3 );
      }
      return false;
    }

    $content =
'  <style type="text/css">
' . $css . '
  </style>';

      // Fill dynamic locallang or typoscript markers
    $content  = $this->dynamicMarkers->main( $content ); 

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



  /***********************************************
  *
  * HTML
  *
  **********************************************/

 /**
  * html( )  :
  *
  * @return	The		content that is displayed on the website
  * @access     private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function html( )
  {
    $content = $this->htmlRadiusbox( );
    if( $content )
    {
      $content = $this->dynamicMarkers->main( $content ); 
      //$content = $this->pi_wrapInBaseClass( $content );    
      return $content;
    }
      
    $content = $this->htmlSword( );
    if( $content )
    {
      $content = $this->dynamicMarkers->main( $content ); 
      //$content = $this->pi_wrapInBaseClass( $content );    
      return $content;
    }
      
  }

 /**
  * htmlRadiusbox( )  :
  *
  * @return	The		content that is displayed on the website
  * @access     private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function htmlRadiusbox( )
  {
    if( $this->userfunc != 'radiusbox' )
    {
      return null;
    }
    $content = $this->subpart['radiusbox'];
    $content = $this->htmlRadiusboxOptions( $content );
    return $content;
  }

 /**
  * htmlRadiusbox( )  :
  *
  * @return	The		content that is displayed on the website
  * @access     private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function htmlRadiusboxOptions( $content )
  {
    $gp     = ( array ) t3lib_div::_GP( $this->conf['gp.']['parameter'] );
    $radius = $gp[ 'select' ];

    $csvOptions = $this->conf['radiusbox.']['options'];
    $csvOptions = str_replace( ' ', null, $csvOptions );
    $arrOptions = explode( ',', $csvOptions );
    $unit       = $this->conf['radiusbox.']['unit'];
    
    $template = $this->cObj->getSubpart( $content, '###OPTION###' ) ;
   
    $options = array( );
    $search = array( 
      '0' => '###VALUE###',  
      '1' => '###SELECTED###',  
      '2' => '###LABEL###'
    );
    foreach( $arrOptions as $value )
    {
      $selected = null;
      if( $radius == $value )
      {
        $selected = ' selected="selected"';
      }
      $replace = array( 
        '0' => $value,  
        '1' => $selected,  
        '2' => $value . ' ' . $unit
      );
      $options[] = str_replace( $search, $replace, $template );
    }
    
    $strOption = implode( null, $options );

    $content = $this->cObj->substituteSubpart( $content, '###OPTION###', $strOption ) ;
    return $content;
  }

 /**
  * html( )  :
  *
  * @return	The		content that is displayed on the website
  * @access     private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function htmlSword( )
  {
    if( $this->userfunc != 'sword' )
    {
      return null;
    }
    $content = $this->subpart['sword'];
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
    $this->initTemplate( );
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
    $path     = $this->conf['res.']['html.']['tx_radialsearch_pi1.']['path'];
    $template = $this->cObj->fileResource( $path );

    // Die if there isn't any HTML template
    if( empty ( $template ) )
    {
      die( __METHOD__ . ' (' . __LINE__ . '): Template is empty!' );
    }

    $this->subpart['sword']     = $this->cObj->getSubpart( $template, '###SWORD###' );
    $this->subpart['radiusbox'] = $this->cObj->getSubpart( $template, '###RADIUSBOX###' );
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
