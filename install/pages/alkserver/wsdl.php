<?php

// if($_SERVER['SERVER_ADDR'] != $_SERVER['REMOTE_ADDR']){
// 	require(__DIR__ . '/security.php');
// }

if(!(isset($_GET['l']) && (int)$_GET['l'] == 1)){
	require(__DIR__ . '/security.php');
}

header("Content-type: text/xml; charset=utf-8");
require_once("../bitrix/modules/ali.logistic/lib/soap/server/wsdl.php");
?>
