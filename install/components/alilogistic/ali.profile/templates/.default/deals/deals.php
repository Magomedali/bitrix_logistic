<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Ali\Logistic\Helpers\Html;
use Ali\Logistic\Dictionary\DealStates;
use Ali\Logistic\Dictionary\DealFileType;

$deals = is_array($arResult['deals']) && count($arResult['deals']) ? $arResult['deals'] : null;
$filters = is_array($arResult['filters']) && count($arResult['filters']) ? $arResult['filters'] : null;

$total = isset($arResult['total']) ? $arResult['total'] : 0;
$page = isset($arResult['page']) ? $arResult['page'] : 1;
$limit = isset($arResult['limit']) ? $arResult['limit'] : 20;

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

<div id="filters" class="row">
	<div class="col-xs-12">
		<form action="" method="GET">
			<div class="row">
				<div class="col-xs-2">
					<?php
						//echo Html::submitButton("Найти",null,['class'=>'btn btn-primary']);
					?>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="deals-page">
	<div class="deals">
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
								<td>
									<?php 

									?>
								</td>
								<td>
									<?php 
										 if(isset($o['files'])){
										 	echo htmlFilelink($component,$o['files'],DealFileType::FILE_BILL);
										 }
									?>
								</td>
								<td>
									<?php 
										 if(isset($o['files'])){
										 	echo htmlFilelink($component,$o['files'],DealFileType::FILE_ACT);
										 }
									?>
								</td>
								<td>
									<?php 
										 if(isset($o['files'])){
										 	echo htmlFilelink($component,$o['files'],DealFileType::FILE_INVOICE);
										 }
									?>
								</td>
								<td>
									<?php 
										 if(isset($o['files'])){
										 	echo htmlFilelink($component,$o['files'],DealFileType::FILE_TTH);
										 }
									?>
								</td>
								<td>
									<?php 
										if((int)$o['STATE'] < DealStates::IN_PLANNING){
											?>
											<a href="<?php echo $component->getUrl('dealform',['id'=>$o['ID']])?>">Редактировать</a>
											<?php
										}
									?>
									<a href="<?php echo $component->getUrl('viewdeal',['id'=>$o['ID']])?>">Подробнее</a>
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

	<div class="row">
		<div class="col-xs-12">
			<?php $this->getComponent()->includeComponentTemplate("helpers/pagination"); ?>
		</div>
	</div>
</div>