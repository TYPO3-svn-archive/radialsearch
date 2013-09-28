<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2013 - Dirk Wildt http://wildt.at.die-netzmacher.de
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
 *   55: class tx_radialsearch_pi1_flexform
 *   87:     function main()
 *
 *              SECTION: Sheets
 *  111:     private function sheetSdef( )
 *
 *              SECTION: Zz
 *  152:     public function zzFfValue( $sheet, $field, $drs=true )
 *
 * TOTAL FUNCTIONS: 3
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * Class tx_radialsearch_pi1_flexform is an image of the values of the plugin 1 flexform
 *
 * @author    Dirk Wildt http://wildt.at.die-netzmacher.de
 * @package    TYPO3
 * @subpackage    radialsearch
 * @version 0.0.1
 * @since   0.0.1
 */
class tx_radialsearch_pi1_flexform
{
    // Parent object
  public $pObj = null;
    // Current row
  public $row = null;

    // [paths]
  public $pathsDeliveryorder  = null;
  public $pathsInvoice        = null;
  public $pathsRevocation     = null;
  public $pathsTerms          = null;
    // [paths]

    // [sdef]
    // [boolean] enable DRS
  public $sdefDrs = null;
    // [boolean] enable update wizard
  public $sdefUpdatewizard = null;
    // [string] csv list of allowed IP
  public $sdefCsvallowedip;
    // [sdef]



  /**
 * main():  Get the values from the pi_flexform field.
 *          Process each sheet.
 *          Allocates values to $this
 *
 * @return    void
 * @version   0.0.1
 */
  function main()
  {

      // Sheets
    $this->sheetSdef( );
    $this->sheetPaths( );
      // Sheets

  }



  /***********************************************
   *
   * Sheets
   *
   **********************************************/


/**
 * sheetPaths( )  :
 *
 * @return	void
 * @version 0.0.1
 * @since   0.0.1
 */
  private function sheetPaths( )
  {
    $sheet = 'paths';

      // pathsDeliveryorder
    $field                    = 'deliveryorder';
    $this->pathsDeliveryorder = $this->zzFfValue( $sheet, $field );
      // pathsDeliveryorder

      // pathsInvoice
    $field                    = 'invoice';
    $this->pathsInvoice       = $this->zzFfValue( $sheet, $field );
      // pathsInvoice

      // pathsRevocation
    $field                    = 'revocation';
    $this->pathsRevocation    = $this->zzFfValue( $sheet, $field );
      // pathsRevocation

      // pathsTerms
    $field                    = 'terms';
    $this->pathsTerms         = $this->zzFfValue( $sheet, $field );
      // pathsTerms

    return;
  }

/**
 * sheetSdef( ) :
 *
 * @return	void
 * @version 0.0.1
 * @since   0.0.1
 */
  private function sheetSdef( )
  {
    $sheet = 'sDEF';

      // sdefCsvallowedip
    $field                  = 'sdefCsvallowedip';
    $this->sdefCsvallowedip = $this->zzFfValue( $sheet, $field );
      // sdefCsvallowedip

      // sdefDrs
// @see pObj->initByFlexform( )
//    $field          = 'sdefDrs';
//    $this->sdefDrs  = $this->zzFfValue( $sheet, $field, false );
      // sdefDrs

      // sdefUpdatewizard
    $field                  = 'sdefUpdatewizard';
    $this->sdefUpdatewizard = $this->zzFfValue( $sheet, $field );
      // sdefUpdatewizard

    return;
  }



  /***********************************************
   *
   * Zz
   *
   **********************************************/

/**
 * zzFfValue: Returns the value of the given flexform field
 *
 * @param	[type]		$$sheet: ...
 * @param	[type]		$field: ...
 * @param	[type]		$drs: ...
 * @return	mixed		$value  : Value from the flexform field
 * @version 0.0.1
 * @since   0.0.1
 */
  public function zzFfValue( $sheet, $field, $drs=true )
  {
    $pi_flexform = $this->row['pi_flexform'];

    $value = $this->pObj->pi_getFFvalue( $pi_flexform, $field, $sheet, 'lDEF', 'vDEF' );

      // RETURN : Don't prompt to DRS
    if( ! $drs )
    {
      return $value;
    }
      // RETURN : Don't prompt to DRS

      // RETURN : DRS is disabled
    if( ! $this->pObj->b_drs_flexform )
    {
      return $value;
    }
      // RETURN : DRS is disabled

      // DRS
    $prompt = $sheet . '.' . $field . ': "' . $value . '"';
    t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
      // DRS

    return $value;
  }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/pi1/class.tx_radialsearch_pi1_flexform.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/radialsearch/pi1/class.tx_radialsearch_pi1_flexform.php']);
}
?>