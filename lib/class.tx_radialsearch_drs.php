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
 *   56: class tx_radialsearch_drs
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
class tx_radialsearch_drs
{

  public $prefixId = 'tx_radialsearch_drs';

  // same as class name
  public $scriptRelPath = 'lib/class.tx_radialsearch_drs.php';

  // path to this script relative to the extension dir.
  public $extKey = 'radialsearch';

    // [Object] Parent object
  private $pObj = null;

    // [Array] Current row
  private $row = null;

  public $drsError      = false;
  public $drsWarn       = false;
  public $drsInfo       = false;
  public $drsOk         = false;
  public $drsCss        = false;
  public $drsFlexform   = false;
  public $drsHtml       = false;
  public $drsJavascript = false;
  public $drsSql        = false;







 /**
  * init( ): Init the DRS - Development Reporting System
  *
  * @return	void
  * @access public
  * @version    0.0.1
  * @since      0.0.1
  */
  public function init( )
  {
    $this->row = $this->pObj->cObj->data;

    $this->initByExtmngr( );

      // RETURN : DRS is enabled by the extension manager
    if( $this->drsOk )
    {
      return;
    }
      // RETURN : DRS is enabled by the extension manager

    $this->initByFlexform( );
  }

 /**
  * initByExtmngr( ): Init the DRS - Development Reporting System
  *
  * @return	void
  * @access private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function initByExtmngr( )
  {
var_dump( __METHOD__, __LINE__, $this->pObj->arr_extConf );
    switch( $this->pObj->arr_extConf['drs.enabled'] )
    {
      case( 'Disabled' ):
      case( null ):
        return;
        break;
      case( 'Enabled (for debugging only!)' ):
          // Follow the workflow
        break;
      default:
        $prompt = 'Error: drs.enabled is undefined.<br />
          value is ' . $this->pObj->arr_extConf['drs.enabled'] . '<br />
          <br />
          ' . __METHOD__ . ' line(' . __LINE__. ')';
        die( $prompt );
    }

    $this->zzDrsPromptsTrue( );

    $prompt = 'The DRS - Development Reporting System is enabled: ' . $this->pObj->arr_extConf['drs.enabled'];
    t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->pObj->extKey, 0 );
    $prompt = 'The DRS is enabled by the extension manager.';
    t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->pObj->extKey, 0 );
    $str_header = $this->row['header'];
    $int_uid    = $this->row['uid'];
    $int_pid    = $this->row['pid'];
    $prompt = '"' . $str_header . '" (pid: ' . $int_pid . ', uid: ' . $int_uid . ')';
    t3lib_div :: devlog('[INFO/DRS] ' . $prompt, $this->pObj->extKey, 0);
  }

 /**
  * initByFlexform( ): Init the DRS - Development Reporting System
  *
  * @return	void
  * @access private
  * @version    0.0.1
  * @since      0.0.1
  */
  private function initByFlexform( )
  {

      // sdefDrs
    $sheet = 'sDEF';
    $field = 'sdefDrs';
    $this->pObj->flexform->sdefDrs = $this->pObj->flexform->zzFfValue( $sheet, $field, false );
      // sdefDrs

      // Enable the DRS by TypoScript
    if( empty( $this->pObj->flexform->sdefDrs ) )
    {
      return;
    }

    $this->zzDrsPromptsTrue( );

    $prompt = 'The DRS - Development Reporting System is enabled by the flexform (frontend mode).';
    t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->pObj->extKey, 0 );
    $str_header = $this->row['header'];
    $int_uid    = $this->row['uid'];
    $int_pid    = $this->row['pid'];
    $prompt = '"' . $str_header . '" (pid: ' . $int_pid . ', uid: ' . $int_uid . ')';
    t3lib_div :: devlog('[INFO/DRS] ' . $prompt, $this->pObj->extKey, 0);
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




  /***********************************************
  *
  * ZZ
  *
  **********************************************/

 /**
  * zzDrsPromptsTrue( ): Init the DRS - Development Reporting System
  *
  * @return	void
  * @access public
  * @version    0.0.1
  * @since      0.0.1
  */
  public function zzDrsPromptsTrue( )
  {
    $this->drsError       = true;
    $this->drsWarn        = true;
    $this->drsInfo        = true;
    $this->drsOk          = true;
    $this->drsCss         = true;
    $this->drsFlexform    = true;
    $this->drsHtml        = true;
    $this->drsJavascript  = true;
    $this->drsSql         = true;
  }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/lib/class.tx_radialsearch_drs.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/lib/class.tx_radialsearch_drs.php']);
}
?>
