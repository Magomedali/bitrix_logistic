<?
define("NEED_AUTH", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

// $APPLICATION->SetTitle("Личный кабинет");
?>

<?php

if($USER->IsAuthorized()) {
    
	$APPLICATION->IncludeComponent(
	"alilogistic:ali.profile",
	"",
	Array()
	);
}

/*
$APPLICATION->IncludeComponent(
	"bitrix:main.profile",
	"lk",
	Array(
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CHECK_RIGHTS" => "N",
		"COMPONENT_TEMPLATE" => "lk",
		"SEND_INFO" => "N",
		"SET_TITLE" => "Y",
		"USER_PROPERTY" => array(0=>"UF_INN",1=>"UF_KPP",),
		"USER_PROPERTY_NAME" => ""
	)
);
*/
?>

<br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>