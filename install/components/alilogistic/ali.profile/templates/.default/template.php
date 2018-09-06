<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Ali\Logistic\Helpers\Html;
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


$user = is_array($arResult['user']) && count($arResult['user']) ? $arResult['user'] : null;
$hasCompany = isset($arResult['hasCompany'])? $arResult['hasCompany'] : null;

?>
<?php $this->getComponent()->includeComponentTemplate("helpers/breadcrumbs"); ?>
<?php if(!empty($user)){ ?>
<div id="alilogistic" class="row personal_page">
	<div class="col-xs-4">
		<table class="table table-hover">
			<thead>
				<tr>
					<td><strong>Фамилия</strong></td><td><?php echo $user['SECOND_NAME']?></td>
				</tr>
				<tr>
					<td><strong>Имя</strong></td><td><?php echo $user['NAME']?></td>
				</tr>
				<tr>
					<td><strong>Отчество</strong></td><td><?php echo $user['LAST_NAME']?></td>
				</tr>
				<tr>
					<td><strong>Email</strong></td><td><?php echo $user['EMAIL']?></td>
				</tr>
				<tr>
					<td><strong>Личный телефон</strong></td><td><?php echo $user['PERSONAL_PHONE']?></td>
				</tr>
				<tr>
					<td><strong>Рабочий телефон</strong></td><td><?php echo $user['WORK_PHONE']?></td>
				</tr>
			</thead>
		</table>
	</div>
	<div class="col-xs-4">
		<?php echo Html::a("Редактировать",$component->getUrl("profileform"),['class'=>'btn btn-primary']);?>
		<br><br>
		<?php echo !$hasCompany ? Html::a("Присоединиться к другой учетной записи",$component->getActionUrl("follow"),['class'=>'btn btn-default']) : null;?>
	</div>
</div>

<?php } ?>

