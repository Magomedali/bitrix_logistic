<?php


$org = is_array($arResult['org']) && count($arResult['org']) ? $arResult['org'] : null;

?>

<div class="row">
	<div class="col-xs-12">
		<?php if($org){?>
		<h3><?php echo $org['NAME']?></h3>
		<a href="<?php echo $component->getUrl("formorg",['id'=>$org['ID']])?>">Редактировать</a>
		<a href="<?php echo $component->getUrl("removeorg",['id'=>$org['ID']])?>">Удалить</a>
		<table class="table table-hover">
			<thead>
				
			</thead>
			<tbody>
				<tr>
					<td>Юридический адрес</td><td><?php echo $org['LEGAL_ADDRESS']?></td>
				</tr>
				<tr>
					<td>Физический адрес</td><td><?php echo $org['PHYSICAL_ADDRESS']?></td>
				</tr>
				<tr>
					<td>Вид организации</td><td><?php echo $org['ENTITY_TYPE']?></td>
				</tr>
				<tr>	
					<td>ИНН</td><td><?php echo $org['INN']?></td>
				</tr>
				<tr>
					<td>КПП</td><td><?php echo $org['KPP']?></td>
				</tr>
				<tr>
					<td>ОГРН</td><td><?php echo $org['OGRN']?></td>
				</tr>
				<tr>	
					<td>Наименование Банка</td><td><?php echo $org['BANK_NAME']?></td>
				</tr>
				<tr>
					<td>Бик Банка</td><td><?php echo $org['BANK_BIK']?></td>
				</tr>
				<tr>
					<td>Расчетный счет</td><td><?php echo $org['CHECKING_ACCOUNT']?></td>
				</tr>
				<tr>	
					<td>Корреспондентский счет</td><td><?php echo $org['CORRESPONDENT_ACCOUNT']?></td>
				</tr>
				<tr>	
					<td>Интегрирован в 1С</td><td><?php echo $org['INTEGRATED_ID'] ? "Да" : "Нет";?></td>
				</tr>
			</tbody>
			<tfoot>
				
			</tfoot>
		</table>
		<?php } ?>
	</div>
</div>