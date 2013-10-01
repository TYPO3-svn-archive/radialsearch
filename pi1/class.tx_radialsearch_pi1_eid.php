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
  
  public $extKey        = 'radialsearch';
  public $prefixId      = 'tx_radialsearch_pi1_eid';
  public $scriptRelPath = 'pi1/class.tx_radialsearch_pi1_eid.php';

  public $arr_extConf = null;
  
  
  
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
    $this->init( );
    $rows = $this->sql( );
    
    $arrReturn = array( 
     'places' => $rows
    );
    $json = json_encode( $arrReturn );  
    $jsonp_callback = isset( $_GET['callback'] ) ? $_GET['callback'] : null;
    $return = $jsonp_callback ? "$jsonp_callback( $json )" : $json;
    t3lib_div::devlog( '[INFO/DRS] ' . $return, 'radialsearch', 0 );
    return $return;
    
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
  
 /***********************************************
  *
  * Init
  *
  **********************************************/

 /**
  * the main method
  *
  * @return	The		content that is displayed on the website
  * @access     private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function init( )
  {
    $this->initEidTools( );
    $this->initExtConf( );
    $this->initDrs( );
  }

 /**
  * the main method
  *
  * @return	The		content that is displayed on the website
  * @access     private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function initDrs( )
  {
    $path2lib = t3lib_extMgm::extPath( 'radialsearch' ) . 'lib/';

    require_once( $path2lib . 'class.tx_radialsearch_drs.php' );
    $this->drs = t3lib_div::makeInstance( 'tx_radialsearch_drs' );
    $this->drs->setParentObject( $this );
    $this->drs->init( );
  }

 /**
  * the main method
  *
  * @return	The		content that is displayed on the website
  * @access     private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function initEidTools( )
  {
      // Initialize FE user object    
//    $feUserObj = tslib_eidtools::initFeUser(); 
    tslib_eidtools::initFeUser( ); 
      // Connect to database
    tslib_eidtools::connectDB( ); 
  }

 /**
  * the main method
  *
  * @return	The		content that is displayed on the website
  * @access     private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function initExtConf( )
  {
    $this->arr_extConf = unserialize( $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey] );
  }

  
 /***********************************************
  *
  * SQL
  *
  **********************************************/

 /**
  * sql( )  : 
  *
  * @return	
  * @access     private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function sql( )
  {
      // Get the SQL array from the GET-/POST-parameter
    $sql = ( array ) t3lib_div::_GP( 'sql' );

    $select_fields  = '*';
    $from_table     = 'tx_radialsearch_postalcodes';
    $groupBy        = null;
    $orderBy        = 'country_code, postal_code, place_name';
    $limit          = $sql[ 'limit' ];
    $where_clause   = $this->sqlWhere( );
    
    $query  = $GLOBALS['TYPO3_DB']->SELECTquery(      $select_fields, $from_table, $where_clause, $groupBy, $orderBy, $limit );
    $res    = $GLOBALS['TYPO3_DB']->exec_SELECTquery( $select_fields, $from_table, $where_clause, $groupBy, $orderBy, $limit );
    
var_dump( __METHOD__, __LINE__, $query );
      // RETURN : error in SQL query
    if( $this->sqlError( $query ) )
    {
      return false;
    }
      // RETURN : error in SQL query
    
    $rows = array( );
    while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
    {
      $rows[ ] = $row; 
    }
var_dump( __METHOD__, __LINE__, $rows );

      // RETURN : No DRS
    if( ! $this->drs->drsSql )
    {
      return $rows;
    }
      // RETURN : No DRS

      // DRS
    $query  = $GLOBALS['TYPO3_DB']->SELECTquery( $select_fields, $from_table, $where_clause, $groupBy, $orderBy, $limit );
    t3lib_div::devlog( '[INFO/SQL] ' . $query, $this->extKey, 0 );
      // DRS
    
    return $rows;
  }

 /**
  * sqlError( )  : 
  *
  * @return	
  * @access     private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function sqlError( $query )
  {
    $error  = $GLOBALS['TYPO3_DB']->sql_error( );
    
      // RETURN : no error
    if( ! $error )
    {
var_dump( __METHOD__, __LINE__ );
      return false;
    }
      // RETURN : no error
    
      // RETURN : No DRS
    if( ! $this->drs->drsError )
    {
var_dump( __METHOD__, __LINE__ );
      return true;
    }
      // RETURN : No DRS

      // DRS
    t3lib_div::devlog( '[ERROR/SQL] ' . $query, $this->extKey, 3 );
    t3lib_div::devlog( '[ERROR/SQL] ' . $error, $this->extKey, 3 );
      // DRS

var_dump( __METHOD__, __LINE__ );

    return true;
  }

 /**
  * sqlWhere( )  : 
  *
  * @return	
  * @access     private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function sqlWhere( )
  {
      // Get the SQL array from the GET-/POST-parameter
    $sql = ( array ) t3lib_div::_GP( 'sql' );
    
      // Get sword and limit
    $sword = $sql[ 'sword' ];

      // pid
    $pid    = (int) $this->arr_extConf[ 'folder.']['pid' ];
    $where  = 'pid = ' . $pid;

      // sword
    $or = array(
      '0' => 'postal_code LIKE "' . $sword . '%"',
      '1' => 'place_name LIKE "' . $sword . '%"',
      '2' => 'CONCATENATE(postal_code, " ", place_name) LIKE "' . $sword . '%"',
    );
    $where = $where . ' AND (' . implode( ' OR ', $or ) . ')';
    
      // andWhere
    $and = array( );
    foreach( ( array ) $sql[ 'andWhere' ] as $key => $value )
    {
      if( $value == '*')
      {
        continue;
      }
      $and[ ] = $key . ' LIKE "' . $value . '"';
    }
    $andWhere = implode( ' AND ', $and );
    if( $andWhere )
    {
      $andWhere = ' AND ' . $andWhere;
    }
    $andWhere = $andWhere . ' AND deleted = 0';

    return $where . $andWhere;
  }
  
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/pi1/class.tx_radialsearch_pi1_eid.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/pi1/class.tx_radialsearch_pi1_eid.php']);
}

 
$output = t3lib_div::makeInstance( 'tx_radialsearch_pi1_eid' );
echo $output->main( );


?>