<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Ali\Logistic\Deals;
use Ali\Logistic\Dictionary\LoadingMethod;
use Ali\Logistic\Dictionary\TypeOfVehicle;
use Ali\Logistic\Dictionary\WayOfTransportation;
use Ali\Logistic\Dictionary\AdditionalEquipment;
use Ali\Logistic\Dictionary\Documents;
use Ali\Logistic\Dictionary\HowPacked;
use Ali\Logistic\Dictionary\SpecialEquipment;
use Ali\Logistic\helpers\Html;
use Ali\Logistic\helpers\ArrayHelper;

$LoadingMethod = LoadingMethod::getLabels();
$TypeOfVehicle = TypeOfVehicle::getLabels();
$WayOfTransportation = WayOfTransportation::getLabels();
$AdditionalEquipment = AdditionalEquipment::getLabels();
$Documents = Documents::getLabels();

$replicate = isset($arResult['replicate']) && boolval($arResult['replicate']) ? true : false;
$errors = is_array($arResult['errors']) && count($arResult['errors']) ? $arResult['errors'] : null;
$deal = is_array($arResult['deal']) && count($arResult['deal']) ? $arResult['deal'] : null;
$contractors = is_array($arResult['contractors']) && count($arResult['contractors']) ? $arResult['contractors'] : array();
$routes = is_array($arResult['routes']) && count($arResult['routes']) ? $arResult['routes'] : array();


$title = $deal 
			? $replicate ? "Копирование заявки '".$deal['NAME']."'" : $deal['NAME']
			: 'Новая Заявка';


$arResult['breadcrumbs'][]=[
		'title'=>$title,
		'link'=>null,
		'active'=>true
];

?>

<?php $this->getComponent()->includeComponentTemplate("helpers/breadcrumbs"); ?>
<div id="alilogistic" class="row form-deal-page">
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
		<form action="" method="POST" id="formDeal" enctype="multipart/form-data">
			<div class="row">
				<div class="col-xs-6">
					<?php if(!$replicate && $deal && isset($deal['ID']) && $deal['ID']){?>
						<input type="hidden" name="DEAL[ID]" value="<?php echo $deal['ID']?>">
						<input type="submit" value="Сохранить" class="btn btn-primary">
						<?php if(boolval($deal['IS_DRAFT'])){?>
							<input type="submit" value="Сохранить как черновик" name="how_draft" class="btn btn-default">
						<?php } ?>
					<?php }else{?>
						<input type="submit" value="Отправить заявку" class="btn btn-primary">
						<input type="submit" value="Сохранить как черновик" name="how_draft" class="btn btn-default">
					<?php } ?>
				</div>
			</div>

			<!-- Начало формы -->
			<div class="row">
				<div class="col-md-12">
					<ul class="nav nav-tabs">
					  <li class="active"><a data-toggle="tab" href="#routes">Маршрут</a></li>
					  <li><a data-toggle="tab" href="#cargo">Груз</a></li>
					  <li><a data-toggle="tab" href="#ts">Транспорт</a></li>
					  <li><a data-toggle="tab" href="#additional">Дополнительные услуги</a></li>
					</ul>

					<div class="tab-content">
						






						<!-- Routes tab -->
						<div id="routes" class="tab-pane fade in active">
							<div class="row">
								<div class="col-xs-12">
									<div class="row">
										<div class="col-xs-3">
											<h2>Маршрут</h2>
										</div>
										<div class="col-xs-8" style="padding-top: 33px;">
											<a href="<?php echo $component->getActionUrl("getrowroute")?>" id="btn_getRowRoute" class='btn btn-primary' >Добавить погрузку/разгрузку</a>
										</div>
										<div class="col-xs-1" style="padding-top: 33px; text-align: right;">
											<a href="#" id="btn_routeToUp"><i class="glyphicon glyphicon-chevron-up"></i></a><br>
											<a href="#" id="btn_routeToDown"><i class="glyphicon glyphicon-chevron-down"></i></a>
										</div>
									</div>
									
									
									<div class="row routes" id="formRoutesBlock">
										<div class="col-md-12" id="routesItems">
											<?php
												if(count($routes)){
													$start = array_shift($routes);
													$end = array_pop($routes);
													$arResult['route'] = $start;
													
													$this->getComponent()->includeComponentTemplate("deals/rowRouteLoading");
													foreach ($routes as $key => $r) {
														$arResult['number'] = ++$key;
														$arResult['route'] = $r;
														$this->getComponent()->includeComponentTemplate("deals/rowRoute");
													}
													$arResult['route'] = $end;
													$this->getComponent()->includeComponentTemplate("deals/rowRouteUnLoading");
												}else{
													$this->getComponent()->includeComponentTemplate("deals/rowRouteLoading");
													$this->getComponent()->includeComponentTemplate("deals/rowRouteUnLoading");
												}
											?>
										</div>
									</div>	
								</div>
							</div>
						</div>
						







						<!-- Cargo tab -->
						<div id="cargo" class="tab-pane fade in">
							<h2>Груз</h2>
							<div class="row">
								<div class="col-xs-6">
									<p>
										<label for="deal_name" class="form-label">Наименование груза</label>
										<input type="text" name="DEAL[NAME]" id="deal_name" value="<?php echo $deal ? $deal['NAME'] : null;?>" class="form-control">
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
								<div class="col-xs-3">
									<p>
										<label for="deal_weight" class="form-label">Вес груза</label>
										<input type="number" name="DEAL[WEIGHT]" id="deal_weight" value="<?php echo $deal ? $deal['WEIGHT'] : null;?>" class="form-control" step='any'>
									</p>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-3">
									<p>
										<label for="deal_width" class="form-label">Ширина (м)</label>
										<input type="number" name="DEAL[WIDTH]" id="deal_width" value="<?php echo $deal ? $deal['WIDTH'] : null;?>" class="form-control" step='any'>
									</p>
								</div>
								<div class="col-xs-3">
									<p>
										<label for="deal_height" class="form-label">Высота (м)</label>
										<input type="number" name="DEAL[HEIGHT]" id="deal_height" value="<?php echo $deal ? $deal['HEIGHT'] : null;?>" class="form-control" step='any'>
									</p>
								</div>

								<div class="col-xs-3">
									<p>
										<label for="deal_length" class="form-label">Длина (м)</label>
										<input type="number" name="DEAL[LENGTH]" id="deal_length" value="<?php echo $deal ? $deal['LENGTH'] : null;?>" class="form-control" step='any'>
									</p>
								</div>
								<div class="col-xs-3">
									<p>
										<label for="deal_space" class="form-label">Объем (куб. м.)</label>
										<input type="number" name="DEAL[SPACE]" id="deal_space" value="<?php echo $deal ? $deal['SPACE'] : null;?>" class="form-control" step='any'>
									</p>
								</div>
							</div>

							<div class="row">
								<div class="col-xs-4">
									<p>
										<label>Количество мест</label>
										<?php echo Html::input("number","DEAL[COUNT_PLACE]",$deal['COUNT_PLACE'],['class'=>'form-control']);?>
									</p>
								</div>
								<div class="col-xs-8">
									<p>
										<label>Как упакован</label>
										<?php echo Html::radioList("DEAL[HOW_PACKED]",$deal['HOW_PACKED'],HowPacked::getLabels(),['id'=>'deal_HowPacked']);?>
									</p>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-4">
									<p>
										<label>Комментарии</label>
										<?php echo Html::textarea("DEAL[COMMENTS]",$deal['COMMENTS'],['class'=>'form-control']);?>
									</p>
								</div>
								<div class="col-xs-5">
									<p>
										<label>Файл:</label>
										<?php echo Html::fileInput("PRINT_FORM");?>
									</p>
									<?php 
										$path = Deals::getPublicPathDealFiles();

										if($deal && isset($deal['ID']) && isset($deal['PRINT_FORM']) && $deal['PRINT_FORM'] && file_exists(ALI_DEAL_FILES.$deal['PRINT_FORM'])){
									?>
									<p>
										<?php
											echo Html::a("Прикрепленный файл",$component->getActionUrl('getDealFile',['id'=>$deal['ID']]),['target'=>'_blank']);
										?>
									</p>
									<?php } ?>
								</div>
								
							</div>
						</div>















						<!-- Ts tab -->
						<div id="ts" class="tab-pane fade in">
							<h2>Транспорт</h2>
							<div class="row">
								<div class="col-xs-3">
									<p>
										<label for="deal_type_od_vehicle" class="form-label">Tип транспортного средства</label>
										<?php 
											echo Html::checkboxList("DEAL[TYPE_OF_VEHICLE]",TypeOfVehicle::toArrayCode($deal['TYPE_OF_VEHICLE']),$TypeOfVehicle,['id'=>"deal_type_od_vehicle"]);
										?>
									</p>
								</div>

								<div class="col-xs-3">
									<p>
										<label for="deal_loading_method" class="form-label">Способ погрузки</label>
										<?php 
											echo Html::checkboxList("DEAL[LOADING_METHOD]",LoadingMethod::toArrayCode($deal['LOADING_METHOD']),$LoadingMethod,['id'=>"deal_loading_method"]);
										?>
									</p>
								</div>

								<div class="col-xs-3">
									<p>
										<label for="deal_unloading_method" class="form-label">Способ разгрузки</label>
										<?php 
											echo Html::checkboxList("DEAL[UNLOADING_METHOD]",LoadingMethod::toArrayCode($deal['UNLOADING_METHOD']),$LoadingMethod,['id'=>"deal_unloading_method"]);
										?>
									</p>
								</div>

								<div class="col-xs-3">
									<p>
										<label for="deal_way_of_transportation" class="form-label">Способ перевозки</label>
										<?php 
											echo Html::radioList("DEAL[WAY_OF_TRANSPORTATION]",$deal['WAY_OF_TRANSPORTATION'],$WayOfTransportation,['id'=>"deal_way_of_transportation"]);
										?>
									</p>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<div class="panel panel-primary">
										<div class="panel-heading">
									      <h4 class="panel-title">
									        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Дополнительные требования</a>
									      </h4>
									    </div>
									    <div class="collapse" id="collapseOne">
										    <div class="panel-body">
										    	<div class="row">
													<div class="col-xs-2">
														<p>
															<label for="deal_req_temp_from" class="form-label">Темп. от</label>
															<?php echo Html::input("number","DEAL[REQUIRES_TEMPERATURE_FROM]",$deal['REQUIRES_TEMPERATURE_FROM'] ? $deal['REQUIRES_TEMPERATURE_FROM'] : "",['id'=>'deal_req_temp_from','class'=>'form-control','step'=>'any']);?>
														</p>
													</div>
													<div class="col-xs-2">
														<p>
															<label for="deal_req_temp_to" class="form-label">Темп. до</label>
															<?php echo Html::input("number","DEAL[REQUIRES_TEMPERATURE_TO]",$deal['REQUIRES_TEMPERATURE_TO'] ? $deal['REQUIRES_TEMPERATURE_TO'] : "",['id'=>'deal_req_temp_to','class'=>'form-control','step'=>'any']);?>
														</p>
													</div>
													<div class="col-xs-4">
														<p>
															<label for="deal_adrclass" class="form-label">АДР Класс</label>
															<?php echo Html::input("number","DEAL[ADR_CLASS]",$deal['ADR_CLASS'] ? $deal['ADR_CLASS'] : "",['min'=>1,'max'=>10,'id'=>'deal_adrclass','class'=>'form-control']);?>
														</p>
													</div>
													
												</div>
												<div class="row">
													<div class="col-xs-5">
														<p>
															<label for="deal_additional_equipment" class="form-label">Требуется дополнительное оборудование</label>
															<?php echo Html::checkboxList("DEAL[ADDITIONAL_EQUIPMENT]",AdditionalEquipment::toArrayCode($deal['ADDITIONAL_EQUIPMENT']),$AdditionalEquipment,['id'=>'deal_additional_equipment']);?>
														</p>
														
													</div>
													<div class="col-xs-3">
														<p>
															<label for="deal_req_documents" class="form-label">Требуются документы</label>
															<?php echo Html::checkboxList("DEAL[REQUIRED_DOCUMENTS]",Documents::toArrayCode($deal['REQUIRED_DOCUMENTS']),$Documents,['id'=>'deal_req_documents']);?>
														</p>
													</div>
													<div class="col-xs-4">
														<p>
															<label for="deal_reqrussiandriver" class="form-label">Водитель гражданин России</label>
															<?php echo Html::checkbox("DEAL[REQUIRED_RUSSIAN_DRIVER]",$deal['REQUIRED_RUSSIAN_DRIVER'],['id'=>'deal_reqrussiandriver']);?>
														</p>
													</div>
												</div>
										    </div>
									    </div>
									</div>
								</div>
							</div>
						</div>








						<!-- Additional tab -->
						<div id="additional" class="tab-pane fade in">
							<h2>Дополнительные услуги</h2>
							<div class="row">
								<div class="col-xs-4">
									<p>
										<?php echo Html::checkbox("DEAL[CARGO_HANDLING]",$deal['CARGO_HANDLING'],['id'=>'deal_requires_loader','value'=>1]);?>
										<label for="deal_requires_loader" class="form-label">Требуется грузчик</label>
									</p>
								</div>
								<div class="col-xs-3">
									<p>
										<label for="deal_count_loader" class="form-label">Количество грузчиков</label>
										<?php echo Html::input("number","DEAL[COUNT_LOADERS]",$deal['COUNT_LOADERS'],['id'=>'deal_count_loader','class'=>'form-control']);?>
									</p>
								</div>
								<div class="col-xs-3">
									<p>
										<label for="deal_count_hours" class="form-label">Количество часов</label>
										<?php echo Html::input("number","DEAL[COUNT_HOURS]",$deal['COUNT_HOURS'],['id'=>'deal_count_hours','class'=>'form-control']);?>
									</p>
								</div>
							</div>

							<div class="row">
								<div class="col-xs-4">
									<p>
										<?php echo Html::checkbox("DEAL[REQUIRES_INSURANCE]",$deal['REQUIRES_INSURANCE'],['id'=>'deal_requires_insurance']);?>
										<label for="deal_requires_insurance" class="form-label">Требуется страхование</label>
									</p>
									
								</div>
								<div class="col-xs-2">
									<p>
										<label for="deal_sum" class="form-label">Стоимость</label>
										<?php echo Html::input("number","DEAL[SUM]",$deal['SUM'] ? $deal['SUM'] : "",['id'=>'deal_sum','class'=>'form-control','step'=>'any']);?>
									</p>
								</div>
							</div>

							<div class="row">
								<div class="col-xs-4">
									<p>
										<?php echo Html::checkbox("DEAL[SUPPORT_REQUIRED]",$deal['SUPPORT_REQUIRED'],['id'=>'deal_req_supports']);?>
										<label for="deal_req_supports" class="form-label">Требуется сопровождение</label>
									</p>
									<p>
										<?php echo Html::checkbox("DEAL[SECURE_STORAGE]",$deal['SECURE_STORAGE'],['id'=>'deal_req_sec_storage']);?>
										<label for="deal_req_sec_storage" class="form-label">Ответственное хранение</label>
									</p>
									<p>
										<?php echo Html::checkbox("DEAL[CROSS_DOCKING]",$deal['CROSS_DOCKING'],['id'=>'deal_req_cross_docking']);?>
										<label for="deal_req_cross_docking" class="form-label">Кросс-докинг</label>
									</p>
								</div>
								<div class="col-xs-4">
									<p>
										<label for="deal_spec_equipment" class="form-label">Спецтехника</label>
										<?php echo Html::checkboxList("DEAL[SPECIAL_EQUIPMENT]",SpecialEquipment::toArrayCode($deal['SPECIAL_EQUIPMENT']),SpecialEquipment::getLabels(),['id'=>'deal_spec_equipment']);?>
									</p>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
			
			

			
			
			

			<div class="row">
				<div class="col-md-12">
					<nav aria-label="...">
					  <ul class="pager">
					    <li class="prev_tab"><a>Назад</a></li>
					    <li class="next_tab"><a>Далее</a></li>
					  </ul>
					</nav>
				</div>
			</div>
			<!-- Конец формы -->
		</form>
	</div>
</div>

<script type="text/javascript">

	$("body").on("click",".pager li",function(){

		var active_tab = $(".nav-tabs li.active");

		
		if($(this).hasClass("prev_tab")){
			if(active_tab.length){
				var prev = active_tab.prev();
				prev.length ? prev.find("a").trigger("click") : null;
			}
		}else if($(this).hasClass("next_tab")){
			if(active_tab.length){
				var next = active_tab.next();
				next.length ? next.find("a").trigger("click") : null;
			}
		}
	});




	$("#btn_getRowRoute").click(function(event){
		event.preventDefault();

		var count = parseInt($("#formRoutesBlock div.form-route_between").length);
		var number = 0;

		if(count){
			number = parseInt($("#formRoutesBlock div.form-route_between").eq(-1).data("number")) + 1;
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
				$("#block_route_end").before(html);
			},
			error:function(msg){
				console.log(msg);
			},
			complete:function(){

			}
		})
	});



	$("body").on("click",".rmRouteForm",function(){
		
		var count = parseInt($("#formRoutesBlock div.form-route_between").length);
		

		$(this).parents("div.form-route_between").remove();
	});

	$("body").on("click",".rmRoute",function(event){
		event.preventDefault();

		var count = parseInt($("#formRoutesBlock div.form-route_between").length);
		

		var url = $(this).attr("href");
		var route = $(this).parents("div.form-route_between");
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

	var dateTostandartFormat = function(d){

		var Y=d.getFullYear();
		var m=d.getMonth()+1;
		var day=d.getDate();
		var H=d.getHours();
		var i=d.getMinutes();
		var s=d.getSeconds();
		day = (day < 10) ? day='0'+day : day;
		
		m = (m < 10) ? m='0'+m : m;
		
		var strDate = Y+"-"+m+"-"+day+"T"+H+":"+i;
		
		return strDate; 
	}

	// $("body").on("focusout",'.form-route input.startdate,.form-route input.finishdate',function(){


	// 	var now = Date.now();
	// 	var strDate = $(this).val();
	// 	var inputDate = new Date(strDate);
		

	// 	if($(this).hasClass("startdate")){
	// 		// Дата начала должна быть меньше даты окончания
	// 		var finish = $(this).parents(".form-route").find("input.finishdate").val();
	// 		var finishDate = new Date(finish);
	// 		var startDate = inputDate;
	// 		if(finishDate < startDate){
	// 			var f_d_str = dateTostandartFormat(startDate);
	// 			$(this).parents(".form-route").find("input.finishdate").val(f_d_str);
	// 			return false;
	// 		}
	// 	}

	// 	if($(this).hasClass("finishdate")){
	// 		// Дата окончания должна быть больше даты начала
	// 		var start = $(this).parents(".form-route").find("input.startdate").val();
	// 		var startDate = new Date(start);
	// 		var finishDate = inputDate;
	// 		if(finishDate < startDate){
	// 			console.log(startDate);
	// 			$(this).val(dateTostandartFormat(startDate));
	// 			return false;
	// 		}
	// 	}

	// 	// Подстраховка, если инпуты пустые изначально
	// 	if(now > inputDate){
	// 		//Запрет ввода даты меньше текущей
	// 		$(this).val(dateTostandartFormat(new Date(now)));
	// 		return false;
	// 	}

	// });


	$("body").on("click",".form-route-select",function(event){
		event.preventDefault();
		var selectedClass = "form-route-selected";
		$(".form-route").removeClass(selectedClass);
		var parent = $(this).parents(".form-route");
		parent.addClass(selectedClass);
	});
	
	var routeModel = function(){
		this.dt_from;
		this.dt_to;
		this.town;
		this.address;
		this.person;
		this.org;
		this.phone;
		this.comment;

		this.load = function(form){
			this.dt_from = form.find("input.startdate").val();
			this.dt_to = form.find("input.finishdate").val();

			this.town = form.find("input.town").val();
			this.address = form.find("input.address").val();
			this.person = form.find("input.person").val();
			this.org = form.find("input.org").val();
			this.phone = form.find("input.phone").val();
			this.comment = form.find("input.comment").val();
		}

		this.unLoad = function(to){
			to.find("input.startdate").val(this.dt_from);
			to.find("input.finishdate").val(this.dt_to);

			to.find("input.town").val(this.town);
			to.find("input.address").val(this.address);
			to.find("input.person").val(this.person);
			to.find("input.org").val(this.org);
			to.find("input.phone").val(this.phone );
			to.find("input.comment").val(this.comment);
		}
	};

	var changeData = function(from,to){
		var mFrom = new routeModel();
		mFrom.load(from);
		
		var mTo = new routeModel();
		mTo.load(to);

		mFrom.unLoad(to);
		mTo.unLoad(from);
	}

	$("#btn_routeToUp").click(function(event){
		event.preventDefault();
		var selectedClass = "form-route-selected";
		var selected = $(".form-route."+selectedClass);
		if(!selected.length) return false;
		var prev = selected.prev();
		if(!prev.length) return false;

		changeData(selected,prev);
		prev.find(".form-route-select").trigger("click");
	});


	$("#btn_routeToDown").click(function(event){
		event.preventDefault();
		var selectedClass = "form-route-selected";
		var selected = $(".form-route."+selectedClass);
		if(!selected.length) return false;
		var next = selected.next();
		if(!next.length) return false;

		changeData(selected,next);
		next.find(".form-route-select").trigger("click");
	});

	var is_updated_page = <?php echo $deal && isset($deal['ID']) ? 1 : 0;?> 
	$("#formDeal").submit(function(event){
		
		var errors = [];
		var tabRoutes = $(".nav.nav-tabs a[href='#routes']");
		
		var routes = $(".form-route");
		var t = "Неправильное значение даты и времени!";
		var now = Date.now();
		routes.each(function(){
			var strStart = $(this).find("input.startdate").val();
			var strFinish = $(this).find("input.finishdate").val();
			var startDate = new Date(strStart);
			var finishDate = new Date(strFinish);
			var nextCheck = true;
			if(!parseInt(is_updated_page) && startDate < Date.now()){
				errors.push(t);
				$(this).find("input.startdate").addClass("error");
				$(this).find("input.startdate + span.dt_error").html(t);
				nextCheck = false;
			}else{
				$(this).find("input.startdate").removeClass("error");
				$(this).find("input.startdate + span.dt_error").html("");
			}

			if(nextCheck){
				if(startDate > finishDate){
					errors.push(t);
					$(this).find("input.startdate").addClass("error");
					$(this).find("input.startdate + span.dt_error").html(t);
					$(this).find("input.finishdate").addClass("error");
					$(this).find("input.finishdate + span.dt_error").html(t);
				}else{
					$(this).find("input.startdate").removeClass("error");
					$(this).find("input.startdate + span.dt_error").html("");
					$(this).find("input.finishdate").removeClass("error");
					$(this).find("input.finishdate + span.dt_error").html("");
				}
			}
			
		});
		

		if(errors.length){
			event.preventDefault();
			tabRoutes.trigger("click");
		}

	});


	$("#deal_length,#deal_height,#deal_width").keyup(function(){
		var space = $("#deal_space");

		var l = $("#deal_length").val();
		var w = $("#deal_width").val();
		var h = $("#deal_height").val();
		var space_val = w*l*h;
		if(l > 0 && w > 0 && h > 0){
			space.val(space_val.toFixed(2));
		}
	})

</script>