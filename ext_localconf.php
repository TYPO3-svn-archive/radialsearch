<?php

if( ! defined ( 'TYPO3_MODE' ) )
{
  die ( 'Access denied.' );
}



$cached = false;
t3lib_extMgm::addPItoST43( $_EXTKEY, 'pi1/class.tx_radialsearch_pi1.php', '_pi1', 'list_type', $cached );

$TYPO3_CONF_VARS['FE']['eID_include']['tx_radialsearch_pi1'] = 'EXT:' . $_EXTKEY . '/test.php';


?>