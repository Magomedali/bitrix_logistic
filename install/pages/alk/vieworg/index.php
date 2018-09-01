<?
define("NEED_AUTH", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");


$APPLICATION->SetTitle("Личный кабинет");

use \Bitrix\Main\Application;

$context = Application::getInstance()->getContext();
$request = $context->getRequest();
if(isset($request['id'])){

	//$APPLICATION->AddChainItem("Детальная информация о товаре", "index.php?id=".$request['id']."&ID=".$arSection["ID"]);
}


$dir_name = basename(__DIR__);

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