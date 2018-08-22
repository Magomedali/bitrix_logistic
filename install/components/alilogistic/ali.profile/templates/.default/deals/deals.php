<?php

$deals = is_array($arResult['deals']) && count($arResult['deals']) ? $arResult['deals'] : null;

?>


<div class="organisations-page">
	<div class="panel">
		<a href="<?php echo $component->getUrl('dealform')?>">Создать заявку</a>
	</div>
	<div class="organisations">
		<table class="table table-bordered  table-hover">
			<thead>
				<tr>
					<th>#</th>
					<th>Наименование</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php
					if($deals){
						foreach ($deals as $key => $o) {
							?>

							<tr>
								<td><?php echo ++$key?></td>
								<td><?php echo $o['NAME'];?></td>
								<td>
									<a href="<?php echo $component->getUrl('viewdeal',['id'=>$o['ID']])?>">Подробнее</a>
									<a href="<?php echo $component->getUrl('dealform',['id'=>$o['ID']])?>">Редактировать</a>
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