<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Ali\Logistic\Helpers\Html;
use Ali\Logistic\Dictionary\DealStates;
use Ali\Logistic\Dictionary\DealFileType;

$deals = is_array($arResult['deals']) && count($arResult['deals']) ? $arResult['deals'] : null;
$filtres = is_array($arResult['filtres']) && count($arResult['filtres']) ? $arResult['filtres'] : null;
$hasFilter=is_array($filtres)&& array_key_exists('Filter',$filtres) && is_array($filtres['Filter']) && count($filtres['Filter']);


$total = isset($arResult['total']) ? $arResult['total'] : 0;
$page = isset($arResult['page']) ? $arResult['page'] : 1;
$limit = isset($arResult['limit']) ? $arResult['limit'] : 20;
$pageTitle = isset($arResult['pageTitle']) ? $arResult['pageTitle'] : "Текущие заявки";



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
			return Html::a(DealFileType::getLabels($type),$component->getActionUrl('downloadFile',['f'=>$f['ID']]),['target'=>'_blank'])."<br>";
		}else{
			return "Файл '".DealFileType::getLabels($type)."' не найден!<br>";
		}
	}

}
?>
<?php $this->getComponent()->includeComponentTemplate("helpers/breadcrumbs"); ?>
<div class="row filtres" id="alilogistic">
	<div class="col-xs-12">
		<div class="panel panel-primary  form-route form-route_between" data-number="<?php echo $number; ?>">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion_filters" href="#collapse_filters">Параметры фильтра</a>
				</h4>
			</div>
			<div class="collapse <?php echo $hasFilter ? 'in' :'';?>" id="collapse_filters">
				<div class="panel-body">
					<form action="" method="GET">
						<div class="row">
							<div class="col-xs-2">
								<label>С:</label>
								<?php echo Html::input("date",'Filter[date_from]',isset($filtres['Filter']['date_from']) && strtotime($filtres['Filter']['date_from']) ? date("Y-m-d",strtotime($filtres['Filter']['date_from'])) : null,['class'=>'form-control']);?>
							</div>
							<div class="col-xs-2">
								<label>По:</label>
								<?php echo Html::input("date",'Filter[date_to]',isset($filtres['Filter']['date_to']) && strtotime($filtres['Filter']['date_to']) ? date("Y-m-d",strtotime($filtres['Filter']['date_to'])) : null,['class'=>'form-control']);?>
							</div>
							<div class="col-xs-2">
								<label>Номер:</label>
								<?php echo Html::input("text",'Filter[number]',isset($filtres['Filter']['number']) ? $filtres['Filter']['number'] : "",['class'=>'form-control']);?>
							</div>
							<div class="col-xs-3">
								<label>Водитель:</label>
								<?php echo Html::input("text",'Filter[driver]',isset($filtres['Filter']['driver']) ? $filtres['Filter']['driver'] : "",['class'=>'form-control']);?>
							</div>
							<div class="col-xs-3">
								<label>№ ТС:</label>
								<?php echo Html::input("text",'Filter[ts]',isset($filtres['Filter']['ts']) ? $filtres['Filter']['ts'] : "",['class'=>'form-control']);?>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-3">
								<label>Статус:</label>
								<?php echo Html::dropDownList('Filter[state]',isset($filtres['Filter']['state']) ? $filtres['Filter']['state'] : null,DealStates::getLabels(),['class'=>'form-control','prompt'=>'Выберите статус']);?>
							</div>
							<div class="col-xs-2">
								<label>Вес С:</label>
								<?php echo Html::input("number",'Filter[weight_f]',isset($filtres['Filter']['weight_f']) ? $filtres['Filter']['weight_f'] : "",['class'=>'form-control']);?>
							</div>


							<div class="col-xs-2">
								<label>Вес По:</label>
								<?php echo Html::input("number",'Filter[weight_t]',isset($filtres['Filter']['weight_t']) ? $filtres['Filter']['weight_t'] : "",['class'=>'form-control']);?>
							</div>


							<div class="col-xs-2">
								<label>Объем С:</label>
								<?php echo Html::input("number",'Filter[space_f]',isset($filtres['Filter']['space_f']) ? $filtres['Filter']['space_f'] : "",['class'=>'form-control']);?>
							</div>


							<div class="col-xs-2">
								<label>Объем По:</label>
								<?php echo Html::input("number",'Filter[space_t]',isset($filtres['Filter']['space_t']) ? $filtres['Filter']['space_t'] : "",['class'=>'form-control']);?>
							</div>
						</div>
						<div class="row">

							<div class="col-xs-3">
								<label>Стадия обработки:</label>
								<?php echo Html::dropDownList('Filter[stage]',isset($filtres['Filter']['stage']) ? $filtres['Filter']['stage'] : null,['IS_ACTIVE'=>'Текущие','COMPLETED'=>'Завершенные','IS_DRAFT'=>"Черновик"],['class'=>'form-control','prompt'=>'Стадия обработки']);?>
							</div>

							<div class="col-xs-3">
								<label>Место погрузки:</label>
								<?php echo Html::input("text",'Filter[loading]',isset($filtres['Filter']['loading']) ? $filtres['Filter']['loading'] : "",['class'=>'form-control']);?>
							</div>
							<div class="col-xs-3">
								<label>Место разгрузки:</label>
								<?php echo Html::input("text",'Filter[unloading]',isset($filtres['Filter']['unloading']) ? $filtres['Filter']['unloading'] : "",['class'=>'form-control']);?>
							</div>
							<div class="col-xs-2">
								<?php
									echo Html::submitButton("Найти",['class'=>'btn btn-primary','style'=>'margin-top:25px;']);
								?>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	
	<div class="col-xs-12 deals" style="margin-top: 15px;">
		<table class="table table-bordered  table-hover">
			<thead>
				<tr>
					<th>Номер</th>
					<th>Дата заявки</th>
					<th>Наименование</th>
					<th>Вес, кг</th>
					<th>Объем, м3</th>
					<th>Стоимость Руб.</th>
					<th>ФИО водителя</th>
					<th>№ ТС</th>
					<th>Место погрузки</th>
					<th>Место разгрузки</th>
					<th>Статус</th>
					<th>Документы</th>
				</tr>
			</thead>
			<tbody>
				<?php
					if($deals){
						foreach ($deals as $key => $o) {
							?>

							<tr>
								<td>
									<?php echo $o['DOCUMENT_NUMBER'];?>
								</td>
								<td><?php echo date("H:i d.m.Y",strtotime($o['CREATED_AT']))?></td>
								<td  style="position:relative;">
									<?php echo Html::a($o['NAME'],null,['class'=>'deal_actions'])?>
									<div class="deal_actions_block">
										<?php 
											if((int)$o['STATE'] < DealStates::IN_PLANNING){
												?>
												<a href="<?php echo $component->getUrl('dealform',['id'=>$o['ID']])?>">
													<i class="glyphicon glyphicon-pencil"></i> Редактировать
												</a><br>
												<?php
											}
										?>
										<a href="<?php echo $component->getUrl('viewdeal',['id'=>$o['ID']])?>">
											<i class="glyphicon glyphicon-eye-open"></i> Подробнее
										</a><br>
										<a href="<?php echo $component->getUrl('dealform',['replicate'=>$o['ID']])?>">
											<i class="glyphicon glyphicon-copyright-mark"></i> Копировать
										</a>
									</div>	
								</td>
								<td><?php echo $o['WEIGHT'] ? $o['WEIGHT'] : "";?></td>
								<td><?php echo $o['SPACE'] ? $o['SPACE'] : "";?></td>
								<td><?php echo $o['SUM'] ? $o['SUM'] : "";?></td>
								<td><?php echo $o['DRIVER_INFO'];?></td>
								<td><?php echo $o['VEHICLE'];?></td>
								<td><?php echo $o['LOADING_PLACE'];?></td>
								<td><?php echo $o['UNLOADING_PLACE'];?></td>
								<td><?php echo DealStates::getLabels($o['STATE']);?></td>
								<td>
									<?php 
										if(isset($o['files'])){
										 	echo htmlFilelink($component,$o['files'],DealFileType::FILE_BILL);
										}
									
										if(isset($o['files'])){
										 	echo htmlFilelink($component,$o['files'],DealFileType::FILE_ACT);
										}
									 
										if(isset($o['files'])){
										 	echo htmlFilelink($component,$o['files'],DealFileType::FILE_INVOICE);
										}
									
										if(isset($o['files'])){
										 	echo htmlFilelink($component,$o['files'],DealFileType::FILE_TTH);
										}
									?>
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

	<div class="col-xs-12">
		<?php $this->getComponent()->includeComponentTemplate("helpers/pagination"); ?>
	</div>
<script type="text/javascript">
	
	$(".deal_actions").click(function(event){
		event.preventDefault();

		var tthis = $(this);
		var this_block = tthis.siblings(".deal_actions_block");
		$(".deal_actions_block").not(this_block).hide();
		this_block.toggle();
	});
</script>	
</div>

