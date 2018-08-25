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



$user = is_array($arResult['user']) && count($arResult['user']) ? $arResult['user'] : null;

// print_r($user);
?>
<?php if(!empty($user)){ ?>

<div id="personal_page" class="row">
	<div class="col-xs-12">
		<p>Фамилия: <?php echo $user['SECOND_NAME']?></p>
		<p>Имя: <?php echo $user['NAME']?></p>
		<p>Отчество: <?php echo $user['LAST_NAME']?></p>
		<p>Email: <?php echo $user['EMAIL']?></p>
	</div>
</div>

<?php } ?>

