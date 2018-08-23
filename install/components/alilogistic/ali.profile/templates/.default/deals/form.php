<?php

use Ali\Logistic\Dictionary\LoadingMethod;
use Ali\Logistic\Dictionary\TypeOfVehicle;
use Ali\Logistic\Dictionary\WayOfTransportation;
use Ali\Logistic\Dictionary\AdditionalEquipment;
use Ali\Logistic\Dictionary\Documents;
use Ali\Logistic\helpers\Html;
use Ali\Logistic\helpers\ArrayHelper;

$LoadingMethod = LoadingMethod::getLabels();
$TypeOfVehicle = TypeOfVehicle::getLabels();
$WayOfTransportation = WayOfTransportation::getLabels();
$AdditionalEquipment = AdditionalEquipment::getLabels();
$Documents = Documents::getLabels();

$errors = is_array($arResult['errors']) && count($arResult['errors']) ? $arResult['errors'] : null;
$deal = is_array($arResult['deal']) && count($arResult['deal']) ? $arResult['deal'] : null;
$contractors = is_array($arResult['contractors']) && count($arResult['contractors']) ? $arResult['contractors'] : array();
$routes = is_array($arResult['routes']) && count($arResult['routes']) ? $arResult['routes'] : array();

?>

<div class="row form-deal-page">
	<div class="row">
		<div class="col-xs-6">
			<?php if($deal){?>
				<h3>Организация <?php echo $deal['NAME']?></h3>
			<?php }else{ ?>
				<h3>Новая Заявка</h3>
			<?php } ?>
		</div>
	</div>
	<?php 
		if($errors){
	?>
		<div class="col-xs-6">
			<?php foreach ($errors as $key => $e) { ?>
				<div class="alert alert-warning">
					<div>
						<?php echo $e;?>
					</div>
				</div>
			<?php }?>
					
		</div>
	<?php } ?>
	<div class="form-deal col-xs-12">
		<form action="" method="POST" id="formDeal">
			<div class="row">
				<div class="col-xs-6">
					<p>
						<label for="deal_name" class="form-label">Наименование</label>
						<input type="text" name="DEAL[NAME]" id="deal_name" value="<?php echo $deal ? $deal['NAME'] : null;?>" class="form-control" required>
					</p>
				</div>
				<div class="col-xs-3">
					<p>
						<label for="deal_name" class="form-label">Организация</label>
						<?php
							echo Html::dropDownList("DEAL[CONTRACTOR_ID]",$deal['CONTRACTOR_ID'],ArrayHelper::map($contractors,'ID','NAME'),['class'=>'form-control']);
						?>
					</p>
				</div>
				<div class="col-xs-3">
					<p>
						<label for="deal_weight" class="form-label">Вес груза</label>
						<input type="number" name="DEAL[WEIGHT]" id="deal_weight" value="<?php echo $deal ? $deal['WEIGHT'] : null;?>" class="form-control" required>
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-3">
					<p>
						<label for="deal_width" class="form-label">Ширина (м)</label>
						<input type="number" name="DEAL[WIDTH]" id="deal_width" value="<?php echo $deal ? $deal['WIDTH'] : null;?>" class="form-control" required>
					</p>
				</div>
				<div class="col-xs-3">
					<p>
						<label for="deal_height" class="form-label">Высота (м)</label>
						<input type="number" name="DEAL[HEIGHT]" id="deal_height" value="<?php echo $deal ? $deal['HEIGHT'] : null;?>" class="form-control" required>
					</p>
				</div>

				<div class="col-xs-3">
					<p>
						<label for="deal_length" class="form-label">Длина (м)</label>
						<input type="number" name="DEAL[LENGTH]" id="deal_length" value="<?php echo $deal ? $deal['LENGTH'] : null;?>" class="form-control" required>
					</p>
				</div>
				<div class="col-xs-3">
					<p>
						<label for="deal_space" class="form-label">Объем (куб. м.)</label>
						<input type="number" name="DEAL[SPACE]" id="deal_space" value="<?php echo $deal ? $deal['SPACE'] : null;?>" class="form-control" required>
					</p>
				</div>
			</div>
			
			<div class="row">
				<div class="col-xs-4">
					<p>
						<label for="deal_type_od_vehicle" class="form-label">Tип транспортного средства</label>
						<?php 
							echo Html::dropDownList("DEAL[TYPE_OF_VEHICLE]",$deal['TYPE_OF_VEHICLE'],$TypeOfVehicle,['id'=>"deal_type_od_vehicle",'class'=>'form-control','prompt'=>'Выберите тип транспортного средства']);
						?>
					</p>
				</div>

				<div class="col-xs-4">
					<p>
						<label for="deal_loading_method" class="form-label">Способ погрузки</label>
						<?php 
							echo Html::dropDownList("DEAL[LOADING_METHOD]",$deal['LOADING_METHOD'],$LoadingMethod,['id'=>"deal_loading_method",'class'=>'form-control','prompt'=>'Выберите cпособ погрузки']);
						?>
					</p>
				</div>

				<div class="col-xs-4">
					<p>
						<label for="deal_way_of_transportation" class="form-label">Способ перевозки</label>
						<?php 
							echo Html::dropDownList("DEAL[WAY_OF_TRANSPORTATION]",$deal['WAY_OF_TRANSPORTATION'],$WayOfTransportation,['id'=>"deal_way_of_transportation",'class'=>'form-control','prompt'=>'Выберите cпособ перевозки']);
						?>
					</p>
				</div>
			</div>
			
			<div class="row">
				<div class="col-xs-4">
					<p>
						<label for="deal_requires_loader" class="form-label">Требуется грузчик?</label>
						<?php echo Html::checkbox("DEAL[REQUIRES_LOADER]",$deal['REQUIRES_LOADER'],['id'=>'deal_requires_loader','value'=>1]);?>
					</p>
				</div>
				<div class="col-xs-4">
					<p>
						<label for="deal_count_loader" class="form-label">Количество грузчиков</label>
						<?php echo Html::input("number","DEAL[COUNT_LOADERS]",$deal['COUNT_LOADERS'],['id'=>'deal_count_loader','class'=>'form-control']);?>
					</p>
				</div>
				<div class="col-xs-4">
					<p>
						<label for="deal_count_hours" class="form-label">Количество часов</label>
						<?php echo Html::input("number","DEAL[COUNT_HOURS]",$deal['COUNT_HOURS'],['id'=>'deal_count_hours','class'=>'form-control']);?>
					</p>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-4">
					<p>
						<label for="deal_requires_insurance" class="form-label">Требуется страхование?</label>
						<?php echo Html::checkbox("DEAL[REQUIRES_INSURANCE]",$deal['REQUIRES_INSURANCE'],['id'=>'deal_requires_insurance']);?>
					</p>
				</div>
				<div class="col-xs-4">
					<p>
						<label for="deal_req_temp_from" class="form-label">Темп. от</label>
						<?php echo Html::input("number","DEAL[REQUIRES_TEMPERATURE_FROM]",$deal['REQUIRES_TEMPERATURE_FROM'],['id'=>'deal_req_temp_from','class'=>'form-control']);?>
					</p>
				</div>
				<div class="col-xs-4">
					<p>
						<label for="deal_req_temp_to" class="form-label">Темп. от</label>
						<?php echo Html::input("number","DEAL[REQUIRES_TEMPERATURE_TO]",$deal['REQUIRES_TEMPERATURE_TO'],['id'=>'deal_req_temp_to','class'=>'form-control']);?>
					</p>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-4">
					<p>
						<label for="deal_req_supports" class="form-label">Требуется сопровождение?</label>
						<?php echo Html::checkbox("DEAL[SUPPORT_REQUIRED]",$deal['SUPPORT_REQUIRED'],['id'=>'deal_req_supports']);?>
					</p>
				</div>
				<div class="col-xs-4">
					<div class="parent_checkbox">
						<p>
							<label for="deal_additional_equipment" class="form-label">Требуются дополнительные оборудования?</label>
							<?php echo Html::checkbox("DEAL[ADDITIONAL_EQUIPMENT]",$deal['ADDITIONAL_EQUIPMENT'],['id'=>'deal_additional_equipment']);?>
						</p>
					</div>
					<div class="child_checkboxes">
						<p>
							<label for="deal_additional_equipment_1" class="form-label">Требуется коники?</label>
							<?php echo Html::checkbox("DEAL[ADDITIONAL_EQUIPMENT_CONICS]",$deal['ADDITIONAL_EQUIPMENT_CONICS'],['id'=>'deal_additional_equipment_1']);?>
						</p>
						<p>
							<label for="deal_additional_equipment_2" class="form-label">Требуется аппарели?</label>
							<?php echo Html::checkbox("DEAL[ADDITIONAL_EQUIPMENT_RAMPS]",$deal['ADDITIONAL_EQUIPMENT_RAMPS'],['id'=>'deal_additional_equipment_2']);?>
						</p>
						<p>
							<label for="deal_additional_equipment_3" class="form-label">Требуется гидроборт?</label>
							<?php echo Html::checkbox("DEAL[ADDITIONAL_EQUIPMENT_TAIL_LIFT]",$deal['ADDITIONAL_EQUIPMENT_TAIL_LIFT'],['id'=>'deal_additional_equipment_3']);?>
						</p>
						<p>
							<label for="deal_additional_equipment_4" class="form-label">Требуется манипулятор?</label>
							<?php echo Html::checkbox("DEAL[ADDITIONAL_EQUIPMENT_MANIPULATOR]",$deal['ADDITIONAL_EQUIPMENT_MANIPULATOR'],['id'=>'deal_additional_equipment_4']);?>
						</p>
						<p>
							<label for="deal_additional_equipment_5" class="form-label">Требуется эвакуатор?</label>
							<?php echo Html::checkbox("DEAL[ADDITIONAL_EQUIPMENT_WRECKER]",$deal['ADDITIONAL_EQUIPMENT_WRECKER'],['id'=>'deal_additional_equipment_5']);?>
						</p>
						<p>
							<label for="deal_additional_equipment_6" class="form-label">Требуется кран?</label>
							<?php echo Html::checkbox("DEAL[ADDITIONAL_EQUIPMENT_CRANE]",$deal['ADDITIONAL_EQUIPMENT_CRANE'],['id'=>'deal_additional_equipment_6']);?>
						</p>
					</div>
				</div>
				<div class="col-xs-4">
					<div class="parent_checkbox">
						<p>
							<label for="deal_req_documents" class="form-label">Требуется документы?</label>
							<?php echo Html::checkbox("DEAL[REQUIRED_DOCUMENTS]",$deal['REQUIRED_DOCUMENTS'],['id'=>'deal_req_documents']);?>
						</p>
					</div>
					<div class="child_checkboxes">
						<p>
							<label for="deal_req_documents_1" class="form-label">Требуется доверенность?</label>
							<?php echo Html::checkbox("DEAL[REQUIRED_DOCUMENTS_PROCURATION]",$deal['REQUIRED_DOCUMENTS_PROCURATION'],['id'=>'deal_req_documents_1']);?>
						</p>
						<p>
							<label for="deal_req_documents_2" class="form-label">Требуется медкнижка?</label>
							<?php echo Html::checkbox("DEAL[REQUIRED_DOCUMENTS_MEDICAL_BOOK]",$deal['REQUIRED_DOCUMENTS_MEDICAL_BOOK'],['id'=>'deal_req_documents_2']);?>
						</p>
						<p>
							<label for="deal_req_documents_3" class="form-label">Требуется санобработка?</label>
							<?php echo Html::checkbox("DEAL[REQUIRED_DOCUMENTS_SANITIZATION]",$deal['REQUIRED_DOCUMENTS_SANITIZATION'],['id'=>'deal_req_documents_3']);?>
						</p>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-4">
					<p>
						<?php 
							$with_nds = $with_o_nds = null;
							if(isset($deal['WITH_NDS']) && $deal['WITH_NDS']){
								$with_nds = 1;
							}else{
								$with_o_nds = 1;
							}
						?>
						<label for="deal_with_nds" class="form-label">С НДС</label>
						<?php echo Html::radio("DEAL[WITH_NDS]",$with_nds,['id'=>'deal_with_nds','value'=>1]);?>

						<label for="deal_with_o_nds" class="form-label">Без НДС</label>
						<?php echo Html::radio("DEAL[WITH_NDS]",$with_o_nds,['id'=>'deal_with_o_nds','value'=>0]);?>
					</p>
				</div>
			</div>


			<div class="row">
				<div class="col-xs-12">
					<h4>Маршрут</h4>
					<a href="<?php echo $component->getUrl("getrowroute")?>" id="btn_getRowRoute" class='btn btn-success'>Добаить</a>
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
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
								if(count($routes)){
									foreach ($routes as $key => $r) {
										$arResult['number'] = $key;
										$arResult['route'] = $r;
										$this->getComponent()->includeComponentTemplate("deals/rowRoute");
									}
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
			
			<div class="row">
				<div class="col-xs-6">
					<?php if($deal && isset($deal['ID']) && $deal['ID']){?>
						<input type="hidden" name="DEAL[ID]" value="<?php echo $deal['ID']?>">
					<?php } ?>
					<input type="submit" value="Добавить" class="btn btn-primary">
				</div>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
	$("#btn_getRowRoute").click(function(event){
		event.preventDefault();

		var count = parseInt($("#formRoutesTable tbody tr").length);
		var number = 0;
		if(count){
			number = parseInt($("#formRoutesTable tbody tr").eq(-1).data("number")) + 1;
		}
		var url = $(this).attr("href");

		$.ajax({
			url:url,
			type:"GET",
			data:{
				number:number
			},
			dataType:"html",
			beforeSend:function(){
			},
			success:function(html){
				$("#formRoutesTable tbody").append(html);
			},
			error:function(msg){
				console.log(msg);
			},
			complete:function(){

			}
		})
	});



	$("body").on("click",".rmRouteForm",function(){
		$(this).parents("tr.form-route").remove();
	});

	$("body").on("click",".rmRoute",function(event){
		event.preventDefault();

		var url = $(this).attr("href");
		var route = $(this).parents("tr.form-route");
		$.ajax({
			url:url,
			type:"POST",
			dataType:"json",
			beforeSend:function(){
			},
			success:function(json){
				if(json.hasOwnProperty("success") && json.success && route.length){
					route.remove();
				}
			},
			error:function(msg){
				console.log(msg);
			},
			complete:function(){

			}
		})
	});

	
</script>