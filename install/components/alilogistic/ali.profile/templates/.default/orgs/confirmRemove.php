<?php

$org = is_array($arResult['org']) && count($arResult['org']) ? $arResult['org'] : null;

?>

<div class="row">
	<div class="col-xs-12">
		<?php if($org && $org['ID']){?>
			<form method="POST">
				<div class="alert alert-danger">
					Внимание! При удалении организации, вы потеряете данные о заявках текущей организации.
				</div>
				<input type="hidden" name="id" value="<?php echo $org['ID']?>">
				<input type="hidden" name="confirm" value="1">
				<button type="submit" class="btn btn-primary">Подтвердить удаление</button>
			</form>
		<?php } ?>
	</div>
</div>