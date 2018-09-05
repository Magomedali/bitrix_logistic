<?php

use Ali\Logistic\Helpers\Html;

$title = "Присоединение к другой учетной записи!";
$breads[]=['title'=>$title,'link'=>null,'active'=>1];
$arResult['breadcrumbs']=$breads;

$errors = is_array($arResult['errors']) && count($arResult['errors']) ? $arResult['errors'] : null;
?>

<?php $this->getComponent()->includeComponentTemplate("helpers/breadcrumbs"); ?>

<?php if($errors){ ?>
	<div class="row">
		<div class="col-xs-6">
			<?php foreach ($errors as $key => $e) { ?>
				<div class="alert alert-warning">
					<div>
						<?php echo $e;?>
					</div>
				</div>
			<?php }?>
					
		</div>
	</div>
<?php } ?>

<div class="div">
	<div class="col-xs-12">
		<form action="" method="POST">
			<div class="row">
				<div class="col-xs-3">
					<label>Email</label>
					<?php echo Html::input("text",'email',null,['class'=>'form-control']);?>
				</div>
				<div class="col-xs-3">
					<label>Пароль</label>
					<?php echo Html::input("password",'password',null,['class'=>'form-control']);?>
				</div>
			</div>
			<div class="row" style="margin-top: 20px;">
				<div class="col-xs-3">
					<?php echo Html::submitButton("Присоединиться",['class'=>'btn btn-primary']);?>
				</div>
			</div>
		</form>
	</div>
</div>