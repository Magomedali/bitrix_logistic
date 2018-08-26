<?php require("top.php"); ?>

<?php

use \Bitrix\Main\Application;
use Ali\Logistic\Contractors;

$context = Application::getInstance()->getContext();
$request = $context->getRequest();

$topic = null;

if($request->isPost()  && isset($request['startload'])){
	$id = (int)$request['Topic']['ID'];
	
	if(isset($request['accepted_delete']) && (int)$request['accepted_delete'] && (int)$id){
		TopicTable::deleteWithMessages((int)$id);
	}

	LocalRedirect("/bitrix/admin/ali.logisitc_main.php");
}

if($request && isset($request['id']) && (int)$request['id']){
	$topic = TopicTable::getRowById((int)$request['id']);
	//Проверить можно ли редактировать пользователю данную тему
}

if(!$topic){
	LocalRedirect("/bitrix/admin/ali.logisitc_main.php");
}

$title = "Настройка свободных контрагентов";

$APPLICATION->SetTitle($title);

?>


<form action="" method="POST">
	<p>
		<input type="submit" name="startload" value="Начать загрузку">
	</p>
</form>
<? require("bottom.php"); ?>