<?php
if (!defined ('TYPO3_MODE'))  die ('Access denied.');



  ///////////////////////////////////////
  // 
  // INDEX
  // 
  // tx_radialsearch_postalcodes



  ///////////////////////////////////////
  // 
  // tx_radialsearch_postalcodes
  
$TCA['tx_radialsearch_postalcodes'] = array (
  'ctrl' => $TCA['tx_radialsearch_postalcodes']['ctrl'],
  'interface' => array (
    'showRecordFieldList' => '
        country_code
      , postal_code
      , place_name
      , admin_name1
      , admin_code1
      , admin_name2
      , admin_code2
      , admin_name3
      , admin_code3
      , latitude
      , longitude
      , accuracy
      '
  ),
  'feInterface' => $TCA['tx_radialsearch_postalcodes']['feInterface'],
  'columns' => array (
    'country_code' => array (    
      'exclude' => 0,    
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_radialsearch_postalcodes.country_code',
      'config' => array (
        'type' => 'input',  
        'max'  => '2',  
        'size' => '2',  
        'eval' => 'trim,required',
      )
    ),
    'postal_code' => array (    
      'exclude' => 0,    
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_radialsearch_postalcodes.postal_code',
      'config' => array (
        'type' => 'input',  
        'max'  => '20',  
        'size' => '20',  
        'eval' => 'trim,required',
      )
    ),
    'place_name' => array (    
      'exclude' => 0,    
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_radialsearch_postalcodes.place_name',
      'config' => array (
        'type' => 'input',  
        'max'  => '30',  
        'size' => '30',  
        'eval' => 'trim,required',
      )
    ),
    'admin_name1' => array (    
      'exclude' => 0,    
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_radialsearch_postalcodes.admin_name1',
      'config' => array (
        'type' => 'input',  
        'max'  => '100',  
        'size' => '100',  
        'eval' => 'trim',
      )
    ),
    'admin_code1' => array (    
      'exclude' => 0,    
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_radialsearch_postalcodes.admin_code1',
      'config' => array (
        'type' => 'input',  
        'max'  => '20',  
        'size' => '20',  
        'eval' => 'trim',
      )
    ),
    'admin_name2' => array (    
      'exclude' => 0,    
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_radialsearch_postalcodes.admin_name2',
      'config' => array (
        'type' => 'input',  
        'max'  => '100',  
        'size' => '100',  
        'eval' => 'trim',
      )
    ),
    'admin_code2' => array (    
      'exclude' => 0,    
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_radialsearch_postalcodes.admin_code2',
      'config' => array (
        'type' => 'input',  
        'max'  => '20',  
        'size' => '20',  
        'eval' => 'trim',
      )
    ),
    'admin_name3' => array (    
      'exclude' => 0,    
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_radialsearch_postalcodes.admin_name3',
      'config' => array (
        'type' => 'input',  
        'max'  => '100',  
        'size' => '100',  
        'eval' => 'trim',
      )
    ),
    'admin_code3' => array (    
      'exclude' => 0,    
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_radialsearch_postalcodes.admin_code3',
      'config' => array (
        'type' => 'input',  
        'max'  => '20',  
        'size' => '20',  
        'eval' => 'trim',
      )
    ),
    'latitude' => array (    
      'exclude' => 0,    
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_radialsearch_postalcodes.latitude',
      'config' => array (
        'type' => 'input',  
        'max'  => '30',  
        'size' => '30',  
        'eval' => 'trim,required',
      )
    ),
    'longitude' => array (    
      'exclude' => 0,    
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_radialsearch_postalcodes.longitude',
      'config' => array (
        'type' => 'input',  
        'max'  => '30',  
        'size' => '30',  
        'eval' => 'trim,required',
      )
    ),
    'accuracy' => array (    
      'exclude' => 0,    
      'label' => 'LLL:EXT:caddy/locallang_db.xml:tx_radialsearch_postalcodes.accuracy',
      'config' => array (
        'type' => 'input',  
        'max'  => '1',  
        'size' => '1',  
        'eval' => 'trim',
      )
    ),
  ),
  'types' => array (
    '0' => array (
      'showitem' => '
          country_code
        , postal_code
        , place_name
        , admin_name1
        , admin_code1
        , admin_name2
        , admin_code2
        , admin_name3
        , admin_code3
        , latitude
        , longitude
        , accuracy
        '
    )
  ),
  'palettes' => array (
    '1' => array('showitem' => ''),
  )
);
  // tx_radialsearch_postalcodes

?>