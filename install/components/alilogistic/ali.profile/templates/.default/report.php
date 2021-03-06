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

$arResult['breadcrumbs'][]=[
		'title'=>"Акты сверок",
		'link'=>null,
		'active'=>true
];

$contractors = is_array($arResult['contractors']) && count($arResult['contractors']) ? $arResult['contractors'] : array();
$parameters = is_array($arResult['parameters']) && count($arResult['parameters']) ? $arResult['parameters'] : null;
$errors = is_array($arResult['errors']) && count($arResult['errors']) ? $arResult['errors'] : null;
$revice = is_array($arResult['revice']) && count($arResult['revice']) ? $arResult['revice'] : null;
$oldRevises = is_array($arResult['oldRevises']) && count($arResult['oldRevises']) ? $arResult['oldRevises'] : null;

$file_path = ALI_REVISES_PATH;
?>
<?php if(!empty($contractors)){ ?>

<?php $this->getComponent()->includeComponentTemplate("helpers/breadcrumbs"); ?>
<div id="alilogistic" class="row report_page">
	<?php if($errors){ ?>
		<div class="col-xs-12">
			<?php foreach ($errors as $key => $e) { ?>
				<div class="alert alert-warning">
					<div>
						<?php echo $e;?>
					</div>
				</div>
			<?php }?>
		</div>
	<?php } ?>
	<div class="col-xs-12">
		<form action="" method="POST">
			<div class="row">
				<div class="col-xs-3">
					<label>С</label>
					<?php
						echo Html::input("date",'dateFrom',$parameters && isset($parameters['dateFrom']) ? date("Y-m-d",strtotime($parameters['dateFrom'])) : date("Y-m-01",time()),['class'=>'form-control']);
					?>
				</div>
				<div class="col-xs-3">
					<label>По</label>
					<?php
						echo Html::input("date",'dateTo',$parameters && isset($parameters['dateTo']) ? date("Y-m-d",strtotime($parameters['dateTo'])) : date("Y-m-30",time()),['class'=>'form-control']);
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
							<label for="with_nds">С НДС</label>
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
		<?php 

		if($revice && isset($revice['ID']) && isset($revice['FILE']) && file_exists($file_path.$revice['FILE'])){ ?>
		<div class="row" style="margin-top: 25px">
			<div class="col-xs-6">
				<?php echo Html::a("Сформированная сверка",$component->getActionUrl("downloadRevice",['revice'=>$revice['ID']]),['target'=>"_blank"]);?>
			</div>
		</div>
		<?php } ?>
	</div>
</div>


<div class="row" style="margin-top: 25px;">
	<div class="col-xs-12">
		<table class="table table-bordered  table-hover">
			<thead>
				<tr>
					<th>Дата От</th>
					<th>Дата По</th>
					<th>Организация</th>
					<th>C НДС</th>
					<th>Дата формирования</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php
					if($oldRevises){
						$contrs_name = ArrayHelper::map($contractors,'ID','NAME');
						foreach ($oldRevises as $o) {
							?>

							<tr>
								<td><?php echo date("d.m.Y",strtotime($o['DATE_START']));?></td>
								<td><?php echo date("d.m.Y",strtotime($o['DATE_FINISH']));?></td>
								<td><?php echo array_key_exists($o['CONTRACTOR_ID'], $contrs_name) ? $contrs_name[$o['CONTRACTOR_ID']] : "";?></td>
								<td><?php echo $o['WITH_NDS'] && 1 ? "Да" : "Нет";?></td>
								<td><?php echo date("H:i d.m.Y",strtotime($o['CREATED_AT']));?></td>
								<td>
									<?php 
										if(file_exists($file_path.$f['FILE'])){
											echo Html::a("Посмотреть",$component->getActionUrl("downloadRevice",['revice'=>$o['ID']]),['target'=>"_blank"]);
										}else{
											echo "Файл не найден";
										}
										
									?>
								</td>
							</tr>

							<?php
						}
					}
				?>
			</tbody>
			<tfoot>
				
			</tfoot>
		</table>
	</div>
</div>
<?php } ?>

