<?php

if( ! defined ( 'TYPO3_MODE' ) )
{
  die ( 'Access denied.' );
}

$cached = true;
t3lib_extMgm::addPItoST43( $_EXTKEY, 'pi1/class.tx_radialsearch_pi1.php', '_pi1', 'list_type', $cached );

$cached = true;
t3lib_extMgm::addPItoST43( $_EXTKEY, 'pi1/class.tx_radialsearch_pi1.php', '_pi2', 'list_type', $cached );

$cached = true;
t3lib_extMgm::addPItoST43( $_EXTKEY, 'pi3/class.tx_radialsearch_pi3.php', '_pi3', 'list_type', $cached );

$TYPO3_CONF_VARS['FE']['eID_include']['tx_radialsearch_pi1'] = 'EXT:' . $_EXTKEY . '/pi1/class.tx_radialsearch_pi1_eid.php';

?>