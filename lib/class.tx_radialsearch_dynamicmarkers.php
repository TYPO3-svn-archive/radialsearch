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
 * Library Dynmaic Markers for the 'radialsearch' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage    radialsearch
 * @version     0.0.1
 * @since       0.0.1
 */

class tx_radialsearch_dynamicmarkers extends tslib_pibase {

  public $extKey = 'radialsearch';
    // Path to pi1 to get locallang.xml from pi1 folder
  public $scriptRelPath = 'pi1/class.tx_radialsearch_pi1.php';
    // prefix for automatic locallangmarker
  private $locallangmarker_prefix = array (  
    '_LOCAL_LANG_',     // prefix for HTML template part
    null                // prefix for typoscript part
  );
    // prefix for automatic typoscriptmarker
  private $typoscriptmarker_prefix = array ( 
    '_HTMLMARKER_',     // prefix for HTML template part
    '_HTMLMARKER'       // prefix for typoscript part
  );
  
    // [Object] local cObject
  public $cObj = null;
    // [Array] Current TypoScript configuration of radialsearch
  public $conf = null;
    // [Array] Current content of radialsearch
  private $content = null;
    // [Object] The parent object
  private $pObj = null;

 /**
  * main( ): replace typoscript- and locallang markers
  *
  * @return	void
  * @access public
  * @version    0.0.1
  * @since      0.0.1
  */

  public function main( $content ) 
  {
      // config
    $this->conf = $this->pObj->conf;
    $this->cObj = $this->pObj->cObj;
    $this->content = $content;
    $this->pi_loadLL();

      // 1. replace locallang markers
      // Automaticly fill locallangmarkers with fitting value of locallang.xml
    $this->content  = preg_replace_callback
                      (
                        '#\#\#\#' . $this->locallangmarker_prefix[0] . '(.*)\#\#\##Uis', // regulare expression
                        array
                        (
                          $this, 
                          'DynamicLocalLangMarker'
                        ), // open function
                        $this->content // current content
                      );

      // 2. replace typoscript markers
      // Automaticly fill locallangmarkers with fitting value of locallang.xml
    $this->content  = preg_replace_callback
                      (
                        '#\#\#\#' . $this->typoscriptmarker_prefix[0] . '(.*)\#\#\##Uis', // regulare expression
                        array
                        (
                          $this, 
                          'DynamicTyposcriptMarker'
                        ), // open function
                        $this->content // current content
                      );

    if( ! empty( $this->content ) )
    {
      return $this->content;
    }
  }

 /**
  * DynamicLocalLangMarker( ) : Get automaticly a marker from locallang.xml 
  *
  * @return	void
  * @access private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function DynamicLocalLangMarker( $array ) 
  {
    if( ! empty( $array[1] ) )
    {
      $string = $this->pi_getLL
                ( 
                  strtolower( $this->locallangmarker_prefix[1] . $array[1] ), 
                  '<i>' . strtolower( $array[1] ) . '</i>'
                ); 
    }

    if( ! empty( $string ) )
    {
      return $string;
    }
  }

 /**
  * DynamicTyposcriptMarker( )  : Get automaticly a marker from typoscript
  *
  * @return	void
  * @access private
  * @version    0.0.1
  * @since      0.0.1
  */
 private function DynamicTyposcriptMarker( $array ) 
  {
    if( $this->conf[$this->typoscriptmarker_prefix[1] . '.'][strtolower( $array[1] )] )
    { 
      $string = $this->cObj->cObjGetSingle
                (
                  $this->conf[$this->typoscriptmarker_prefix[1] . '.'][strtolower( $array[1] )], 
                  $this->conf[$this->typoscriptmarker_prefix[1] . '.'][strtolower( $array[1]) . '.'] 
                );
    }

    if( ! empty( $string ) )
    {
      return $string;
    }
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

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/lib/class.tx_radialsearch_dynamicmarkers.php']) 
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/lib/class.tx_radialsearch_dynamicmarkers.php']);
}
?>