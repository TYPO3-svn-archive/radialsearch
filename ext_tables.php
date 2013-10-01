<?php
if (!defined ('TYPO3_MODE'))
{
  die ('Access denied.');
}



  ////////////////////////////////////////////////////////////////////////////
  //
  // INDEX

  // Set TYPO3 version
  // Configuration by the extension manager
  //    Localization support
  //    Store record configuration
  // Enables the Include Static Templates
  // Add dynamic static files
  // Add pagetree icons
  // Methods for backend workflows
  // Plugin Configuration
  // TCA for tables
  // Allow tables on pages



  ////////////////////////////////////////////////////////////////////////////
  //
  // Set TYPO3 version

  // Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)
list( $main, $sub, $bugfix ) = explode( '.', TYPO3_version );
$version = ( ( int ) $main ) * 1000000;
$version = $version + ( ( int ) $sub ) * 1000;
$version = $version + ( ( int ) $bugfix ) * 1;
$typo3Version = $version;
  // Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)

if( $typo3Version < 3000000 ) 
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
  // Set TYPO3 version

    

  ////////////////////////////////////////////////////////////////////////////
  //
  // Configuration by the extension manager

$confArr  = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['radialsearch']);

  // Language for labels of static templates and page tsConfig
$llStatic = $confArr['LLstatic'];
switch( $llStatic ) 
{
  case( 'German' ):
    $llStatic = 'de';
    break;
  default:
    $llStatic = 'default';
}
  // Language for labels of static templates and page tsConfig

  ////////////////////////////////////////////////////////////////////////////
  //
  // Enables the Include Static Templates

  // Case $llStatic
switch( true ) 
{
  case($llStatic == 'de'):
      // German
    t3lib_extMgm::addStaticFile( $_EXTKEY,'static/', 'Umkreissuche' );
    switch( true )
    {
      case( $typo3Version < 4007000 ):
//        t3lib_extMgm::addStaticFile($_EXTKEY,'static/base/typo3/4.6/',     '+Org: Basis fuer TYPO3 < 4.7 (einbinden!)');
//        t3lib_extMgm::addStaticFile($_EXTKEY,'static/downloads/301/flipit/typo3/4.6/',  '+Org: +Downloads Flip it! TYPO3 < 4.7 (einbinden!');
        break;
      default:
//        t3lib_extMgm::addStaticFile($_EXTKEY,'static/base/typo3/4.6/',     '+Org: Basis fuer TYPO3 < 4.7 (NICHT einbinden!)');
//        t3lib_extMgm::addStaticFile($_EXTKEY,'static/downloads/301/flipit/typo3/4.6/',  '+Org: +Downloads Flip it! TYPO3 < 4.7 (NICHT einbinden!');
        break;
    }
    break;
  default:
      // English
    t3lib_extMgm::addStaticFile( $_EXTKEY,'static/', 'Radial Search' );
    switch( true )
    {
      case( $typo3Version < 4007000 ):
//        t3lib_extMgm::addStaticFile($_EXTKEY,'static/base/typo3/4.6/',     '+Org: Basis for TYPO3 < 4.7 (obligate!)');
//        t3lib_extMgm::addStaticFile($_EXTKEY,'static/downloads/301/flipit/typo3/4.6/',  '+Org: +Downloads Flip it! TYPO3 < 4.7 (obligate!');
        break;
      default:
//        t3lib_extMgm::addStaticFile($_EXTKEY,'static/base/typo3/4.6/',     '+Org: Basis for TYPO3 < 4.7 (don\'t use it!)');
//        t3lib_extMgm::addStaticFile($_EXTKEY,'static/downloads/301/flipit/typo3/4.6/',  '+Org: +Downloads Flip it! TYPO3 < 4.7 (don\'t use it!');
        break;
    }
    break;
}
  // Case $llStatic
  // Enables the Include Static Templates



  /////////////////////////////////////////////////
  //
  // Add dynamic static files

//require_once( PATH_typo3conf . 'ext/org/ext_tables/default/typoscript.php' );
  // Add dynamic static files



  ////////////////////////////////////////////////////////////////////////////
  //
  // Add pagetree icons

  // Case $llStatic
switch( true ) 
{
  case( $llStatic == 'de' ):
      // German
    $TCA['pages']['columns']['module']['config']['items'][] =
       array( 'Umkreissuche', 'radsearch', t3lib_extMgm::extRelPath( $_EXTKEY ) . 'ext_icon.gif' );
    break;
  default:
      // English
    $TCA['pages']['columns']['module']['config']['items'][] =
       array( 'Radial Search', 'radsearch', t3lib_extMgm::extRelPath( $_EXTKEY ) . 'ext_icon.gif' );
}
  // Case $llStatic

  //  #34858, 120320, dwildt
t3lib_SpriteManager::addTcaTypeIcon( 'pages', 'contains-radsearch', '../typo3conf/ext/radialsearch/ext_icon.gif' );

  // Add pagetree icons



  ///////////////////////////////////////////////////////////
  //
  // Methods for backend workflows

  // #i0004, 130130, dwildt, 1+
//require_once(t3lib_extMgm::extPath($_EXTKEY).'lib/flexform/class.tx_org_flexform.php');
$path2lib = t3lib_extMgm::extPath( 'radialsearch' ) . 'lib/';
require_once( $path2lib . 'userfunc/class.tx_radialsearch_userfunc.php' );




  ////////////////////////////////////////////////////////////////////////////
  //
  // Plugin Configuration

t3lib_div::loadTCA('tt_content');

$TCA['tt_content']['types']['list']['subtypes_excludelist'][ $_EXTKEY . '_pi1' ]  = 'layout,select_key,recursive,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][ $_EXTKEY . '_pi1' ]      = 'pi_flexform';
t3lib_extMgm::addPlugin(array(
  'LLL:EXT:radialsearch/locallang_db.xml:pi1',
  $_EXTKEY . '_pi1',
  t3lib_extMgm::extRelPath( $_EXTKEY ) . 'ext_icon.gif'
),'list_type');
t3lib_extMgm::addPiFlexFormValue( $_EXTKEY . '_pi1', 'FILE:EXT:' . $_EXTKEY . '/pi1/flexform.xml' ); 

$TCA['tt_content']['types']['list']['subtypes_excludelist'][ $_EXTKEY . '_pi2' ]  = 'layout,select_key,recursive,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][ $_EXTKEY . '_pi2' ]      = 'pi_flexform';
t3lib_extMgm::addPlugin(array(
  'LLL:EXT:radialsearch/locallang_db.xml:pi2',
  $_EXTKEY . '_pi2',
  t3lib_extMgm::extRelPath( $_EXTKEY ) . 'ext_icon.gif'
),'list_type');
t3lib_extMgm::addPiFlexFormValue( $_EXTKEY . '_pi2', 'FILE:EXT:' . $_EXTKEY . '/pi2/flexform.xml' ); 

$TCA['tt_content']['types']['list']['subtypes_excludelist'][ $_EXTKEY . '_pi3' ]  = 'layout,select_key,recursive,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][ $_EXTKEY . '_pi3' ]      = 'pi_flexform';
t3lib_extMgm::addPlugin(array(
  'LLL:EXT:radialsearch/locallang_db.xml:pi3',
  $_EXTKEY . '_pi3',
  t3lib_extMgm::extRelPath( $_EXTKEY ) . 'ext_icon.gif'
),'list_type');
t3lib_extMgm::addPiFlexFormValue( $_EXTKEY . '_pi2', 'FILE:EXT:' . $_EXTKEY . '/pi1/flexform.xml' ); 
  // Plugin Configuration



  ////////////////////////////////////
  //
  // TCA for tables

  // Items
$TCA['tx_radialsearch_postalcodes'] = array (
  'ctrl' => array (
    'title'             => 'LLL:EXT:radialsearch/locallang_db.xml:tx_radialsearch_postalcodes',
    'label'             => 'place_name',  
    'label_alt'         => 'country_code, admin_code1, postal_code',  
    'label_alt_force'   => true,  
    'tstamp'            => 'tstamp',
    'crdate'            => 'crdate',
    'cruser_id'         => 'cruser_id',
    'delete'            => 'deleted',
    'default_sortby'    => 'ORDER BY place_name, country_code, admin_code1, postal_code DESC',  
    'dividers2tabs'     => true,
    'dynamicConfigFile' => t3lib_extMgm::extPath( $_EXTKEY ) . 'tca.php',
    'iconfile'          => t3lib_extMgm::extRelPath( $_EXTKEY ) . 'ext_icon.gif',
    'searchFields'      => 'country_code,postal_code,place_name,admin_name1,admin_code1,admin_name2,admin_code2,admin_name3,admin_code3'
  ),
);
  // Items

  // TCA for tables



  ////////////////////////////////////
  //
  // Allow tables on pages

t3lib_extMgm::allowTableOnStandardPages( 'tx_radialsearch_postalcodes ');
t3lib_extMgm::addToInsertRecords( 'tx_radialsearch_postalcodes ');
  // Allow tables on pages
?>
