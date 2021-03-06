<?php
/***************************************************************
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
***************************************************************/

/**
* Class provides methods for the extension manager.
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    radialsearch
* @version 6.0.0
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
 * @return    string        message wrapped in HTML
 * @access public
 * @version 2.0.0
 * @since 0.0.1
 */
  public function databaseInfo( )
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error
//var_dump($extConf, $data);
    // #58438, 140503, dwildt, 1+
    $this->initTypo3version( );

    $confArr  = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['radialsearch']);
    $pid      = ( int ) $confArr[ 'database.']['pid' ];

    // #58438, 140503, dwildt+
    switch( true )
    {
      case( $this->typo3Version < 6000000 ):
        $data = $_POST['data'];
        if( isset( $data[ 'database.pid' ] ) )
        {
          $pid = ( int ) $data[ 'database.pid' ];
        }
        break;
      default:
        break;
    }

    $str_prompt = $this->importPostalcodes( );

    $select_fields  = 'country_code AS country, count( country_code ) AS records';
    $from_table     = 'tx_radialsearch_postalcodes';
    $groupBy        = 'country_code';
    $orderBy        = 'country_code';
    $limit          = null;
    $where_clause   = 'pid = ' . $pid;

    $query  = $GLOBALS['TYPO3_DB']->SELECTquery(      $select_fields, $from_table, $where_clause, $groupBy, $orderBy, $limit );
    $res    = $GLOBALS['TYPO3_DB']->exec_SELECTquery( $select_fields, $from_table, $where_clause, $groupBy, $orderBy, $limit );
    $error  = $GLOBALS['TYPO3_DB']->sql_error( );

      // RETURN : error in SQL query
    if( $error )
    {
      $str_prompt = $str_prompt . '
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
      return $str_prompt;
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
      $str_prompt = $str_prompt . '
        <div class="typo3-message message-warning">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/locallang.xml:databaseWithoutContentWarn'). '
          </div>
        </div>
        <div class="typo3-message message-information">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/locallang.xml:databaseWithContentPrompt'). '
          </div>
        </div>
        ';
      return $str_prompt;
    }
      // Database is empty

      // Database has content
    $str_prompt = $str_prompt . '
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
 * @return    string        message wrapped in HTML
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
    // #58438, 140503, dwildt, 1+
    $this->initTypo3version( );

    $confArr  = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['radialsearch']);
    $path     = $confArr[ 'database.']['path' ];

    // #58438, 140503, dwildt+
    switch( true )
    {
      case( $this->typo3Version < 6000000 ):
        $data = $_POST['data'];
        if( isset( $data[ 'database.path' ] ) )
        {
          $path = $data[ 'database.path' ];
        }
        break;
      default:
        break;
    }

    if( empty( $path ) )
    {
      $str_prompt = '
        <div class="typo3-message message-warning">
          <div class="message-body">
            Either path is missing in the field above or directory doesn\'t contain any *.txt file.<br />
            Please save this form once.
          </div>
        </div>
        ';
      return $str_prompt;
    }

    $files = scandir( t3lib_div::getIndpEnv( 'TYPO3_DOCUMENT_ROOT' ) . '/' . $path );
    $prompt   = 'path: ' . t3lib_div::getIndpEnv( 'TYPO3_DOCUMENT_ROOT' ) . '/' . $path . '<br />'
              . 'files: ' . var_export( $files, true )
              ;
    foreach( $files as $key => $file )
    {
      $path_parts = pathinfo( $file );
      if( $path_parts['extension'] == 'txt' )
      {
        $files[ $key ] = '<option value="' . $file . '">' . $file . '</option>';
        continue;
      }
      unset( $files[ $key] );
    }

    if( empty( $files ) )
    {
      $str_prompt = '
        <div class="typo3-message message-warning">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/locallang.xml:directoryWithoutContentWarn'). '
          </div>
        </div>
        ';
      return $str_prompt;
    }

    $options = implode( PHP_EOL, ( array ) $files );

//    $str_prompt = '
//      <div class="typo3-message message-ok">
//        <div class="message-body">
//          <select name="data[database.selectbox]" size="1">
//            <option value="">Don\'t import anything</option>
//            ' . $options . '
//          </select>
//        </div>
//      </div>
//      ';


    // #58438, 140503, dwildt+
    switch( true )
    {
      case( $this->typo3Version < 6000000 ):
        $str_prompt = '
          <dd>
            <select name="data[database.selectbox]" size="1">
              <option value="">' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/locallang.xml:importNothing'). '</option>
              ' . $options . '
            </select>
          </dd>
          ';
        break;
      default:
        $str_prompt = '
          <dd>
            <select name="tx_extensionmanager_tools_extensionmanagerextensionmanager[config][database.selectbox][value]" size="1">
              <option value="">' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/locallang.xml:importNothing'). '</option>
              ' . $options . '
            </select>
          </dd>
          ';
        break;
    }

    return $str_prompt;
  }

/**
 * importPostalcodes( ):
 *
 * @return    string        message wrapped in HTML
 * @access private
 * @version 0.0.1
 * @since 0.0.1
 */
  private function importPostalcodes( )
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error
    $str_prompt = null;

    $confArr    = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['radialsearch']);
    $file       = $confArr[ 'database.']['selectbox' ];
    $pid        = $confArr[ 'database.']['pid' ];
    $truncate   = ( boolean ) $confArr[ 'database.']['truncate' ];
    $path       = $confArr[ 'database.']['path' ];

    // #58438, 140503, dwildt, 1+
    $this->initTypo3version( );

    // #58438, 140503, dwildt+
    switch( true )
    {
      case( $this->typo3Version < 6000000 ):
        $data       = $_POST['data'];
        $file       = $data[ 'database.selectbox' ];
        $pid        = $data[ 'database.pid' ];
        $truncate   = ( boolean ) $data[ 'database.truncate' ];
        if( isset( $data[ 'database.path' ] ) )
        {
          $path = $data[ 'database.path' ];
        }
        break;
      default:
        break;
    }


      // RETURN : no file selected
    if( empty( $file ) )
    {
      return;
    }
      // RETURN : no file selected

    //var_dump( $file );

      // Get the path
    if( empty( $path ) )
    {
      $str_prompt = '
        <div class="typo3-message message-error">
          <div class="message-body">
            Path is missing in the field path!<br />
            Sorry, but ' . $file . ' can\'t impoted.
          </div>
        </div>
        ';
      return $str_prompt;
    }

    $path = t3lib_div::getIndpEnv( 'TYPO3_DOCUMENT_ROOT' ) . '/' . $path . '/' . $file;

    $handle = @fopen( $path, 'r' );

    if ( ! $handle) {
      $str_prompt = '
        <div class="typo3-message message-error">
          <div class="message-body">
            ERROR: Can\'t open ' . $path . '.
          </div>
        </div>
        ';
      return $str_prompt;
    }

    $rows = array( );

    if( $truncate )
    {
      $query = 'TRUNCATE tx_radialsearch_postalcodes';
      $res    = $GLOBALS['TYPO3_DB']->sql_query( $query );
      $str_prompt = $str_prompt . '
        <div class="typo3-message message-notice">
          <div class="message-body">
            Database was truncated before the import.
          </div>
        </div>
        ';
    }

    $keys = 'INSERT INTO tx_radialsearch_postalcodes ( uid, pid, tstamp, crdate, cruser_id, deleted, country_code, postal_code, place_name, admin_name1, admin_code1, admin_name2, admin_code2, admin_name3, admin_code3, latitude, longitude, accuracy ) VALUES ' . PHP_EOL;
    $i        = 0;
    $j        = 0;
    $sumRows  = 0;
    while( ( $line = fgets( $handle, 4096 ) ) !== false )
    {
      $defaultValues = 'NULL, ' . $pid . ', UNIX_TIMESTAMP( ), UNIX_TIMESTAMP( ), 0, 0';
      $line = $GLOBALS['TYPO3_DB']->quoteStr( $line, 'tx_radialsearch_postalcodes' );
      $line = str_replace( array( "\t" . PHP_EOL, PHP_EOL, "\t\t", "\t" ), array( '\', NULL', NULL, '\', NULL, \'', '\', \'' ), $line );
        $line = '\'' . $line . '\'';
      $line = str_replace( array( "'NULL'", "NULL'", "'NULL" ), array( 'NULL', 'NULL', 'NULL' ), $line );
      $line = $defaultValues . ', ' .  $line;
      $rows[ $j ] = $line;
      $sumRows++;
      $i++;
      $j++;
      if( $i >= 100 )
      {
        $i = 0;
        $values = '  ( ' . implode( ' ),' . PHP_EOL . '  ( ', $rows ) . ' );';
        $query  = $keys . $values . PHP_EOL . PHP_EOL;

        $res    = $GLOBALS['TYPO3_DB']->sql_query( $query );
        $error  = $GLOBALS['TYPO3_DB']->sql_error( );

        $rows = array( );

          // RETURN : error in SQL query
        if( $error )
        {
          $str_prompt = $str_prompt . '
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
          return $str_prompt;
        }
          // RETURN : error in SQL query
      }
      if( $j >= 1000 && 0 )
      {
        fclose($handle);
        return;
      }
    }
    $values = '  ( ' . implode( ' ),' . PHP_EOL . '  ( ', $rows ) . ' );';
    $query  = $keys . $values . PHP_EOL . PHP_EOL;

    $res    = $GLOBALS['TYPO3_DB']->sql_query( $query );
    $error  = $GLOBALS['TYPO3_DB']->sql_error( );

      // RETURN : error in SQL query
    if( $error )
    {
      $str_prompt = $str_prompt . '
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
      return $str_prompt;
    }
      // RETURN : error in SQL query

    if( ! feof( $handle ) )
    {
      fclose( $handle );
      $str_prompt = $str_prompt . '
        <div class="typo3-message message-warning">
          <div class="message-body">
            ERROR: ' . $file . ' seems to have an unproper end.<br />
            Please check, if imported data are proper.
          </div>
        </div>
        ';
      return $str_prompt;
    }
    fclose( $handle );

    $str_prompt = $str_prompt . '
      <div class="typo3-message message-ok">
        <div class="message-body">
          SUCCESS: ' . $file . ' (#' . $sumRows . ' records) are imported.
        </div>
      </div>
      ';
    return $str_prompt;
  }


  /**
   * init_typo3version( ):  Get the current TYPO3 version, move it to an integer
   *                        and set the global $bool_typo3_43
   *                        This method is independent from
   *                        * t3lib_div::int_from_ver (upto 4.7)
   *                        * t3lib_utility_VersionNumber::convertVersionNumberToInteger (from 4.7)
   *
   * @return    void
   * @version 2.0.0
   * @since   2.0.0
   * @internal #58438
   */
  private function initTypo3version()
  {
    // RETURN : typo3Version is set
    if ($this->typo3Version !== null)
    {
      return;
    }
    // RETURN : typo3Version is set
    // Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)
    list( $main, $sub, $bugfix ) = explode('.', TYPO3_version);
    $version = ( (int) $main ) * 1000000;
    $version = $version + ( (int) $sub ) * 1000;
    $version = $version + ( (int) $bugfix ) * 1;
    $this->typo3Version = $version;
    // Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)
//echo __METHOD__ . ' (' . __LINE__ . '): ' . typo3Version . '<br />' . PHP_EOL;

    if ($this->typo3Version < 3000000)
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
            integer $this->typo3Version: ' . (int) $this->typo3Version . '
          </li>
        </ul>
          ';
      die($prompt);
    }
  }



  /**
 * promptExternalLinks(): Displays the quick start message.
 *
 * @return    string        message wrapped in HTML
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
 * @return    string        message wrapped in HTML
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
 * @return    string        message wrapped in HTML
 * @version 6.0.0
 * @since 0.0.1
 */
  function promptVersionPrompt( )
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error

    $prompt = null;

    $this->set_TYPO3Version( );

    switch( true )
    {
      case( $this->typo3Version < 4005000 ):
          // Smaller than 4.5
        $prompt = $prompt . '
          <div class="typo3-message message-warning" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/userfunc/locallang.xml:promptEvaluatorTYPO3version45smaller'). '
            </div>
          </div>
          ';
//        $prompt = $prompt . '
//          <div class="typo3-message message-information" style="max-width:' . $this->maxWidth . ';">
//            <div class="message-body">
//              ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/userfunc/locallang.xml:promptEvaluatorIncludeCss4-6'). '
//            </div>
//          </div>
//          ';
        break;
      case( $this->typo3Version < 4006000 ):
          // Smaller than 4.6
        $prompt = $prompt . '
          <div class="typo3-message message-ok" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/userfunc/locallang.xml:promptEvaluatorTYPO3version46smaller'). '
            </div>
          </div>
          ';
//        $prompt = $prompt . '
//          <div class="typo3-message message-information" style="max-width:' . $this->maxWidth . ';">
//            <div class="message-body">
//              ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/userfunc/locallang.xml:promptEvaluatorIncludeCss4-6'). '
//            </div>
//          </div>
//          ';
        break;
      case( $this->typo3Version < 4007000 ):
          // Smaller than 4.7
        $prompt = $prompt . '
          <div class="typo3-message message-ok" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/userfunc/locallang.xml:promptEvaluatorTYPO3version47smaller'). '
            </div>
          </div>
          ';
//        $prompt = $prompt . '
//          <div class="typo3-message message-information" style="max-width:' . $this->maxWidth . ';">
//            <div class="message-body">
//              ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/userfunc/locallang.xml:promptEvaluatorIncludeCss4-6'). '
//            </div>
//          </div>
//          ';
        break;
      case( $this->typo3Version < 4008000 ):
          // Smaller than 4.8
        $prompt = $prompt . '
          <div class="typo3-message message-ok" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/userfunc/locallang.xml:promptEvaluatorTYPO3version48smaller'). '
            </div>
          </div>
          ';
        break;
      case( $this->typo3Version < 6000000 ):
          // Smaller than 6.0
        $prompt = $prompt . '
          <div class="typo3-message message-ok" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/userfunc/locallang.xml:promptEvaluatorTYPO3version60smaller'). '
            </div>
          </div>
          ';
        break;
      case( $this->typo3Version < 6001000 ):
          // Smaller than 6.1
        $prompt = $prompt . '
          <div class="typo3-message message-ok" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/userfunc/locallang.xml:promptEvaluatorTYPO3version61smaller'). '
            </div>
          </div>
          ';
        break;
      case( $this->typo3Version < 6002000 ):
          // Smaller than 6.2
        $prompt = $prompt . '
          <div class="typo3-message message-ok" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/userfunc/locallang.xml:promptEvaluatorTYPO3version62smaller'). '
            </div>
          </div>
          ';
        break;
      case( $this->typo3Version < 6003000 ):
          // Smaller than 6.3
        $prompt = $prompt . '
          <div class="typo3-message message-ok" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/userfunc/locallang.xml:promptEvaluatorTYPO3version63smaller'). '
            </div>
          </div>
          ';
        break;
      default:
          // Equal to or greater than 6.1
        $prompt = $prompt . '
          <div class="typo3-message message-warning" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:radialsearch/lib/userfunc/locallang.xml:promptEvaluatorTYPO3version62orGreater'). '
            </div>
          </div>
          ';
        break;
    }

    return $prompt;
  }



/**
 * set_typo3Version():
 *
 * @return    void
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
