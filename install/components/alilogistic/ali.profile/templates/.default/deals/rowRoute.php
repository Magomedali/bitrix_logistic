<?php

use Ali\Logistic\helpers\Html;
use Ali\Logistic\helpers\ArrayHelper;
use Ali\Logistic\Dictionary\RoutesKind;

$routeKinds = RoutesKind::getLabels();

$number = isset($arResult['number']) && (int)$arResult['number'] ? (int)$arResult['number'] : 0;
$route = isset($arResult['route']) && $arResult['route'] ? $arResult['route'] : null;


?>

<tr class="form-route" data-number="<?php echo $number; ?>">
	<td style="min-width: 40px;">
		<?php
			echo Html::dropDownList("ROUTES[{$number}][KIND]",$route && isset($route['KIND']) ? $route['KIND'] : null,$routeKinds,['class'=>'form-control']);
		?>
	</td>
	<td>
		<?php
			echo Html::input("datetime-local","ROUTES[{$number}][START_AT]",$route && isset($route['START_AT']) ? date("Y-m-d\TH:i",strtotime($route['START_AT'])) : date("Y-m-d\TH:i",time()),['class'=>'form-control']);
		?>
	</td>
	<td>
		<?php
			echo Html::input("datetime-local","ROUTES[{$number}][FINISH_AT]",$route && isset($route['FINISH_AT']) ? date("Y-m-d\TH:i",strtotime($route['FINISH_AT'])) : date("Y-m-d\TH:i",time()),['class'=>'form-control']);
		?>
	</td>
	<td>
		<?php
			echo Html::input("text","ROUTES[{$number}][ORGANISATION]",$route && isset($route['ORGANISATION']) ? $route['ORGANISATION'] : null,['class'=>'form-control']);
		?>
	</td>
	<td>
		<?php
			echo Html::input("text","ROUTES[{$number}][ADDRESS]",$route && isset($route['ADDRESS']) ? $route['ADDRESS'] : null,['class'=>'form-control']);
		?>
	</td>
	<td>
		<?php
			echo Html::input("text","ROUTES[{$number}][PERSON]",$route && isset($route['PERSON']) ? $route['PERSON'] : null,['class'=>'form-control']);
		?>
	</td>
	<td>
		<?php
			echo Html::input("text","ROUTES[{$number}][PHONE]",$route && isset($route['PHONE']) ? $route['PHONE'] : null,['class'=>'form-control']);
		?>
	</td>
	<td>
		<?php
			echo Html::input("text","ROUTES[{$number}][COMMENT]",$route && isset($route['COMMENT']) ? $route['COMMENT'] : null,['class'=>'form-control']);
		?>
	</td>
	<td>
		<?php
			if(isset($route['ID'])){
				echo Html::hiddenInput("ROUTES[{$number}][ID]",$route['ID']);
				echo Html::a("X",$component->getUrl("rmroute",['id'=>$route['ID']]),['class'=>'btn btn-danger rmRoute']);
			}else{
				echo Html::a("X",null,['class'=>'btn btn-danger rmRouteForm']);
			}
		?>
	</td>
</tr>