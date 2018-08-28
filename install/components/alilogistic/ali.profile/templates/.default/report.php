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
use Ali\Logistic\helpers\Html;
use Ali\Logistic\helpers\ArrayHelper;

$APPLICATION->SetTitle("Акты сверок");



$contractors = is_array($arResult['contractors']) && count($arResult['contractors']) ? $arResult['contractors'] : array();
$parameters = is_array($arResult['parameters']) && count($arResult['parameters']) ? $arResult['parameters'] : null;

// print_r($user);
?>
<?php if(!empty($contractors)){ ?>

<div id="sverka_page" class="row">
	<div class="col-xs-12">
		<form action="" method="POST">
			<div class="row">
				<div class="col-xs-3">
					<label>С</label>
					<?php
						echo Html::input("date",'dateFrom',$parameters && isset($parameters['dateFrom']) ? date("Y-m-d\TH:i",strtotime($parameters['dateFrom'])) : null,['class'=>'form-control']);
					?>
				</div>
				<div class="col-xs-3">
					<label>По</label>
					<?php
						echo Html::input("date",'dateTo',$parameters && isset($parameters['dateTo']) ? date("Y-m-d\TH:i",strtotime($parameters['dateTo'])) : null,['class'=>'form-control']);
					?>
				</div>
				<div class="col-xs-3">
					<label for="with_nds">Организация</label>
					<?php
						echo Html::dropDownList("contractor",$parameters && isset($parameters['contractor']) ? $parameters['contractor'] : null,ArrayHelper::map($contractors,'ID','NAME'),['class'=>'form-control']);
					?>
				</div>
				<div class="col-xs-3">
					<div class="row">
						<div class="col-xs-6">
							<label for="with_nds">С</label>
							<?php
								echo Html::checkbox("with_nds",$parameters && isset($parameters['with_nds']) && $parameters['with_nds'],['id'=>'with_nds']);
							?>
						</div>

						<div class="col-xs-6">
							<?php
								echo Html::submitButton("Сформировать",['class'=>'btn btn-success']);
							?>
						</div>
					</div>
					
				</div>
			</div>
		</form>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		
	</div>
</div>
<?php } ?>

