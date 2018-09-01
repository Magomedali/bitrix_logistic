<?
define("NEED_AUTH", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Черновики");
$dir_name = basename(__DIR__);
?>

<?php

if($USER->IsAuthorized()) {
    
	$APPLICATION->IncludeComponent(
	"alilogistic:ali.profile",
	"",
	Array(
		'route'=>$dir_name
	)
	);
}

?>

<br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>