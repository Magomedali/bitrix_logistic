<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use \Bitrix\Main\Localization\Loc;


$error = isset($arResult['error'])? $arResult['error'] : null;
?>
<h1><?php echo $error;?></h1>