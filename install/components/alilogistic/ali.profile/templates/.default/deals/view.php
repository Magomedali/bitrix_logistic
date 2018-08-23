<?php

use Ali\Logistic\helpers\Html;
use Ali\Logistic\helpers\ArrayHelper;
use Ali\Logistic\Dictionary\RoutesKind;

$deal = is_array($arResult['deal']) && count($arResult['deal']) ? $arResult['deal'] : null;
$routes = is_array($arResult['routes']) && count($arResult['routes']) ? $arResult['routes'] : array();
?>
<div class="row">
	<div class="col-xs-12">
		<h2>Заявка <?php echo Html::encode($deal['NAME']);?></h2>
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