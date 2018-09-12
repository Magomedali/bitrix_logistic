<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Ali\Logistic\helpers\Html;
use Ali\Logistic\helpers\ArrayHelper;
use Ali\Logistic\Dictionary\RoutesKind;
use Ali\Logistic\Dictionary\DealStates;
use Ali\Logistic\Dictionary\WayOfTransportation;
use Ali\Logistic\Dictionary\HowPacked;
use Ali\Logistic\Dictionary\DealFileType;
use Ali\Logistic\Deals;

$deal = is_array($arResult['deal']) && count($arResult['deal']) ? $arResult['deal'] : null;
$routes = is_array($arResult['routes']) && count($arResult['routes']) ? $arResult['routes'] : array();
$costs = is_array($arResult['costs']) && count($arResult['costs']) ? $arResult['costs'] : array();

$parts = explode("/", $_SERVER['HTTP_REFERER']);
$page = array_pop($parts);
$page = $page ? $page : end($parts);

if($page){
	$arResult['breadcrumbs'][]=[
		'title'=>"Заявки",
		'url'=>$page
	];
}

$pageTitle = $deal['NAME'];

$arResult['breadcrumbs'][]=[
		'title'=>$pageTitle,
		'link'=>null,
		'active'=>true
];

function htmlFilelink($component,$files,$type){
	if(isset($files[$type]) && is_array($files[$type])){
		$f = $files[$type];
		$fPath = DealFileType::getFilePath($type);
		if(isset($f['FILE']) && file_exists($fPath.$f['FILE'])){
			return Html::a("Открыть",$component->getActionUrl('downloadFile',['f'=>$f['ID']]),['target'=>'_blank']);
		}else{
			return "Файл не найден!";
		}
	} 
}
?>

<?php $this->getComponent()->includeComponentTemplate("helpers/breadcrumbs"); ?>
<div class="row" id="alilogistic">
	<div class="col-xs-12">
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#routes">Маршруты</a></li>
			<li><a data-toggle="tab" href="#cargo">Груз</a></li>
			<li><a data-toggle="tab" href="#ts">Транспорт</a></li>
			<li><a data-toggle="tab" href="#additional">Дополнительные услуги</a></li>
			<li><a data-toggle="tab" href="#costs">Расчет стоимости</a></li>
			<li><a data-toggle="tab" href="#docs">Документы</a></li>
		</ul>

		<div class="tab-content">
			<!-- Routes tab -->
			<div id="routes" class="tab-pane fade in active">
				<div class="row">
					<div class="col-xs-12">
						<h2>Маршрут</h2>
							<table class="table table-bordered table-hover" id="formRoutesTable">
								<thead>
									<tr>
										<th style="min-width: 100px;">Тип</th>
										<th>Время от</th>
										<th>Время до</th>
										<th>Организация</th>
										<th>Населенный пункт</th>
										<th>Адрес</th>
										<th>Контактное лицо</th>
										<th>Телефон</th>
										<th>Комментарий</th>
									</tr>
								</thead>
								<tbody>
									<?php
										if(count($routes)){
											foreach ($routes as $key => $r) {
												?>
												<tr>
													<td><?php echo RoutesKind::getLabels($r['KIND']);?></td>
													<td><?php echo Html::encode($r['START_AT']);?></td>
													<td><?php echo Html::encode($r['FINISH_AT']);?></td>
													<td><?php echo Html::encode($r['ORGANISATION']);?></td>
													<td><?php echo Html::encode($r['TOWN']);?></td>
													<td><?php echo Html::encode($r['ADDRESS']);?></td>
													<td><?php echo Html::encode($r['PERSON']);?></td>
													<td><?php echo Html::encode($r['PHONE']);?></td>
													<td><?php echo Html::encode($r['COMMENT']);?></td>
												</tr>
												<?php
											}
										}
									?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<!-- Cargo tab -->
			<div id="cargo" class="tab-pane fade in">
				<div class="row">
					<div class="col-xs-12">
						<h2>Груз</h2>
						<table class="table table-hover">
							<thead>
								<tr>
									<td><strong>Номер</strong></td>
									<td><?php echo $deal['DOCUMENT_NUMBER']?></td>
								</tr>
								<tr>
									<td><strong>Наименование</strong></td>
									<td><?php echo $deal['NAME']?></td>
								</tr>
								<tr>
									<td><strong>Статус</strong></td>
									<td><?php echo DealStates::getLabels($deal['STATE'])?></td>
								</tr>
								<tr>
									<td><strong>Организация</strong></td>
									<td><?php echo $deal['CONTRACTOR_NAME']?></td>
								</tr>
								<tr>
									<td><strong>Длина</strong></td>
									<td><?php echo $deal['LENGTH']?></td>
								</tr>
								<tr>
									<td><strong>Ширина</strong></td>
									<td><?php echo $deal['WIDTH']?></td>
								</tr>
								<tr>
									<td><strong>Высота</strong></td>
									<td><?php echo $deal['HEIGHT']?></td>
								</tr>
								<tr>
									<td><strong>Объем</strong></td>
									<td><?php echo $deal['SPACE']?></td>
								</tr>
								<tr>
									<td><strong>Как упакован</strong></td>
									<td><?php echo HowPacked::getLabels($deal['HOW_PACKED'])?></td>
								</tr>
								<tr>
									<td><strong>Количество мест</strong></td>
									<td><?php echo $deal['COUNT_PLACE']?></td>
								</tr>
								<tr>
									<td><strong>С НДС</strong></td>
									<td><?php echo boolval($deal['WITH_NDS'])? "Да" :"Нет";?></td>
								</tr>
								<tr>
									<td><strong>Комментраии</strong></td>
									<td><?php echo $deal['COMMENTS']?></td>
								</tr>
								<tr>
									<td><strong>Прикрепленный файл:</strong></td>
									<td>
										<?php 
											$path = Deals::getPublicPathDealFiles();

											if($deal['PRINT_FORM'] && file_exists(ALI_DEAL_FILES.$deal['PRINT_FORM'])){
												echo Html::a("Скачать",$component->getActionUrl('getDealFile',['id'=>$deal['ID']]),['target'=>'_blank']);
											}
										?>
									</td>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>


			<!-- Ts tab -->
			<div id="ts" class="tab-pane fade in">
				<div class="row">
					<div class="col-xs-12">
						<h2>Транспорт</h2>
						<table class="table table-hover">
							<thead>
								<tr>
									<td><strong>Tип транспортного средства</strong></td>
									<td><?php echo $deal['TYPE_OF_VEHICLE']?></td>
								</tr>
								<tr>
									<td><strong>Способ погрузки</strong></td>
									<td><?php echo $deal['LOADING_METHOD']?></td>
								</tr>
								<tr>
									<td><strong>Способ разгрузки</strong></td>
									<td><?php echo $deal['UNLOADING_METHOD']?></td>
								</tr>
								<tr>
									<td><strong>Способ перевозки</strong></td>
									<td><?php echo WayOfTransportation::getLabels($deal['WAY_OF_TRANSPORTATION']);?></td>
								</tr>
								
								<tr>
									<td><strong>Темп. от</strong></td>
									<td><?php echo $deal['REQUIRES_TEMPERATURE_FROM'] ? $deal['REQUIRES_TEMPERATURE_FROM'] :"";?></td>
								</tr>
								<tr>
									<td><strong>Темп. до</strong></td>
									<td><?php echo $deal['REQUIRES_TEMPERATURE_TO'] ? $deal['REQUIRES_TEMPERATURE_TO'] : "";?></td>
								</tr>
								<tr>
									<td><strong>Адр класс</strong></td>
									<td><?php echo $deal['ADR_CLASS'] ? $deal['ADR_CLASS'] : "" ;?></td>
								</tr>
								<tr>
									<td><strong>Дополнительные оборудования</strong></td>
									<td><?php echo $deal['ADDITIONAL_EQUIPMENT']?></td>
								</tr>

								<tr>
									<td><strong>Документы</strong></td>
									<td><?php echo $deal['REQUIRED_DOCUMENTS']?></td>
								</tr>

								<tr>
									<td><strong>Водитель гражданин России</strong></td>
									<td><?php echo boolval($deal['REQUIRED_RUSSIAN_DRIVER']) ? "Да" : "Нет";?></td>
								</tr>

								<tr>
									<td><strong>Информация о водителе:</strong></td>
									<td><?php echo $deal['DRIVER_INFO'];?></td>
								</tr>

								<tr>
									<td><strong>Транспортное средство:</strong></td>
									<td><?php echo $deal['VEHICLE'];?></td>
								</tr>

							</thead>
						</table>
					</div>
				</div>
			</div>


			<!-- Additional tab -->
			<div id="additional" class="tab-pane fade in">
				<div class="row">
					<div class="col-xs-12">
						<h2>Дополнительные услуги</h2>
						<table class="table table-hover">
							<thead>
								<tr>
									<td><strong>Количество грузчиков</strong></td>
									<td><?php echo $deal['COUNT_LOADERS'] ? $deal['COUNT_LOADERS'] :"";?></td>
								</tr>
								<tr>
									<td><strong>Количество часов</strong></td>
									<td><?php echo $deal['COUNT_HOURS'] ? $deal['COUNT_HOURS'] : "";?></td>
								</tr>
								<tr>
									<td><strong>Страхование?</strong></td>
									<td><?php echo boolval($deal['REQUIRED_INSURANCE'])? "Да" :"Нет";?></td>
								</tr>
								<tr>
									<td><strong>Стоимость руб.</strong></td>
									<td><?php echo $deal['SUM']? $deal['SUM'] :"";?></td>
								</tr>
								<tr>
									<td><strong>Спецтехника</strong></td>
									<td><?php echo $deal['SPECIAL_EQUIPMENT'];?></td>
								</tr>
								<tr>
									<td><strong>Сопровождение?</strong></td>
									<td><?php echo boolval($deal['CARGO_HANDLING'])? "Да" :"Нет";?></td>
								</tr>

								<tr>
									<td><strong>Погрузо-разгрузочные работы?</strong></td>
									<td><?php echo boolval($deal['REQUIRED_SUPPORT'])? "Да" :"Нет";?></td>
								</tr>

								<tr>
									<td><strong>Ответственное хранение</strong></td>
									<td><?php echo boolval($deal['SECURE_STORAGE'])? "Да" :"Нет";?></td>
								</tr>

								<tr>
									<td><strong>Кросс-докинг</strong></td>
									<td><?php echo boolval($deal['CROSS_DOCKING'])? "Да" :"Нет";?></td>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>


			<!-- Costs tab -->
			<div id="costs" class="tab-pane fade in">
				<div class="row">
					<div class="col-xs-12">
						<h2>Расчеты стоимости</h2>
							<table class="table table-bordered table-hover" id="formCostsTable">
								<thead>
									<tr>
										<th>Вид услуги</th>
										<th>Цена</th>
										<th>Количество</th>
										<th>Сумма</th>
										<th>Дата</th>
									</tr>
								</thead>
								<tbody>
									<?php
										if(count($costs)){
											foreach ($costs as $key => $c) {
												?>
												<tr>
													<td><?php echo Html::encode($c['KIND_SERVICE']);?></td>
													<td><?php echo Html::encode($c['COST']);?></td>
													<td><?php echo Html::encode($c['QUANTITY']);?></td>
													<td><?php echo Html::encode($c['AMOUNT']);?></td>
													<td><?php echo date("H:i d.m.Y",strtotime($c['CREATED_AT']));?></td>
												</tr>
												<?php
											}
										}
									?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<!-- Docs tab -->
			<div id="docs" class="tab-pane fade in">
				<div class="row">
					<div class="col-xs-12">
						<h2>Документы</h2>
						<table class="table table-bordered table-hover" id="docsDeal">
							<tbody>
							<tr>
								<td><strong><?php echo DealFileType::getLabels(DealFileType::FILE_BILL);?></strong></td>
								<td><?php echo htmlFilelink($component,$deal['files'],DealFileType::FILE_BILL); ?></td>
							</tr>
							<tr>	
								<td><strong><?php echo DealFileType::getLabels(DealFileType::FILE_ACT);?></strong></td>
								<td><?php echo htmlFilelink($component,$deal['files'],DealFileType::FILE_ACT); ?></td>
							</tr>
							<tr>
								<td><strong><?php echo DealFileType::getLabels(DealFileType::FILE_INVOICE);?></strong></td>
								<td><?php echo htmlFilelink($component,$deal['files'],DealFileType::FILE_INVOICE); ?></td>
							</tr>
							<tr>
								<td><strong><?php echo DealFileType::getLabels(DealFileType::FILE_DRIVER_ATTORNEY);?></strong></td>
								<td><?php echo htmlFilelink($component,$deal['files'],DealFileType::FILE_DRIVER_ATTORNEY); ?></td>
							</tr>
							<tr>
								<td><strong><?php echo DealFileType::getLabels(DealFileType::FILE_CONTRACT);?></strong></td>
								<td><?php echo htmlFilelink($component,$deal['files'],DealFileType::FILE_CONTRACT); ?></td>
							</tr>
							<tr>
								<td><strong><?php echo DealFileType::getLabels(DealFileType::FILE_TTH);?></strong></td>
								<td><?php echo htmlFilelink($component,$deal['files'],DealFileType::FILE_TTH); ?></td>
							</tr>
							<tr>
								<td><strong><?php echo DealFileType::getLabels(DealFileType::FILE_PRINT_FORM);?></strong></td>
								<td><?php echo htmlFilelink($component,$deal['files'],DealFileType::FILE_PRINT_FORM); ?></td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
