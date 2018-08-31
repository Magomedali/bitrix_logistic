<?php

use Ali\Logistic\Dictionary\DealStates;

$deals = is_array($arResult['deals']) && count($arResult['deals']) ? $arResult['deals'] : null;
$type = isset($arResult['type']) ? $arResult['type'] : 'IS_ACTIVE';

switch ($type) {
	case 'IS_ACTIVE':
		$type = "Текущие заявки";
		break;

	case 'IS_DRAFT':
		$type = "Черновики";
		break;
	
	default:
		$type = "Текущие заявки";
		break;
}
$APPLICATION->SetTitle($title);
?>

<div class="row">
	<div class="col-xs-12">
		<h2><?php echo $title; ?></h2>
	</div>
</div>
<div class="organisations-page">
	<div class="panel">
		<a href="<?php echo $component->getUrl('dealform')?>">Создать заявку</a>
	</div>
	<div class="organisations">
		<table class="table table-bordered  table-hover">
			<thead>
				<tr>
					<th>Номер</th>
					<th>Наименование</th>
					<th>Вес, кг</th>
					<th>Объем, м3</th>
					<th>ФИО водителя</th>
					<th>№ ТС</th>
					<th>Статус</th>
					<th>Стоимость, руб.</th>
					<th>Cчет</th>
					<th>Акт</th>
					<th>Счет фактура</th>
					<th>ТТН</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php
					if($deals){
						foreach ($deals as $key => $o) {
							?>

							<tr>
								<td><?php echo $o['DOCUMENT_NUMBER']?></td>
								<td><?php echo $o['NAME'];?></td>
								<td><?php echo $o['WEIGHT'];?></td>
								<td><?php echo $o['SPACE'];?></td>
								<td><?php echo $o['DRIVER_INFO'];?></td>
								<td><?php echo $o['VEHICLE'];?></td>
								<td><?php echo DealStates::getLabels($o['STATE']);?></td>
								<td><?php ?></td>
								<td><?php ?></td>
								<td><?php ?></td>
								<td><?php ?></td>
								<td><?php ?></td>
								<td>
									<a href="<?php echo $component->getUrl('viewdeal',['id'=>$o['ID']])?>">Подробнее</a>
									<a href="<?php echo $component->getUrl('dealform',['id'=>$o['ID']])?>">Редактировать</a>
									<a href="<?php echo $component->getUrl('dealform',['copy_id'=>$o['ID']])?>">Копировать</a>
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