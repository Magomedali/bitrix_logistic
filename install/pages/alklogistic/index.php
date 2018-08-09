<?php
define("NEED_AUTH", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");


$APPLICATION->SetTitle("Logisitc");

?>
<?php

if($USER->IsAuthorized()) {
    

	$APPLICATION->IncludeComponent(
	"alilogistic:main.window",
	"",
	Array()
	);

}else{
	require("login.php");
}
?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>