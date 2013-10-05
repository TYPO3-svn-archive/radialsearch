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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   56: class tx_radialsearch_interface
 *   95:     public function init( )
 *  119:     private function initByExtmngr( )
 *  160:     private function initByFlexform( )
 *
 *              SECTION: Set
 *  203:     public function setParentObject( $pObj )
 *
 *              SECTION: ZZ
 *  234:     public function zzDrsPromptsTrue( )
 *
 * TOTAL FUNCTIONS: 5
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * Library DRS for the 'radialsearch' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	radialsearch
 * @version	0.0.1
 * @since       0.0.1
 */
class tx_radialsearch_interface
{

  public $prefixId = 'tx_radialsearch_interface';

  // same as class name
  public $scriptRelPath = 'interface/class.tx_radialsearch_interface.php';

  // path to this script relative to the extension dir.
  public $extKey = 'radialsearch';

    // [Object] Parent object
  private $pObj   = null;
    // [Object] Filter object
  private $filter = null;
  
    // [Array] Current configuration of the extension manager
  private $extConf  = null;

  
  
 /***********************************************
  *
  * andWhere
  *
  **********************************************/

/**
 * andWhere( ): 
 *
 * @return	string    $andWhere : andWhere clause
 * @access public
 * @version 0.0.1
 * @since   0.0.1
 */
  public function andWhere( )
  {
    $this->init( );

    $tx_radialsearch_pi1  = ( array ) t3lib_div::_GP( 'tx_radialsearch_pi1' );
    $sword = $tx_radialsearch_pi1[ 'sword' ];
$this->pObj->dev_var_dump( $sword );
    switch( true )
    {
      case( $sword === null ):
      case( $sword == '*' ):
        return null;
      default:
          // Follow the workflow
        break;
    }

    $pid      = ( int ) $this->extConf[ 'database.']['pid' ];

    $andWhere = '' .
' tx_radialsearch_postalcodes.pid = ' . $pid . ' 
' . $this->andWhereFilter( ) . '  
' . $this->andWhereSword( ) . '  
' . $this->andWhereEnabledFields( ) . ' 
';

    return $andWhere;
  }

/**
 * andWhereEnabledFields( ): 
 *
 * @return	string		
 * @access  private
 * @version 0.0.1
 * @since   0.0.1
 */
  private function andWhereEnabledFields( )
  {
    $andWhere = $this->pObj->cObj->enableFields( 'tx_radialsearch_postalcodes' );
    return $andWhere;
  }
  
/**
 * andWhereFilter( ): 
 *
 * @return	string    $andWhere : andWhere clause
 * @access private
 * @version 0.0.1
 * @since   0.0.1
 */
  private function andWhereFilter( )
  {
    $arrAndWhere = array( );
    
    $table = $this->filter->radialsearchTable;

    $confFilter = $this->filter->conf_view[ 'filter.' ][ $table . '.' ][ 'conf.' ][ 'filter.' ];
    foreach( array_keys( ( array ) $confFilter ) as $filter )
    {
        // CONTINUE : filter has an dot
      if( rtrim( $filter, '.') != $filter )
      {
        continue;
      }
        // CONTINUE : field has an dot
      
      $name   = $confFilter[ $filter ];
      $conf   = $confFilter[ $filter . '.' ];
      $value  = $this->pObj->cObj->cObjGetSingle( $name, $conf );

      switch( true )
      {
        case( $value === null ):
        case( $value == '*' ):
            // do nothing
          break;
        default:
          $arrAndWhere[ ] = $filter . ' LIKE ' . $value; 
          break;
      }
    }
    
    if( empty( $arrAndWhere ) )
    {
      return null;
    }
    
    $andWhere = ' AND ' . implode( ' AND ', $arrAndWhere );
    return $andWhere;
  }

/**
 * andWhereSword( ): 
 *
 * @return	string    $andWhere : andWhere clause
 * @access private
 * @version 0.0.1
 * @since   0.0.1
 */
  private function andWhereSword( )
  {
    $tx_radialsearch_pi1  = ( array ) t3lib_div::_GP( 'tx_radialsearch_pi1' );
    $sword = $tx_radialsearch_pi1[ 'sword' ];
    
    $sword = $GLOBALS['TYPO3_DB']->fullQuoteStr( $sword, 'tx_radialsearch_postalcodes' ) ;
    

    $andWhere = '' .
'AND
(
      tx_radialsearch_postalcodes.postal_code LIKE "' . $sword . '%" 
  OR  tx_radialsearch_postalcodes.place_name LIKE "' . $sword . '%" 
  OR  CONCAT(tx_radialsearch_postalcodes.postal_code, " ", tx_radialsearch_postalcodes.place_name) LIKE "' . $sword . '%"
)';

    return $andWhere;
  }

 



  /***********************************************
  *
  * Init
  *
  **********************************************/
  
/**
 * init( ): 
 *
 * @return	boolen        true
 * @access  private
 * @version 0.0.1
 * @since   0.0.1
 */
  private function init( )
  {
    if( ! is_object( $this->pObj ) )
    {
      $prompt = 'ERROR: no object!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Radial Search (Umkreissuche)<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );

    }

    if( ! is_object( $this->filter ) )
    {
      $prompt = 'ERROR: no object!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Radial Search (Umkreissuche)<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
    $this->filter = $this->pObj->objFltr4x;

    $this->initExtConf( );
    return true;
  }

/**
 * initExtConf( ): 
 *
 * @return	boolen        true
 * @access  private
 * @version 0.0.1
 * @since   0.0.1
 */
  private function initExtConf( )
  {
    $this->extConf  = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['radialsearch']);
    return true;
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

 /**
  * setFilterObject( )  : 
  *
  * @param	object
  * @return	void
  * @access public
  * @version    0.0.1
  * @since      0.0.1
  */
  public function setFilterObject( $filterObj )
  {
    if( ! is_object( $filterObj ) )
    {
      $prompt = 'ERROR: no object!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Radial Search<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );

    }
    $this->filter = $filterObj;
  }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/interface/class.tx_radialsearch_interface.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/interface/class.tx_radialsearch_interface.php']);
}
?>
