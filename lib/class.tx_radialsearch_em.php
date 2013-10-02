<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011-2012 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
* Class provides methods for the extension manager.
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    org
* @version 0.0.1
* @since 0.3.1
*/


  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   51: class tx_radialsearch_em
 *   71:     function promptQuickstart()
 *  112:     function promptExternalLinks()
 *  139:     function promptVersionPrompt( )
 *  202:     private function set_typo3Version( )
 *
 * TOTAL FUNCTIONS: 4
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_radialsearch_em
{

    // [INTEGER] TYPO3 version. Sample: 4.7.7 -> 4007007
  var $typo3Version = null;

/**
 * databaseInfo():
 *
 * @return	string		message wrapped in HTML
 * @access public
 * @version 0.0.1
 * @since 0.0.1
 */
  public function databaseInfo( )
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error

    $select_fields  = 'country_code AS country, count( country_code ) AS records';
    $from_table     = 'tx_radialsearch_postalcodes';
    $groupBy        = 'country_code';
    $orderBy        = 'country_code';
    $limit          = null;
    $where_clause   = null;
    
    $query  = $GLOBALS['TYPO3_DB']->SELECTquery(      $select_fields, $from_table, $where_clause, $groupBy, $orderBy, $limit );
    $res    = $GLOBALS['TYPO3_DB']->exec_SELECTquery( $select_fields, $from_table, $where_clause, $groupBy, $orderBy, $limit );
    $error  = $GLOBALS['TYPO3_DB']->sql_error( );
    
      // RETURN : error in SQL query
    if( $error )
    {
      $str_prompt = '
        <div class="typo3-message message-error">
          <div class="message-body">
            <p>
              ERROR: ' . $error . '
            </p>
            <p>
              Query: ' . $query . '
            </p>
          </div>
        </div>
        ';
      return $error;
    }
      // RETURN : error in SQL query
    
    $rows = array( );
    while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
    {
      $rows[ ] = '<li style="margin-bottom:0;">' . $row[ 'country' ] . ': #' . $row[ 'records' ] . ' records</li>'; 
    }

      // Database is empty
    if( empty( $rows ) )
    {
      $str_prompt = '
        <div class="typo3-message message-warning">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/locallang.xml:promptVersionPrompt47smaller'). '
          </div>
        </div>
        <div class="typo3-message message-information">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/locallang.xml:promptVersionPrompt47smaller'). '
          </div>
        </div>
        ';
      return $str_prompt;
    }
      // Database is empty
    
      // Database has content
    $str_prompt = '
      <div class="typo3-message message-ok">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/locallang.xml:databaseWithContentOk'). '
          <ul>
            ' . implode( null, $rows ) . '
          </ul>
        </div>
      </div>
      <div class="typo3-message message-information">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/locallang.xml:databaseWithContentPrompt'). '
        </div>
      </div>
      ';
    return $str_prompt;
      // Database has content

  }

/**
 * databaseSelectbox( ):
 *
 * @return	string		message wrapped in HTML
 * @access public
 * @version 0.0.1
 * @since 0.0.1
 */
  public function databaseSelectbox( )
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error
    $extConf  = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['radialsearch']);
    $data     = $_POST['data'];
//    $prompt   = 'extConf: ' . var_export( $extConf, true ) . '<br />'
//              . 'data: ' . var_export( $data, true )
//              ;
    $path = $extConf[ 'database.']['path' ];
    if( isset( $data[ 'database.path' ] ) )
    {
      $path = $data[ 'database.path' ];
    }
    if( empty( $path ) )
    {
      $str_prompt = '
        <div class="typo3-message message-warning">
          <div class="message-body">
            Path is missing in the field above!<br />
            ' . $prompt . '
          </div>
        </div>
        ';
      return $str_prompt;
    }

    $str_prompt = '
      <div class="typo3-message message-ok">
        <div class="message-body">
          <select name="data[database.selectbox]" size="1">
            <option value="">Don\'t import any txt-file</option>
            <option value="10">10 km</option>
            <option value="25">25 km</option>
            <option value="50">50 km</option>
            <option value="100">100 km</option>
          </select>
        </div>
      </div>
      <div class="typo3-message message-information">
        <div class="message-body">
          ' . $path . '<br />
          ' . $prompt . '
        </div>
      </div>
      ';
    return $str_prompt;
  }



  /**
 * promptExternalLinks(): Displays the quick start message.
 *
 * @return	string		message wrapped in HTML
 * @since 0.3.1
 * @version 0.3.1
 */
  function promptExternalLinks()
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error

      $str_prompt = null;

      $str_prompt = $str_prompt.'
<div class="message-body">
  ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/locallang.xml:promptExternalLinksBody'). '
</div>';

    return $str_prompt;
  }

  

  /**
 * promptQuickstart(): Displays the quick start message.
 *
 * @return	string		message wrapped in HTML
 * @since 0.3.1
 * @version 0.3.1
 */
  function promptQuickstart()
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error

      $str_prompt = null;

      $str_prompt = $str_prompt.'
<div class="typo3-message message-information">
  <div class="message-body">
    ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/locallang.xml:promptQuickstartBody'). '
  </div>
</div>
<div class="typo3-message message-information">
  <div class="message-body">
    ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/locallang.xml:promptGeneralInfo'). '
  </div>
</div>
';

    return $str_prompt;
  }



/**
 * promptVersionPrompt():
 *
 * @return	string		message wrapped in HTML
 * @version 0.0.1
 * @since 0.0.1
 */
  function promptVersionPrompt( )
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error

    $str_prompt = null;

    $this->set_typo3Version( );

    switch( true )
    {
      case( $this->typo3Version < 4007000 ):
          // Smaller than 4.7
        $str_prompt = $str_prompt.'
          <div class="typo3-message message-warning">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/locallang.xml:promptVersionPrompt47smaller'). '
            </div>
          </div>
          ';
        break;
      case( $this->typo3Version >= 4007999 ):
          // Greater than 4.7
        $str_prompt = $str_prompt.'
          <div class="typo3-message message-warning">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/locallang.xml:promptVersionPrompt47greater'). '
            </div>
          </div>
          ';
        break;
      default:
          // Equal to 4.7
        $str_prompt = $str_prompt.'
          <div class="typo3-message message-ok">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/locallang.xml:promptVersionPrompt47equal'). '
            </div>
          </div>
          <div class="typo3-message message-warning">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/locallang.xml:promptVersionPrompt47equalCSC'). '
            </div>
          </div>
          ';
        break;
    }

    return $str_prompt;
  }



/**
 * set_typo3Version():
 *
 * @return	void
 * @version 0.0.1
 * @since 0.0.1
 */
  private function set_typo3Version( )
  {
      // #43108, 121212, dwildt, +
      // RETURN : typo3Version is set
    if( $this->typo3Version !== null )
    {
      return;
    }
      // RETURN : typo3Version is set

      // Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)
    list( $main, $sub, $bugfix ) = explode( '.', TYPO3_version );
    $version = ( ( int ) $main ) * 1000000;
    $version = $version + ( ( int ) $sub ) * 1000;
    $version = $version + ( ( int ) $bugfix ) * 1;
    $this->typo3Version = $version;
      // Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)

    if( $this->typo3Version < 3000000 )
    {
      $prompt = '<h1>ERROR</h1>
        <h2>Unproper TYPO3 version</h2>
        <ul>
          <li>
            TYPO3 version is smaller than 3.0.0
          </li>
          <li>
            constant TYPO3_version: ' . TYPO3_version . '
          </li>
          <li>
            integer $this->typo3Version: ' . ( int ) $this->typo3Version . '
          </li>
        </ul>
          ';
      die ( $prompt );
    }
  }








}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/lib/class.tx_radialsearch_em.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/lib/class.tx_radialsearch_em.php']);
}

?>