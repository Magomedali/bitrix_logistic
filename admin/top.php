<?php 

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/storeassist/include.php");

IncludeModuleLangFile(__FILE__);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");


\Bitrix\Main\Loader::includeModule("ali.logistic");

global $APPLICATION;


$public_path = str_replace($_SERVER['DOCUMENT_ROOT'], "", ALI_MODULE_ADMIN_PATH);

$APPLICATION->SetAdditionalCSS($public_path."css/bootstrap.min.css");
$APPLICATION->SetAdditionalCSS($public_path."css/aliadmin.css");
$APPLICATION->AddHeadScript($public_path."js/jquery.min.js");
$APPLICATION->AddHeadScript($public_path."js/bootstrap.min.js");
?>