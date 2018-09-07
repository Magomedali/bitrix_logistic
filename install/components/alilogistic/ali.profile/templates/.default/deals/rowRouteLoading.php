<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Ali\Logistic\helpers\Html;
use Ali\Logistic\helpers\ArrayHelper;
use Ali\Logistic\Dictionary\RoutesKind;

$routeKinds = RoutesKind::getLabels();


$replicate = isset($arResult['replicate']) && boolval($arResult['replicate']) ? true : false;
$route = isset($arResult['route']) && $arResult['route'] ? $arResult['route'] : null;


?>

<div class="panel panel-primary form-route form-route_start" id="block_route_start">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion_form-route_start" href="#collapse_form-route_start">Погрузка</a>
		</h4>
	</div>
	<div class="collapse in" id="collapse_form-route_start">
		<div class="panel-body">
			<div class="col-xs-12">
				<div class="row">
					<div class="col-xs-2">
						<div class="col-xs-3">
							<?php
								if(!$replicate && isset($route['ID'])){
									echo Html::hiddenInput("ROUTES_START[ID]",$route['ID']);
								}
								echo Html::hiddenInput("ROUTES_START[KIND]",RoutesKind::LOADING);
							?>
						</div>
					</div>
					<div class="col-xs-5">
						<p>
							<label>Населенный пункт:</label>
							<?php
								echo Html::input("text","ROUTES_START[TOWN]",$route && isset($route['TOWN']) ? $route['TOWN'] : null,['class'=>'form-control']);
							?>
						</p>
						<p>
							<label>Дата с:</label>
							<?php
								echo Html::input("datetime-local","ROUTES_START[START_AT]",$route && isset($route['START_AT']) ? date("Y-m-d\TH:i",strtotime($route['START_AT'])) : date("Y-m-d\TH:i",time()),['class'=>'form-control startdate']);
							?>
						</p>
						<p>
							<label>Получатель/Отправитель:</label>
							<?php
								echo Html::input("text","ROUTES_START[ORGANISATION]",$route && isset($route['ORGANISATION']) ? $route['ORGANISATION'] : null,['class'=>'form-control']);
							?>
						</p>
						<p>
							<label>Контактное лицо:</label>
							<?php
								echo Html::input("text","ROUTES_START[PERSON]",$route && isset($route['PERSON']) ? $route['PERSON'] : null,['class'=>'form-control']);
							?>
						</p>
					</div>
					<div class="col-xs-5">
						<p>
							<label>Точный адрес:</label>
							<?php
								echo Html::input("text","ROUTES_START[ADDRESS]",$route && isset($route['ADDRESS']) ? $route['ADDRESS'] : null,['class'=>'form-control']);
							?>
						</p>
						<p>
							<label>Дата по:</label>
							<?php
								echo Html::input("datetime-local","ROUTES_START[FINISH_AT]",$route && isset($route['FINISH_AT']) ? date("Y-m-d\TH:i",strtotime($route['FINISH_AT'])) : date("Y-m-d\TH:i",time()),['class'=>'form-control finishdate']);
							?>
						</p>
						<p>
							<label>Телефон:</label>
							<?php
								echo Html::input("text","ROUTES_START[PHONE]",$route && isset($route['PHONE']) ? $route['PHONE'] : null,['class'=>'form-control']);
							?>
						</p>
						<p>
							<label>Комментарии:</label>
							<?php
								echo Html::input("text","ROUTES_START[COMMENT]",$route && isset($route['COMMENT']) ? $route['COMMENT'] : null,['class'=>'form-control']);
							?>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>