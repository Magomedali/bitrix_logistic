<?php
define("NEED_AUTH", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>

<div class="row">
	<div class="log_pan">
		<h4>
			Вы не авторизованы. <a href="/auth">Вход</a>
		</h4>
	</div>
</div>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>