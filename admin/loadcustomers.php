<?php require("top.php"); ?>

<?php

set_time_limit(0);
ini_set('session.gc_maxlifetime', 2400);


use \Bitrix\Main\Application;
use Ali\Logistic\soap\clients\Contractors1C;

$context = Application::getInstance()->getContext();
$request = $context->getRequest();

$topic = null;
$log=null;
if($request->isPost() && isset($request['startload'])){
	

	$log = Contractors1C::loadContractors();

	if(!is_array($log)){
		$errors = "Cервис 1С не отвечает";
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

<div class="row">
	<div class="col-xs-12">
		<?php 
		if(is_array($log) && isset($log['error_log']) && is_array($log['error_log'])){
			foreach ($log['error_log'] as $key => $l) {
				?>
					<p style="color: #f00;">Ошибка при сохранении! - <?php echo $l; ?></p>
				<?php
			}
		}
		?>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<?php 
		if(is_array($log) && isset($log['success_log']) && is_array($log['success_log'])){
			foreach ($log['success_log'] as $key => $l) {
				?>
					<p style="color: #0f0;">Сохранено! - <?php echo $l; ?></p>
				<?php
			}
		}
		?>
	</div>
</div>

<? require("bottom.php"); ?>