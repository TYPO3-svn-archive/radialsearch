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
  private $pObj     = null;
    // [Object] Filter object
  private $currentObj   = null;
    // [Boolean] True, if sword is set. False if not.
  private $isSword  = null;
  
    // [Array] Current configuration of the extension manager
  private $extConf  = null;

  
  
 /***********************************************
  *
  * and
  *
  **********************************************/

/**
 * andFrom( ) : Returns the andFrom statement.
 *              Returns null, if there isn't any sword.
 *
 * @return	string    $andFrom : andFrom statement
 * @access public
 * @version 0.0.1
 * @since   0.0.1
 */
  public function andFrom( )
  {
    $this->init( );

      // RETURN : there isn't any sword
    if( ! $this->isSword )
    {
      return null;
    }
      // RETURN : there isn't any sword

      // Set the andFrom statement
    $andFrom = '' .
' CROSS JOIN  tx_radialsearch_postalcodes ';

    return $andFrom;
  }
  
/**
 * andHaving( ) : Returns the andHaving statement.
 *              Returns null, if there isn't any sword.
 *
 * @return	string    $andHaving : andHaving statement
 * @access public
 * @version 0.0.1
 * @since   0.0.1
 */
  public function andHaving( )
  {
    $this->init( );

      // RETURN : there isn't any sword
    if( ! $this->isSword )
    {
      return null;
    }
      // RETURN : there isn't any sword

    $tx_radialsearch_pi1  = ( array ) t3lib_div::_GP( 'tx_radialsearch_pi1' );

    $radius = ( int ) $tx_radialsearch_pi1[ 'radius' ];

      // Set the andHaving statement
    $andHaving = '' .
' HAVING distance < ' . $radius . ' ';

    return $andHaving;
  }
  
/**
 * andOrderBy( ) : Returns the andOrderBy statement.
 *              Returns null, if there isn't any sword.
 *
 * @return	string    $andOrderBy : andOrderBy statement
 * @access public
 * @version 0.0.1
 * @since   0.0.1
 */
  public function andOrderBy( )
  {
    $this->init( );

      // RETURN : there isn't any sword
    if( ! $this->isSword )
    {
      return null;
    }
      // RETURN : there isn't any sword


      // Set the andOrderBy statement
    $andOrderBy = '' .
' distance ';

    return $andOrderBy;
  }
  
/**
 * andSelect( ) : Returns the andSelect statement.
 *                Returns null, if there isn't any sword.
 *
 * @return	string    $andSelect : andSelect statement
 * @access public
 * @version 0.0.1
 * @since   0.0.1
 */
  public function andSelect( )
  {
    $this->init( );

      // RETURN : there isn't any sword
    if( ! $this->isSword )
    {
      return null;
    }
      // RETURN : there isn't any sword

    $km = ( double ) $this->extConf[ 'earth.']['radius.' ]['km' ];
    if( empty ( $km ) )
    {
        //  http://de.wikipedia.org/wiki/Erdradius
      $km = 6378.2;
    }

    $table          = $this->currentObj->radialsearchTable;
    $constanteditor = $this->currentObj->conf_view[ 'filter.' ][ $table . '.' ][ 'conf.' ][ 'constanteditor.' ];
    $destLat        = $constanteditor[ 'lat' ];
    $destLon        = $constanteditor[ 'lon' ];
    
      // Set the andSelect statement
    $andSelect = '' .
', ACOS
(
    SIN( RADIANS( tx_radialsearch_postalcodes.latitude  ) ) * SIN( RADIANS( ' . $destLat . ' ) ) 
  + COS( RADIANS( tx_radialsearch_postalcodes.latitude  ) ) * COS( RADIANS( ' . $destLat . ' ) )
  * COS( RADIANS( tx_radialsearch_postalcodes.longitude )   - RADIANS( ' . $destLon . ' ) )
) 
* ' . $km . ' AS distance
';

    return $andSelect;
  }

  
  
 /***********************************************
  *
  * andWhere
  *
  **********************************************/

/**
 * andWhere( )  : Returns the andWhere clause.
 *                Returns null, if there isn't set any sword.
 * 
 * @param       boolean   $withDistance :
 * @return	string    $andWhere     : andWhere clause
 * @access public
 * @version 0.0.1
 * @since   0.0.1
 */
  public function andWhere( $withDistance=false )
  {
    $this->init( );

      // RETURN : there isn't any sword
    if( ! $this->isSword )
    {
      return null;
    }
      // RETURN : there isn't any sword

      // Get the pid
    $pid = ( int ) $this->extConf[ 'database.']['pid' ];

      // Set the andWhere statement
    $andWhere = '' .
' AND tx_radialsearch_postalcodes.pid = ' . $pid . ' 
' . $this->andWhereFilter( ) . '  
' . $this->andWhereSword( ) . '  
' . $this->andWhereEnabledFields( ) . ' 
' . $this->andWhereDistance( $withDistance ) . ' 
';

    return $andWhere;
  }

/**
 * andWhereDistance( ): 
 *
 * @param       boolean   $withDistance :
 * @return	string    $andWhere : andWhere clause
 * @access private
 * @version 0.0.1
 * @since   0.0.1
 */
  private function andWhereDistance( $withDistance )
  {
    if( ! $withDistance )
    {
      return null;
    }
    
    $tx_radialsearch_pi1  = ( array ) t3lib_div::_GP( 'tx_radialsearch_pi1' );
    $maxRadius            = ( int ) $tx_radialsearch_pi1[ 'radius' ];
    
    $km = ( double ) $this->extConf[ 'earth.']['radius.' ]['km' ];
    if( empty ( $km ) )
    {
        //  http://de.wikipedia.org/wiki/Erdradius
      $km = 6378.2;
    }

    $table          = $this->currentObj->radialsearchTable;
    $constanteditor = $this->currentObj->conf_view[ 'filter.' ][ $table . '.' ][ 'conf.' ][ 'constanteditor.' ];
    $destLat        = $constanteditor[ 'lat' ];
    $destLon        = $constanteditor[ 'lon' ];
$this->pObj->dev_var_dump( $table, $this->currentObj );
    
    $andWhere = '' .
'AND 
( 
  ACOS
  (
      SIN( RADIANS( tx_radialsearch_postalcodes.latitude  ) ) * SIN( RADIANS( ' . $destLat . ' ) ) 
    + COS( RADIANS( tx_radialsearch_postalcodes.latitude  ) ) * COS( RADIANS( ' . $destLat . ' ) )
    * COS( RADIANS( tx_radialsearch_postalcodes.longitude )   - RADIANS( ' . $destLon . ' ) )
  ) 
  * ' . $km . ' 
) < ' . $maxRadius . ' 
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
    
    $table = $this->currentObj->radialsearchTable;

    $confFilter = $this->currentObj->conf_view[ 'filter.' ][ $table . '.' ][ 'conf.' ][ 'filter.' ];
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
    $sword = $GLOBALS['TYPO3_DB']->quoteStr( $sword, 'tx_radialsearch_postalcodes' ) ;

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
 * @return	boolean        true
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

    if( ! is_object( $this->currentObj ) )
    {
      $prompt = 'ERROR: no object!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Radial Search (Umkreissuche)<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }

    $this->initExtConf( );
    $this->initSword( );
    return true;
  }

/**
 * initExtConf( ): 
 *
 * @return	boolean        true
 * @access  private
 * @version 0.0.1
 * @since   0.0.1
 */
  private function initExtConf( )
  {
    $this->extConf  = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['radialsearch']);
    return true;
  }

/**
 * initSword( ): Set the class var $isSword
 *
 * @return	boolean        true, if sword is set. False, if not.
 * @access  private
 * @version 0.0.1
 * @since   0.0.1
 */
  private function initSword( )
  {
      // RETURN : sword is set before
    if( $this->isSword !== null )
    {
      return $this->isSword;
    }
      // RETURN : sword is set before

      // Get the current sword
    $tx_radialsearch_pi1  = ( array ) t3lib_div::_GP( 'tx_radialsearch_pi1' );
    $sword = $tx_radialsearch_pi1[ 'sword' ];
    
      // Set class var $isSword
    switch( true )
    {
      case( $sword === null ):
      case( $sword == '' ):
      case( $sword == '*' ):
        $this->isSword = false;
        break;
      default:
        $this->isSword = true;
        break;
    }
    unset( $sword );
      // Set class var $isSword

    return $this->isSword;
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
  * setCurrentObject( )  : 
  *
  * @param	object
  * @return	void
  * @access public
  * @version    0.0.1
  * @since      0.0.1
  */
  public function setCurrentObject( $currentObj )
  {
    if( ! is_object( $currentObj ) )
    {
      $prompt = 'ERROR: no object!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Radial Search<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );

    }
    $this->currentObj = $currentObj;
  }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/interface/class.tx_radialsearch_interface.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/interface/class.tx_radialsearch_interface.php']);
}
?>
