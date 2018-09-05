<?
define("NEED_AUTH", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");


$APPLICATION->SetTitle(null);
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");

use \Bitrix\Main\Application;

$dir_name = basename(__DIR__);

if($USER->IsAuthorized()) {
    
	$APPLICATION->IncludeComponent(
	"alilogistic:ali.profile",
	"",
	Array(
		'route'=>$dir_name,
		"ADD_SECTIONS_CHAIN" => "Y",
		"SET_TITLE" => "Y",
	)
	);

	
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

?>