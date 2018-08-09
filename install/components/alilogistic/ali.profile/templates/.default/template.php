<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
use \Bitrix\Main\Localization\Loc;

$APPLICATION->SetTitle(getMessage("ALI_PROFILE_TITLE"));
$aMenuLinks[] = Array(
		"Подать заявку", 
		"/personal/podat-zayavku/", 
		Array(), 
		Array(), 
		"" 
	);
?>
<div id="chat_container">
	
</div>
