<?php

########################################################################
# Extension Manager/Repository config file for ext "org".
#
# Auto generated 15-12-2011 23:01
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Radial Search (Umkreissuche)',
	'description' =>  'Radial search (German: Umkreissuche) for your TYPO3 database.' .
                          'Your data must have a latitude and a longitude. ' .
                          'The Browser - TYPO3 without PHP - has radial seacrh integrated.',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '1.0.0',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'alpha',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Dirk Wildt (Die Netzmacher)',
	'author_email' => 'http://wildt.at.die-netzmacher.de',
	'author_company' => 'Die Netzmacher',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.5.0-6.1.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
	'_md5_values_when_last_written' => 'a:114:{s:9:"ChangeLog";s:4:"6bfb";s:21:"ext_conf_template.txt";s:4:"9439";s:12:"ext_icon.gif";s:4:"ec42";s:14:"ext_tables.php";s:4:"3c8a";s:14:"ext_tables.sql";s:4:"040b";s:16:"locallang_db.xml";s:4:"5463";s:7:"tca.php";s:4:"e767";s:14:"doc/manual.pdf";s:4:"f53e";s:14:"doc/manual.sxw";s:4:"41e7";s:16:"ext_icon/cal.gif";s:4:"aa44";s:24:"ext_icon/calentrance.gif";s:4:"bf10";s:20:"ext_icon/calpage.gif";s:4:"12db";s:23:"ext_icon/calspecial.gif";s:4:"aa44";s:20:"ext_icon/caltype.gif";s:4:"aa44";s:19:"ext_icon/calurl.gif";s:4:"ca5d";s:22:"ext_icon/cat_color.gif";s:4:"5dc9";s:22:"ext_icon/cat_image.gif";s:4:"40df";s:21:"ext_icon/cat_text.gif";s:4:"bb16";s:23:"ext_icon/department.gif";s:4:"78e9";s:26:"ext_icon/departmentcat.gif";s:4:"78e9";s:21:"ext_icon/download.gif";s:4:"31ce";s:30:"ext_icon/download_shipping.gif";s:4:"b958";s:24:"ext_icon/downloadcat.gif";s:4:"31ce";s:26:"ext_icon/downloadmedia.gif";s:4:"31ce";s:18:"ext_icon/event.gif";s:4:"ec42";s:25:"ext_icon/headquarters.gif";s:4:"13a5";s:21:"ext_icon/location.gif";s:4:"314b";s:17:"ext_icon/news.gif";s:4:"bfa6";s:20:"ext_icon/newscat.gif";s:4:"bfa6";s:21:"ext_icon/newspage.gif";s:4:"12db";s:20:"ext_icon/newsurl.gif";s:4:"ca5d";s:21:"ext_icon/shipping.gif";s:4:"86d7";s:18:"ext_icon/staff.gif";s:4:"6705";s:16:"ext_icon/tax.gif";s:4:"bf10";s:31:"lib/class.tx_org_extmanager.php";s:4:"0a18";s:17:"lib/locallang.xml";s:4:"c99e";s:37:"lib/icons/die-netzmacher.de_200px.gif";s:4:"48b3";s:31:"lib/icons/your-logo_de-blue.gif";s:4:"19f7";s:31:"lib/icons/your-logo_de-grey.gif";s:4:"1fbc";s:36:"lib/icons/your-logo_default-blue.gif";s:4:"710c";s:36:"lib/icons/your-logo_default-grey.gif";s:4:"6fdc";s:12:"res/cart.gif";s:4:"e071";s:16:"res/download.gif";s:4:"471a";s:15:"res/favicon.ico";s:4:"5d26";s:20:"res/org_rss-feed.gif";s:4:"c5f9";s:20:"res/realurl_conf.php";s:4:"5203";s:14:"res/ticket.gif";s:4:"4859";s:21:"res/ticket_booked.gif";s:4:"9e2c";s:16:"res/html/org.css";s:4:"adbc";s:34:"res/html/calendar/201/default.tmpl";s:4:"fe3d";s:34:"res/html/calendar/211/default.tmpl";s:4:"2d4a";s:36:"res/html/department/601/default.tmpl";s:4:"9b91";s:36:"res/html/department/611/default.tmpl";s:4:"3971";s:35:"res/html/downloads/301/default.tmpl";s:4:"991e";s:35:"res/html/downloads/302/default.tmpl";s:4:"43b0";s:35:"res/html/downloads/311/default.tmpl";s:4:"fc2c";s:38:"res/html/headquarters/501/default.tmpl";s:4:"5c4c";s:38:"res/html/headquarters/511/default.tmpl";s:4:"8a56";s:34:"res/html/location/701/default.tmpl";s:4:"d47d";s:34:"res/html/location/711/default.tmpl";s:4:"daa1";s:30:"res/html/news/401/default.tmpl";s:4:"153a";s:30:"res/html/news/411/default.tmpl";s:4:"1816";s:26:"res/html/news/499/rss.tmpl";s:4:"af6b";s:38:"res/html/shopping_cart/801/default.css";s:4:"d663";s:39:"res/html/shopping_cart/801/default.tmpl";s:4:"c78b";s:39:"res/html/shopping_cart/811/default.tmpl";s:4:"8532";s:38:"res/html/shopping_cart/821/default.css";s:4:"eb4a";s:39:"res/html/shopping_cart/821/default.tmpl";s:4:"ec3c";s:31:"res/html/staff/101/default.tmpl";s:4:"0bb0";s:31:"res/html/staff/111/default.tmpl";s:4:"9ab7";s:25:"static/base/constants.txt";s:4:"caed";s:21:"static/base/setup.txt";s:4:"6129";s:33:"static/calendar/201/constants.txt";s:4:"d41d";s:29:"static/calendar/201/setup.txt";s:4:"cdd0";s:41:"static/calendar/201/expired/constants.txt";s:4:"d41d";s:37:"static/calendar/201/expired/setup.txt";s:4:"fcc3";s:33:"static/calendar/211/constants.txt";s:4:"d41d";s:29:"static/calendar/211/setup.txt";s:4:"83fd";s:35:"static/department/601/constants.txt";s:4:"d41d";s:31:"static/department/601/setup.txt";s:4:"8119";s:35:"static/department/611/constants.txt";s:4:"d41d";s:31:"static/department/611/setup.txt";s:4:"9ce1";s:34:"static/downloads/301/constants.txt";s:4:"d41d";s:30:"static/downloads/301/setup.txt";s:4:"1061";s:34:"static/downloads/302/constants.txt";s:4:"d41d";s:30:"static/downloads/302/setup.txt";s:4:"850c";s:34:"static/downloads/311/constants.txt";s:4:"d41d";s:30:"static/downloads/311/setup.txt";s:4:"d9e8";s:37:"static/headquarters/501/constants.txt";s:4:"d41d";s:33:"static/headquarters/501/setup.txt";s:4:"ce9a";s:37:"static/headquarters/511/constants.txt";s:4:"d41d";s:33:"static/headquarters/511/setup.txt";s:4:"a182";s:33:"static/location/701/constants.txt";s:4:"d41d";s:29:"static/location/701/setup.txt";s:4:"33ac";s:33:"static/location/711/constants.txt";s:4:"d41d";s:29:"static/location/711/setup.txt";s:4:"69a0";s:29:"static/news/401/constants.txt";s:4:"d41d";s:25:"static/news/401/setup.txt";s:4:"3693";s:29:"static/news/411/constants.txt";s:4:"d41d";s:25:"static/news/411/setup.txt";s:4:"33fe";s:29:"static/news/499/constants.txt";s:4:"1151";s:25:"static/news/499/setup.txt";s:4:"f569";s:38:"static/shopping_cart/801/constants.txt";s:4:"d41d";s:34:"static/shopping_cart/801/setup.txt";s:4:"a7fe";s:38:"static/shopping_cart/811/constants.txt";s:4:"fa13";s:34:"static/shopping_cart/811/setup.txt";s:4:"9320";s:38:"static/shopping_cart/821/constants.txt";s:4:"ab3c";s:34:"static/shopping_cart/821/setup.txt";s:4:"235a";s:30:"static/staff/101/constants.txt";s:4:"d41d";s:26:"static/staff/101/setup.txt";s:4:"f677";s:30:"static/staff/111/constants.txt";s:4:"d41d";s:26:"static/staff/111/setup.txt";s:4:"c8c8";s:20:"tsConfig/de/page.txt";s:4:"a780";s:25:"tsConfig/default/page.txt";s:4:"d882";}',
);

?>
