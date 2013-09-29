<?php

if( ! defined( 'PATH_typo3conf' ) ) die ( 'Could not access this script directly!' );

require_once( PATH_tslib . 'class.tslib_pibase.php' );
 
class test extends tslib_pibase {
  function main(){
    $feUserObj = tslib_eidtools::initFeUser(); // Initialize FE user object    
    tslib_eidtools::connectDB(); //Connect to database
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
}
 
$output = t3lib_div::makeInstance( 'test' );
echo $output->main();


?>
