<?php

use Ali\Logistic\ContractorsType;

$types = Ali\Logistic\ContractorsType::getLabels();

$errors = is_array($arResult['errors']) && count($arResult['errors']) ? $arResult['errors'] : null;
$org = is_array($arResult['org']) && count($arResult['org']) ? $arResult['org'] : null;
?>

<div class="row form-org-page">
	<div class="row">
		<div class="col-xs-6">
			<?php if($org){?>
				<h3>Организация <?php echo $org['NAME']?></h3>
			<?php }else{ ?>
				<h3>Новая организация</h3>
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
	<div class="form-org col-xs-12">
		<form action="" method="POST">
			<div class="row">
				<div class="col-xs-12">
					<p>
						<label for="org_name" class="form-label">Наименование организации</label>
						<input type="text" name="ORG[NAME]" id="org_name" value="<?php echo $org ? $org['NAME'] : null;?>" class="form-control">
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<p>
						<label for="org_legal_address" class="form-label">Юридический адрес</label>
						<input type="text" name="ORG[LEGAL_ADDRESS]" id="org_legal_address" value="<?php echo $org ? $org['LEGAL_ADDRESS'] : null;?>" class="form-control">
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-6">
					<p>
						<label for="org_physical_address" class="form-label">Физический адрес</label>
						<input type="text" name="ORG[PHYSICAL_ADDRESS]" id="org_physical_address" value="<?php echo $org ? $org['PHYSICAL_ADDRESS'] : null;?>" class="form-control">
					</p>
				</div>
				<div class="col-xs-6">
					<p>
						<input type="checkbox" name="equal_legal_address" id="equal_legal_address">
						<label for="equal_legal_address" class="form-label">Совпадает с юридическим</label>
					</p>
				</div>
			</div>
			
			<div class="row">
				<div class="col-xs-12">
					<?php 
						$checked_1 = $checked_2 = "";
						if(isset($org['ENTITY_TYPE'])){
							$org['ENTITY_TYPE'] == 1 ? $checked_1 = "checked" : $checked_2 = "checked";
						}
					?>
					<p>
						<?php 
							foreach ($types as $v => $t) {
								
								
								$checked = isset($org['ENTITY_TYPE']) && $org['ENTITY_TYPE'] == $v ? "checked" : "";
								
						?>
							<label for="org_type_<?php echo $v;?>" class="form-label"><?php echo $t?></label>
							<input type="radio" name="ORG[ENTITY_TYPE]" id="org_type_<?php echo $v;?>" value="<?php echo $v;?>" <?php echo $checked;?>>
						<?php
							}
						?>
						
					</p>
				</div>
			</div>
			
			<div class="row">
				<div class="col-xs-12">
					<p>
						<label for="org_inn" class="form-label">ИНН</label>
						<input type="number" name="ORG[INN]" id="org_inn" value="<?php echo $org ? $org['INN'] : null;?>" class="form-control">
					</p>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12">
					<p>
						<label for="org_kpp" class="form-label">КПП</label>
						<input type="number" name="ORG[KPP]" id="org_kpp" value="<?php echo $org ? $org['KPP'] : null;?>" class="form-control">
					</p>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12">
					<p>
						<label for="org_ogrn" class="form-label">ОГРН</label>
						<input type="number" name="ORG[OGRN]" id="org_ogrn" value="<?php echo $org ? $org['OGRN'] : null;?>" class="form-control">
					</p>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12">
					<p>
						<label for="bank_bik" class="form-label">Бик Банка</label>
						<input type="number" name="ORG[BANK_BIK]" id="bank_bik" value="<?php echo $org ? $org['BANK_BIK'] : null;?>" class="form-control">
					</p>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12">
					<p>
						<label for="bank_name" class="form-label">Наименование Банка</label>
						<input type="text" name="ORG[BANK_NAME]" id="bank_name" value="<?php echo $org ? $org['BANK_NAME'] : null;?>" class="form-control">
					</p>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12">
					<p>
						<label for="checking_account" class="form-label">Расчетный счет</label>
						<input type="text" name="ORG[CHECKING_ACCOUNT]" id="checking_account" value="<?php echo $org ? $org['CHECKING_ACCOUNT'] : null;?>" class="form-control">
					</p>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12">
					<p>
						<label for="correspondent_account" class="form-label">Корреспондентский счет</label>
						<input type="text" name="ORG[CORRESPONDENT_ACCOUNT]" id="correspondent_account" value="<?php echo $org ? $org['CORRESPONDENT_ACCOUNT'] : null;?>" class="form-control">
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-6">
					<?php if($org && isset($org['ID']) && $org['ID']){?>
						<input type="hidden" name="ORG[ID]" value="<?php echo $org['ID']?>">
					<?php } ?>
					<input type="submit" value="Добавить" class="btn btn-primary">
				</div>
			</div>
		</form>
	</div>
</div>