<?php

$orgs = is_array($arResult['orgs']) && count($arResult['orgs']) ? $arResult['orgs'] : null;

?>


<div class="organisations-page">
	<div class="panel">
		<a href="<?php echo $component->getUrl('formorg')?>">Добавить организацию</a>
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
									<a href="<?php echo $component->getUrl('formorg',['id'=>$o['ID']])?>">Редактировать</a>
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