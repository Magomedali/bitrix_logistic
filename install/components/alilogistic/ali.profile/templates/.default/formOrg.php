<?php

?>

<div class="row form-org-page">
	<div class="row">
		<div class="col-xs-6">
			<h3>Новая организация</h3>
		</div>
	</div>
	<div class="form-org col-xs-12">
		<form action="" method="POST">
			<div class="row">
				<div class="col-xs-12">
					<p>
						<label for="org_name" class="form-label">Наименование организации</label>
						<input type="text" name="ORG[NAME]" id="org_name" class="form-control">
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<p>
						<label for="org_legal_address" class="form-label">Юридический адрес</label>
						<input type="text" name="ORG[LEGAL_ADDRESS]" id="org_legal_address" class="form-control">
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-6">
					<p>
						<label for="org_physical_address" class="form-label">Физический адрес</label>
						<input type="text" name="ORG[PHYSICAL_ADDRESS]" id="org_physical_address" class="form-control">
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
					<p>
						<label for="org_type_1" class="form-label">Юридическое лицо</label>
						<input type="radio" name="ORG[ENTITY_TYPE]" id="org_type_1" value="1">
						<label for="org_type_2" class="form-label">ИП</label>
						<input type="radio" name="ORG[ENTITY_TYPE]" id="org_type_2" value="2">
					</p>
				</div>
			</div>
			
			<div class="row">
				<div class="col-xs-12">
					<p>
						<label for="org_inn" class="form-label">ИНН</label>
						<input type="text" name="ORG[INN]" id="org_inn" class="form-control">
					</p>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12">
					<p>
						<label for="org_kpp" class="form-label">КПП</label>
						<input type="text" name="ORG[KPP]" id="org_kpp" class="form-control">
					</p>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12">
					<p>
						<label for="org_ogrn" class="form-label">ОГРН</label>
						<input type="text" name="ORG[OGRN]" id="org_ogrn" class="form-control">
					</p>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12">
					<p>
						<label for="bank_bik" class="form-label">Бик Банка</label>
						<input type="text" name="ORG[BANK_BIK]" id="bank_bik" class="form-control">
					</p>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12">
					<p>
						<label for="bank_name" class="form-label">Наименование Банка</label>
						<input type="text" name="ORG[BANK_NAME]" id="bank_name" class="form-control">
					</p>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12">
					<p>
						<label for="checking_account" class="form-label">Расчетный счет</label>
						<input type="text" name="ORG[CHECKING_ACCOUNT]" id="checking_account" class="form-control">
					</p>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12">
					<p>
						<label for="correspondent_account" class="form-label">Корреспондентский счет</label>
						<input type="text" name="ORG[CORRESPONDENT_ACCOUNT]" id="correspondent_account" class="form-control">
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-6">
					<input type="submit" value="Добавить" class="btn btn-primary">
				</div>
			</div>
		</form>
	</div>
</div>