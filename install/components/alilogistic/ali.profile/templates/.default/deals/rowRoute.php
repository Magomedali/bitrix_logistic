<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Ali\Logistic\helpers\Html;
use Ali\Logistic\helpers\ArrayHelper;
use Ali\Logistic\Dictionary\RoutesKind;

$routeKinds = RoutesKind::getLabels();


$replicate = isset($arResult['replicate']) && boolval($arResult['replicate']) ? true : false;
$number = isset($arResult['number']) && (int)$arResult['number'] ? (int)$arResult['number'] : 0;
$route = isset($arResult['route']) && $arResult['route'] ? $arResult['route'] : null;


?>

<div class="panel panel-default  form-route form-route_between" data-number="<?php echo $number; ?>">
	<div class="panel-heading">
		<div class="row">
			<div class="col-xs-10">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion_form-route_<?php echo $number; ?>" href="#collapse_form-route_<?php echo $number; ?>">Промежуточная точка</a>
				</h4>
			</div>
			<div class="col-xs-2" style="text-align: right;">
				<a class="form-route-select">Выбрать</a>&nbsp&nbsp
				<?php
					if(!$replicate && isset($route['ID'])){
						echo Html::hiddenInput("ROUTES[{$number}][ID]",$route['ID']);
						echo Html::a("<i class='glyphicon glyphicon-remove'></i>",$component->getActionUrl("rmroute",['id'=>$route['ID']]),['class'=>'rmRoute']);
					}else{
						echo Html::a("<i class='glyphicon glyphicon-remove'></i>",null,['class'=>'rmRouteForm']);
					}
				?>
			</div>
		</div>
	</div>
	<div class="collapse in" id="collapse_form-route_<?php echo $number; ?>">
		<div class="panel-body">
			<div class="col-xs-12">
				<div class="row">
					<div class="col-xs-2">
						<label>Тип:</label>
						<?php
							echo Html::dropDownList("ROUTES[{$number}][KIND]",$route && isset($route['KIND']) ? $route['KIND'] : null,$routeKinds,['class'=>'form-control']);
						?>
					</div>
					<div class="col-xs-5">
						<div class="geocoder geocoder-town">
							<p>
								<label>Населенный пункт:</label>
								<?php
									echo Html::input("text","ROUTES[{$number}][TOWN]",$route && isset($route['TOWN']) ? $route['TOWN'] : null,['class'=>'form-control town','autocomplete'=>"off"]);
								?>
							</p>
						</div>
						<p>
							<label>Дата с:</label>
							<?php
								echo Html::input("datetime-local","ROUTES[{$number}][START_AT]",$route && isset($route['START_AT']) ? date("Y-m-d\TH:i",strtotime($route['START_AT'])) : date("Y-m-d\TH:i",time()),['class'=>'form-control startdate','max'=>"9999-12-31T23:59"]);
							?>
							<span class="dt_error"></span>
						</p>
						<div class="autocomplete autocomplete-org">
							<p>
								<label>Организация:</label>
								<?php
									echo Html::input("text","ROUTES[{$number}][ORGANISATION]",$route && isset($route['ORGANISATION']) ? $route['ORGANISATION'] : null,['class'=>'form-control org','autocomplete'=>'off']);
								?>
							</p>
						</div>
						<div class="autocomplete autocomplete-person">
							<p>
								<label>Контактное лицо:</label>
								<?php
									echo Html::input("text","ROUTES[{$number}][PERSON]",$route && isset($route['PERSON']) ? $route['PERSON'] : null,['class'=>'form-control person','autocomplete'=>'off']);
								?>
							</p>
						</div>
					</div>
					<div class="col-xs-5">
						<div class="geocoder geocoder-address">
							<p>
								<label>Точный адрес:</label>
								<?php
									echo Html::input("text","ROUTES[{$number}][ADDRESS]",$route && isset($route['ADDRESS']) ? $route['ADDRESS'] : null,['class'=>'form-control address','autocomplete'=>"off"]);
								?>
							</p>
						</div>
						<p>
							<label>Дата по:</label>
							<?php
								echo Html::input("datetime-local","ROUTES[{$number}][FINISH_AT]",$route && isset($route['FINISH_AT']) ? date("Y-m-d\TH:i",strtotime($route['FINISH_AT'])) : date("Y-m-d\TH:i",time()),['class'=>'form-control finishdate','max'=>"9999-12-31T23:59"]);
							?>
							<span class="dt_error"></span>
						</p>
						<p>
							<label>Телефон:</label>
							<?php
								echo Html::input("text","ROUTES[{$number}][PHONE]",$route && isset($route['PHONE']) ? $route['PHONE'] : null,['class'=>'form-control phone']);
							?>
						</p>
						<p>
							<label>Комментарии:</label>
							<?php
								echo Html::input("text","ROUTES[{$number}][COMMENT]",$route && isset($route['COMMENT']) ? $route['COMMENT'] : null,['class'=>'form-control comment']);
							?>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>