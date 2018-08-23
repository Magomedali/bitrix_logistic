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
			echo Html::dropDownList("ROUTES[{$number}][KIND]",$route ? $route['KIND'] : null,$routeKinds,['class'=>'form-control']);
		?>
	</td>
	<td>
		<?php
			echo Html::input("date","ROUTES[{$number}][START_AT]",$route ? date("Y-m-d",strtotime($route['START_AT'])) : null,['class'=>'form-control']);
		?>
	</td>
	<td>
		<?php
			echo Html::input("date","ROUTES[{$number}][FINISH_AT]",$route ? date("Y-m-d",strtotime($route['FINISH_AT'])) : null,['class'=>'form-control']);
		?>
	</td>
	<td>
		<?php
			echo Html::input("text","ROUTES[{$number}][ORGANISATION]",$route ? $route['ORGANISATION'] : null,['class'=>'form-control']);
		?>
	</td>
	<td>
		<?php
			echo Html::input("text","ROUTES[{$number}][ADDRESS]",$route ? $route['ADDRESS'] : null,['class'=>'form-control']);
		?>
	</td>
	<td>
		<?php
			echo Html::input("text","ROUTES[{$number}][PERSON]",$route ? $route['PERSON'] : null,['class'=>'form-control']);
		?>
	</td>
	<td>
		<?php
			echo Html::input("text","ROUTES[{$number}][PHONE]",$route ? $route['PHONE'] : null,['class'=>'form-control']);
		?>
	</td>
	<td>
		<?php
			echo Html::input("text","ROUTES[{$number}][COMMENT]",$route ? $route['COMMENT'] : null,['class'=>'form-control']);
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