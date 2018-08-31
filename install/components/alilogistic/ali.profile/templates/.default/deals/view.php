<?php

use Ali\Logistic\helpers\Html;
use Ali\Logistic\helpers\ArrayHelper;
use Ali\Logistic\Dictionary\RoutesKind;
use Ali\Logistic\Dictionary\DealStates;
use Ali\Logistic\Dictionary\WayOfTransportation;

$deal = is_array($arResult['deal']) && count($arResult['deal']) ? $arResult['deal'] : null;
$routes = is_array($arResult['routes']) && count($arResult['routes']) ? $arResult['routes'] : array();
?>
<div class="row">
	<div class="col-xs-12">
		<h2>Заявка <?php echo Html::encode($deal['NAME']);?></h2>
	</div>
</div>

<div class="row">
	<div class="col-xs-4">
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
					<td><strong>С НДС</strong></td>
					<td><?php echo boolval($deal['WITH_NDS'])? "Да" :"Нет";?></td>
				</tr>
			</thead>
		</table>
	</div>
	<div class="col-xs-4">
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
					<td><strong>Способ перевозки</strong></td>
					<td><?php echo WayOfTransportation::getLabels($deal['WAY_OF_TRANSPORTATION']);?></td>
				</tr>
				<tr>
					<td><strong>Количество грузчиков</strong></td>
					<td><?php echo $deal['COUNT_LOADERS']?></td>
				</tr>
				<tr>
					<td><strong>Количество часов</strong></td>
					<td><?php echo $deal['COUNT_HOURS']?></td>
				</tr>
				<tr>
					<td><strong>Страхование?</strong></td>
					<td><?php echo boolval($deal['REQUIRED_INSURANCE'])? "Да" :"Нет";?></td>
				</tr>
				<tr>
					<td><strong>Темп. от</strong></td>
					<td><?php echo $deal['REQUIRES_TEMPERATURE_FROM'];?></td>
				</tr>
				<tr>
					<td><strong>Темп. до</strong></td>
					<td><?php echo $deal['REQUIRES_TEMPERATURE_TO'];?></td>
				</tr>
				<tr>
					<td><strong>Сопровождение?</strong></td>
					<td><?php echo boolval($deal['REQUIRED_SUPPORT'])? "Да" :"Нет";?></td>
				</tr>
				<tr>
					<td><strong>Дополнительные оборудования</strong></td>
					<td><?php echo $deal['ADDITIONAL_EQUIPMENT']?></td>
				</tr>

				<tr>
					<td><strong>Документы</strong></td>
					<td><?php echo $deal['REQUIRED_DOCUMENTS']?></td>
				</tr>

			</thead>
		</table>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<h4>Маршрут</h4>
			<table class="table table-bordered table-hover" id="formRoutesTable">
				<thead>
					<tr>
								<th style="min-width: 100px;">Тип</th>
								<th>Время от</th>
								<th>Время до</th>
								<th>Организация</th>
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