<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Ali\Logistic\Helpers\Html;

$user = isset($arResult['user']) && $arResult['user'] ? $arResult['user'] : null;
$errors = is_array($arResult['errors']) && count($arResult['errors']) ? $arResult['errors'] : null;
$title = isset($arResult['pageTitle']) ? $arResult['pageTitle'] : "Форма пользователя";
$arResult['breadcrumbs'][]=[
	'title'=>$title,
	'link'=>null,
	'active'=>true
];

if($user){

?>

<?php $this->getComponent()->includeComponentTemplate("helpers/breadcrumbs"); ?>

<?php if($errors){?>
	<div class="row">
		<div class="col-xs-6">
			<?php foreach ($errors as $key => $e) { ?>
				<div class="alert alert-warning">
					<div>
						<?php echo $e;?>
					</div>
				</div>
			<?php }?>
		</div>
	</div>
<?php } ?>

<div class="row">
	<div class="col-xs-6">
		<form action="" method="POST">
			
			<div class="row">
				<div class="col-xs-6">
					<label for="">Фамилия</label>
					<?php echo Html::textInput("USER[SECOND_NAME]",$user['SECOND_NAME'],['class'=>'form-control']);?>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-6">
					<label for="">Имя</label>
					<?php echo Html::textInput("USER[NAME]",$user['NAME'],['class'=>'form-control']);?>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-6">
					<label for="">Отчество</label>
					<?php echo Html::textInput("USER[LAST_NAME]",$user['LAST_NAME'],['class'=>'form-control']);?>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-6">
					<label for="">Email</label>
					<?php echo Html::textInput("USER[EMAIL]",$user['EMAIL'],['class'=>'form-control']);?>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-6">
					<label for="">Личный телефон</label>
					<?php echo Html::textInput("USER[PERSONAL_PHONE]",$user['PERSONAL_PHONE'],['class'=>'form-control']);?>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-6">
					<label for="">Рабочий телефон</label>
					<?php echo Html::textInput("USER[WORK_PHONE]",$user['WORK_PHONE'],['class'=>'form-control']);?>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-6">
					<?php echo Html::submitInput("Сохранить",['class'=>'btn btn-primary','style'=>'margin-top:20px;']);?>
				</div>
			</div>
		</form>
	</div>
</div>
<?php } ?>