<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2013-2015 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * ************************************************************* */

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
 * @version 6.1.1
 * @since       0.0.1
 */
class tx_radialsearch_interface
{

  public $prefixId = 'tx_radialsearch_interface';
  // same as class name
  public $scriptRelPath = 'interface/class.tx_radialsearch_interface.php';
  // path to this script relative to the extension dir.
  public $extKey = 'radialsearch';
  // [Array] Current configuration of the extension manager
  private $extConf = null;
  // [Object] Parent object
  private $pObj = null;
  // [Boolean] Prompt to DRS?
  private $drs = null;
  // [Boolean] True, if maxRadius should used
  private $andWithMaxRadius = null;
  // [Boolean] True, if sword is set. False if not.
  private $isSword = null;
  // [Array] Array with elemenst lat and lon
  private $confFields = null;
  // [Array] TypoScript configuration of the filter
  private $confFilter = null;
  // [Array] TypoScript configuration of GETPOST
  private $confGP = null;

  /*   * *********************************************
   *
   * and
   *
   * ******************************************** */

  /**
   * andFrom( ) : Returns the andFrom statement.
   *              Returns null, if there isn't any sword.
   *
   * @return	string    $andFrom : andFrom statement
   * @access public
   * @version 0.0.1
   * @since   0.0.1
   */
  public function andFrom()
  {
    $this->init();

    // RETURN : there isn't any sword
    if ( !$this->isSword )
    {
      return null;
    }
    // RETURN : there isn't any sword
    // Set the andFrom statement
    $andFrom = '' .
            ' CROSS JOIN  tx_radialsearch_postalcodes ';

    // RETURN : no DRS
    if ( !$this->drs )
    {
      return $andFrom;
    }
    // RETURN : no DRS
    // DRS
    $prompt = $andFrom;
    t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->extKey, 0 );
    // DRS

    return $andFrom;
  }

  /**
   * andHaving( ) : Returns the andHaving statement.
   *              Returns null, if there isn't any sword.
   *
   * @return	string    $andHaving : andHaving statement
   * @access public
   * @version 6.1.0
   * @since   0.0.1
   */
  public function andHaving()
  {
    $this->init();

    // RETURN : there isn't any sword
    if ( !$this->isSword )
    {
      return null;
    }
    // RETURN : there isn't any sword
    // RETURN : without max radius
    if ( !$this->andWithMaxRadius() )
    {
      //return null;
      // #61797: Code below hasn't the wanted effect.
      $uid = $this->confFields[ 'uid' ];
      $andHaving = ''
              . ' GROUP BY ' . $uid . ' '
      ;
      return $andHaving;
    }
    // RETURN : without max radius

    $distance = $this->confFields[ 'distance' ];
    // #61797, 150327, dwildt, 1+
    $uid = $this->confFields[ 'uid' ];

    $gp = ( array ) t3lib_div::_GP( $this->confGP[ 'parameter' ] );
    $maxRadius = ( int ) $gp[ $this->confGP[ 'select' ] ];

    // Set the andHaving statement
    $andHaving = ''
            . ' GROUP BY ' . $uid . ' ' // #61797, 150327, dwildt, +
            . ' HAVING ' . $distance . ' < ' . ( $maxRadius + 0 ) . ' '
    ;

    // RETURN : no DRS
    if ( !$this->drs )
    {
      return $andHaving;
    }
    // RETURN : no DRS
    // DRS
    $prompt = $andHaving;
    t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->extKey, 0 );
    // DRS

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
  public function andOrderBy()
  {
    $this->init();

    // RETURN : there isn't any sword
    if ( !$this->isSword )
    {
      return null;
    }
    // RETURN : there isn't any sword


    $distance = $this->confFields[ 'distance' ];

    // Set the andOrderBy statement
    $andOrderBy = '' .
            ' ' . $distance . ' ';

    // RETURN : no DRS
    if ( !$this->drs )
    {
      return $andOrderBy;
    }
    // RETURN : no DRS
    // DRS
    $prompt = $andOrderBy;
    t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->extKey, 0 );
    // DRS

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
  public function andSelect()
  {
    $this->init();

    // RETURN : there isn't any sword
    if ( !$this->isSword )
    {
      return null;
    }
    // RETURN : there isn't any sword

    $km = ( double ) $this->extConf[ 'earth.' ][ 'radius.' ][ 'km' ];
    if ( empty( $km ) )
    {
      //  http://de.wikipedia.org/wiki/Erdradius
      $km = 6378.2;
    }

    $distance = $this->confFields[ 'distance' ];
    $destLat = $this->confFields[ 'lat' ];
    $destLon = $this->confFields[ 'lon' ];

    // Set the andSelect statement
    $andSelect = '' .
            ', ACOS
(
    SIN( RADIANS( tx_radialsearch_postalcodes.latitude  ) ) * SIN( RADIANS( ' . $destLat . ' ) )
  + COS( RADIANS( tx_radialsearch_postalcodes.latitude  ) ) * COS( RADIANS( ' . $destLat . ' ) )
  * COS( RADIANS( tx_radialsearch_postalcodes.longitude )   - RADIANS( ' . $destLon . ' ) )
)
* ' . $km . ' AS \'' . $distance . '\'
';

    // RETURN : no DRS
    if ( !$this->drs )
    {
      return $andSelect;
    }
    // RETURN : no DRS
    // DRS
    $prompt = $andSelect;
    t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->extKey, 0 );
    // DRS

    return $andSelect;
  }

  /*   * *********************************************
   *
   * andWhere
   *
   * ******************************************** */

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
  public function andWhere( $withDistance = false )
  {
    $this->init();

    // RETURN : there isn't any sword
    if ( !$this->isSword )
    {
      return null;
    }
    // RETURN : there isn't any sword
    // Get the pid
    $pid = ( int ) $this->extConf[ 'database.' ][ 'pid' ];

    // Set the andWhere statement
    $andWhere = '' .
            ' AND tx_radialsearch_postalcodes.pid = ' . $pid . '
' . $this->andWhereFilter() . '
' . $this->andWhereSword() . '
' . $this->andWhereEnabledFields() . '
' . $this->andWhereDistance( $withDistance ) . '
';

    // RETURN : no DRS
    if ( !$this->drs )
    {
      return $andWhere;
    }
    // RETURN : no DRS
    // DRS
    $prompt = $andWhere;
    t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->extKey, 0 );
    // DRS

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
    if ( !$withDistance )
    {
      return null;
    }

    $smallerThanMaxRadius = null;
    if ( $this->andWithMaxRadius() )
    {
      $gp = ( array ) t3lib_div::_GP( $this->confGP[ 'parameter' ] );
      $maxRadius = ( int ) $gp[ $this->confGP[ 'select' ] ];
      $smallerThanMaxRadius = ' < ' . ( $maxRadius + 0 );
    }


    $km = ( double ) $this->extConf[ 'earth.' ][ 'radius.' ][ 'km' ];
    if ( empty( $km ) )
    {
      //  http://de.wikipedia.org/wiki/Erdradius
      $km = 6378.2;
    }

    // #61797: Code below hasn't the wanted effect.
//    if ( $smallerThanMaxRadius === null )
//    {
//      $smallerThanMaxRadius = ' <= ' . ( $km + 0 );
//      $smallerThanMaxRadius = ' <= ' . 1;
//    }

    $destLat = $this->confFields[ 'lat' ];
    $destLon = $this->confFields[ 'lon' ];

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
) ' . $smallerThanMaxRadius . '
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
  private function andWhereEnabledFields()
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
  private function andWhereFilter()
  {
    $arrAndWhere = array();

    foreach ( array_keys( ( array ) $this->confFilter ) as $filter )
    {
      // CONTINUE : filter has an dot
      if ( rtrim( $filter, '.' ) != $filter )
      {
        continue;
      }
      // CONTINUE : field has an dot

      $name = $this->confFilter[ $filter ];
      $conf = $this->confFilter[ $filter . '.' ];
      $value = $this->pObj->cObj->cObjGetSingle( $name, $conf );

      switch ( true )
      {
        case( $value === null ):
        case( $value == '*' ):
        case( $value == '"*"' ):
          // do nothing
          break;
        default:
          $arrAndWhere[] = $filter . ' LIKE ' . $value;
          break;
      }
    }

    if ( empty( $arrAndWhere ) )
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
   * @version 6.1.1
   * @since   0.0.1
   */
  private function andWhereSword()
  {
    $gp = ( array ) t3lib_div::_GP( $this->confGP[ 'parameter' ] );

    $sword = $gp[ $this->confGP[ 'input' ] ];
    // #i0014, dwildt, 2+
    $sword = strip_tags( $sword );
    $sword = htmlspecialchars( $sword );
    $sword = $GLOBALS[ 'TYPO3_DB' ]->quoteStr( $sword, 'tx_radialsearch_postalcodes' );

    $andWhere = '' .
            'AND
(
      tx_radialsearch_postalcodes.postal_code LIKE "' . $sword . '%"
  OR  tx_radialsearch_postalcodes.place_name LIKE "' . $sword . '%"
  OR  CONCAT(tx_radialsearch_postalcodes.postal_code, " ", tx_radialsearch_postalcodes.place_name) LIKE "' . $sword . '%"
)';

    return $andWhere;
  }

  /**
   * andWithMaxRadius( )  :
   *
   * @return	booelan
   * @internal    #52486
   * @access  private
   * @version 0.0.1
   * @since   0.0.1
   */
  private function andWithMaxRadius()
  {
    if ( $this->andWithMaxRadius !== null )
    {
      return $this->andWithMaxRadius;
    }

    $searchmode = $this->confFields[ 'searchmode' ];
    switch ( $searchmode )
    {
      case( 'Within the radius only' ):
        $this->andWithMaxRadius = true;
        break;
      case( 'Within and without the radius' ):
        $this->andWithMaxRadius = false;
        break;
      default:
        $prompt = 'ERROR: searchmode isn\'t defined!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Radial Search<br />' . PHP_EOL .
                __METHOD__ . ' (' . __LINE__ . ')';
        die( $prompt );
        break;
    }

    return $this->andWithMaxRadius;
  }

  /*   * *********************************************
   *
   * Init
   *
   * ******************************************** */

  /**
   * init( ):
   *
   * @return	boolean        true
   * @access  private
   * @version 0.0.1
   * @since   0.0.1
   */
  private function init()
  {
    $this->initRequirements();
    $this->initExtConf();
    $this->initDRS();
    $this->initSword();
    return true;
  }

  /**
   * initDRS( ):
   *
   * @return	boolean        true
   * @access  private
   * @version 0.0.1
   * @since   0.0.1
   */
  private function initDRS()
  {
    if ( $this->drs !== null )
    {
      return;
    }

    switch ( $this->extConf[ 'drs.' ][ 'enabled' ] )
    {
      case( 'Disabled' ):
      case( null ):
        $this->drs = false;
        return;
      case( 'Enabled (for debugging only!)' ):
        // Follow the workflow
        break;
      default:
        $prompt = 'Error: drs.enabled is undefined.<br />
          value is ' . $this->pObj->arr_extConf[ 'drs.' ][ 'enabled' ] . '<br />
          <br />
          ' . __METHOD__ . ' line(' . __LINE__ . ')';
        die( $prompt );
    }

    // Set prompt flags
    $this->drs = true;

    // DRS
    $prompt = 'The DRS - Development Reporting System is enabled: ' . $this->extConf[ 'drs.' ][ 'enabled' ];
    t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->extKey, 0 );
    $prompt = 'The DRS is enabled by the extension manager.';
    t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->extKey, 0 );
  }

  /**
   * initExtConf( ):
   *
   * @return	boolean        true
   * @access  private
   * @version 0.0.1
   * @since   0.0.1
   */
  private function initExtConf()
  {
    $this->extConf = unserialize( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXT' ][ 'extConf' ][ 'radialsearch' ] );
    return true;
  }

  /**
   * initRequirements( ):
   *
   * @return	void
   * @access  private
   * @version 0.0.1
   * @since   0.0.1
   */
  private function initRequirements()
  {
    if ( !is_object( $this->pObj ) )
    {
      $prompt = 'ERROR: no object!<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Radial Search (Umkreissuche)<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }

    if ( !is_array( $this->confFields ) )
    {
      $prompt = 'ERROR: no array!<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Radial Search<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }

    if ( !is_array( $this->confFilter ) )
    {
      $prompt = 'ERROR: no array!<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Radial Search<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
  }

  /**
   * initSword( ): Set the class var $isSword
   *
   * @return	boolean        true, if sword is set. False, if not.
   * @access  private
   * @version 0.0.1
   * @since   0.0.1
   */
  private function initSword()
  {
    // RETURN : sword is set before
    if ( $this->isSword !== null )
    {
      return $this->isSword;
    }
    // RETURN : sword is set before
    // Get the current sword
    $gp = ( array ) t3lib_div::_GP( $this->confGP[ 'parameter' ] );
    $sword = $gp[ $this->confGP[ 'input' ] ];
    // #i0014, dwildt, 2+
    $sword = strip_tags( $sword );
    $sword = htmlspecialchars( $sword );

    // Set class var $isSword
    switch ( true )
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

  /*   * *********************************************
   *
   * Get
   *
   * ******************************************** */

  /**
   * getFieldForLatLon( ) : Returns an array with the field labels of the latitude and the longitude
   *
   * @return	array         Returns an array with the field labels of the latitude and the longitude
   * @access public
   * @internal #i0012
   * @version    6.0.2
   * @since      6.0.2
   */
  public function getFieldForLatLon()
  {
    if ( !empty( $this->confFields ) )
    {
      return array(
        'lat' => $this->confFields[ 'lat' ],
        'lon' => $this->confFields[ 'lon' ]
      );
    }

    // Die in case of an error
    $arrPrompt = array(
      0 => '',
      1 => '$this->confFields is empty',
      2 => '',
      3 => '',
      4 => 'Method: ' . __METHOD__ . ' #' . __LINE__,
      5 => 'TYPO3 extension Radial Search',
    );
    $prompt = implode( '<br />' . PHP_EOL, $arrPrompt );

    die( $prompt );
  }

  /*   * *********************************************
   *
   * Set
   *
   * ******************************************** */

  /**
   * setConfiguration( )  : Set fields and filter
   *
   * @param	array		$fields : array with elements lat and lon
   * @param	array		$filter : TypoScript configuration of the filter
   * @return	void
   * @access public
   * @version    6.1.0
   * @since      4.7.0
   */
  public function setConfiguration( $fields, $filter, $gp )
  {
    if ( !is_array( $fields ) )
    {
      $prompt = 'ERROR: no array!<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Radial Search<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }

    if ( !is_array( $filter ) )
    {
      $prompt = 'ERROR: no array!<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Radial Search<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }

    if ( empty( $fields[ 'distance' ] ) )
    {
      $prompt = 'ERROR: field[ distance ] is empty!<br />' . PHP_EOL .
              'Take care of a proper configuration and PHP code.<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Radial Search<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }

    // #61797, 150327, dwildt, +
    if ( empty( $fields[ 'uid' ] ) )
    {
      $prompt = 'ERROR: field[ uid ] is empty!<br />' . PHP_EOL .
              'Take care of a proper configuration and PHP code.<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Radial Search<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }

    if ( empty( $fields[ 'lat' ] ) )
    {
      $prompt = 'ERROR: field[ lat ] is empty!<br />' . PHP_EOL .
              'Take care of a proper configuration and PHP code.<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Radial Search<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }

    if ( empty( $fields[ 'lon' ] ) )
    {
      $prompt = 'ERROR: field[ lon ] is empty!<br />' . PHP_EOL .
              'Take care of a proper configuration and PHP code.<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Radial Search<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }

    if ( empty( $fields[ 'searchmode' ] ) )
    {
      $prompt = 'ERROR: field[ searchmode ] is empty!<br />' . PHP_EOL .
              'Take care of a proper configuration and PHP code.<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Radial Search<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }

    if ( !is_array( $gp ) )
    {
      $prompt = 'ERROR: no array!<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Radial Search<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }

    $this->confFields = $fields;
    $this->confFilter = $filter;
    $this->confGP = $gp;

    return true;
  }

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
    if ( !is_object( $pObj ) )
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

if ( defined( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/radialsearch/interface/class.tx_radialsearch_interface.php' ] )
{
  include_once($TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/radialsearch/interface/class.tx_radialsearch_interface.php' ]);
}
?>