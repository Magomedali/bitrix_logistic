<?php require("top.php"); ?>

<?php

use \Bitrix\Main\Application;
use Ali\Logistic\soap\clients\Contractors1C;

$context = Application::getInstance()->getContext();
$request = $context->getRequest();

$topic = null;
if($request->isPost() && isset($request['startload'])){
	

	$contractors = Contractors1C::loadContractors();

	if($contractors){
		
	}else{
		$errors = "Cервису 1С не отвечает";
	}
}

$title = "Импорт контрагентов из 1С";
$APPLICATION->SetTitle($title);
?>

<?php
	if($errors){
		?>
		<p><?php echo $errors;?></p>
		<?php
	}
?>
<div class="row">
	<div class="col-xs-12">
		<form action="" method="POST">
			<p>
				<input type="submit" name="startload" value="Начать загрузку">
			</p>
		</form>
	</div>
</div>


<? require("bottom.php"); ?>