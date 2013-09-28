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
 *   97: class tx_radialsearch_pi1 extends tslib_pibase
 *
 *              SECTION: Main
 *  154:     public function main( $content, $conf )
 *
 *              SECTION: Radial Search
 *  228:     private function radialsearchProductAdd( )
 *  250:     private function radialsearchProductDelete( )
 *  266:     private function radialsearchRendered( )
 *  280:     private function radialsearchUpdate( )
 *
 *              SECTION: Clean
 *  323:     private function clean( )
 *
 *              SECTION: Debug
 *  345:     private function debugOutputBeforeRunning( )
 *
 *              SECTION: Init
 *  382:     private function init( )
 *  402:     private function initAccessByIp( )
 *  448:     private function initDatabase( )
 *  461:     private function initDatabaseTable( )
 *  512:     private function initFlexform( )
 *  525:     private function initGetPost( )
 *  581:     private function initGetPostCid( )
 *  631:     private function initInstances( )
 *  681:     private function initPid( )
 *  720:     private function initPowermail( )
 *  733:     private function initTemplate( )
 *
 *              SECTION: Send
 *  754:     private function send( )
 *  768:     private function sendCustomer( )
 *  783:     private function sendCustomerDeliveryorder( )
 *  808:     private function sendCustomerInvoice( )
 *  833:     private function sendCustomerTerms( )
 *  858:     private function sendVendor( )
 *  873:     private function sendVendorDeliveryorder( )
 *  898:     private function sendVendorInvoice( )
 *  923:     private function sendVendorTerms( )
 *
 *              SECTION: Update Wizard
 *  957:     private function updateWizard( $content )
 *
 *              SECTION: ZZ
 * 1002:     private function zz_cObjGetSingle( $cObj_name, $cObj_conf )
 *
 * TOTAL FUNCTIONS: 29
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * 
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

    $content = 'Welcome Radial Search!';
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
    $this->drs              = t3lib_div::makeInstance( 'tx_radialsearch_drs' );

    require_once( $path2lib . 'class.tx_radialsearch_dynamicmarkers.php' );
    $this->dynamicMarkers   = t3lib_div::makeInstance( 'tx_radialsearch_dynamicmarkers' );

    require_once( 'class.tx_radialsearch_pi1_flexform.php' );
    $this->flexform         = t3lib_div::makeInstance( 'tx_radialsearch_pi1_flexform' );

    $this->dynamicMarkers->setParentObject( $this );
    $this->drs->setParentObject( $this );
    $this->flexform->setParentObject( $this );
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

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/pi1/class.tx_radialsearch_pi1.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/pi1/class.tx_radialsearch_pi1.php']);
}
?>
