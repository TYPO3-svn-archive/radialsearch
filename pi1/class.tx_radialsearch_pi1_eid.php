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

if( ! defined( 'PATH_typo3conf' ) ) die ( 'Could not access this script directly!' );

require_once( PATH_tslib . 'class.tslib_pibase.php' );
 
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
 * Class tx_radialsearch_pi1_eid
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	radialsearch
 * @version	0.0.1
 * @since       0.0.1
 */
class tx_radialsearch_pi1_eid extends tslib_pibase {
  
 /***********************************************
  *
  * Main
  *
  **********************************************/

 /**
  * the main method
  *
  * @return	The		content that is displayed on the website
  * @access     public
  * @version    0.0.1
  * @since      0.0.1
  */
  public function main( )
  {
    $feUserObj = tslib_eidtools::initFeUser(); // Initialize FE user object    
    tslib_eidtools::connectDB(); //Connect to database
    //echo "<pre>", print_r($GLOBALS["TYPO3_DB"]), "</pre>";
    $return = array(
      'TYPO3_DB'  => $GLOBALS[ 'TYPO3_DB' ],
      '_GET'      => $GLOBALS[ '_GET'     ],
      '_POST'     => $GLOBALS[ '_POST'    ]
    );
    $json = json_encode( $return );  
    $jsonp_callback = isset($_GET['callback']) ? $_GET['callback'] : null;
    $return = $jsonp_callback ? "$jsonp_callback($json)" : $json;
    t3lib_div::devlog( '[INFO/DRS] ' . $return, 'radialsearch', 0 );
    return $return;
  }
}
 
$output = t3lib_div::makeInstance( 'tx_radialsearch_pi1_eid' );
echo $output->main( );


?>
