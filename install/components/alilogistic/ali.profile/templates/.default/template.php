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
$orgs = is_array($arResult['orgs']) && count($arResult['orgs']) ? $arResult['orgs'] : null;
$hasntContractors = isset($arResult['hasntContractors']) ? $arResult['hasntContractors'] : false;

?>
<?php $this->getComponent()->includeComponentTemplate("helpers/breadcrumbs"); ?>
<?php if(!empty($user)){ ?>
<div id="alilogistic" class="row personal_page">
	<?php 
		if(boolval($hasntContractors)){
			?>
			<div class="col-xs-12">
				<div class="alert alert-warning">
					Извените, у вас нет зарегистрированных организаций. Прежде чем создать заявку, вам необходимо зарегистрировать свою организацию либо присоединиться к другой учетной записи!  	
				</div>
			</div>
			<?php
		}
	?>
	<div class="col-xs-12">
		<div class="row">
			<div class="col-xs-12">
				<?php echo Html::a("Редактировать",$component->getUrl("profileform"),['class'=>'btn btn-primary']);?>
		
				<?php echo !$hasCompany ? Html::a("Присоединиться к другой учетной записи",$component->getActionUrl("follow"),['class'=>'btn btn-default']) : null;?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<table class="table table-hover">
					<thead>
						<tr>
							<td><strong>Фамилия</strong></td>
							<td><strong>Имя</strong></td>
							<td><strong>Отчество</strong></td>
							<td><strong>Email</strong></td>
							<td><strong>Личный телефон</strong></td>
							<td><strong>Рабочий телефон</strong></td>
						</tr>
						<tr>
							<td><?php echo $user['LAST_NAME']?></td>
							<td><?php echo $user['NAME']?></td>
							<td><?php echo $user['SECOND_NAME']?></td>
							<td><?php echo $user['EMAIL']?></td>
							<td><?php echo $user['PERSONAL_PHONE']?></td>
							<td><?php echo $user['WORK_PHONE']?></td>
						</tr>
					</thead>
				</table>
			</div>
		</div>
		
	</div>
	
</div>
<div class="row organisations-page">
	<div class="panel">
		<a href="<?php echo $component->getUrl('formorg')?>" class='btn btn-primary'>Добавить организацию</a>
	</div>
	<div class="organisations">
		<table class="table table-bordered  table-hover">
			<thead>
				<tr>
					<th>#</th>
					<th>Наименование</th>
					<th>ИНН</th>
					<th>КПП</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php
					if($orgs){
						foreach ($orgs as $key => $o) {
							?>

							<tr>
								<td><?php echo ++$key?></td>
								<td><?php echo $o['NAME'];?></td>
								<td><?php echo $o['INN'];?></td>
								<td><?php echo $o['KPP'];?></td>
								<td>
									<a href="<?php echo $component->getUrl('vieworg',['id'=>$o['ID']])?>">Подробнее</a>
									<?php if(false){?>
										<a href="<?php echo $component->getUrl('formorg',['id'=>$o['ID']])?>">Редактировать</a>
									<?php } ?>
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

