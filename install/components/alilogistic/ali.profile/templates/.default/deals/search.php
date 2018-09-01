<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Ali\Logistic\Dictionary\DealStates;

$deals = is_array($arResult['deals']) && count($arResult['deals']) ? $arResult['deals'] : null;
$type = isset($arResult['type']) ? $arResult['type'] : 'IS_ACTIVE';

?>


<div class="organisations-page">
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
									<a href="<?php echo $component->getUrl('dealform',['replicate'=>$o['ID']])?>">Копировать</a>
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