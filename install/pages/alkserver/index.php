<?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

ob_end_clean();
ob_start();

header("Content-Type: text/xml; charset=utf-8");


use Ali\Logistic\soap\Server\LogisticServer;
use Ali\Logistic\soap\Server\ServerHandler;


if(CModule::IncludeModule('ali.logistic')){

	ini_set("soap.wsdl_cache_enabled", "1");
	try {
		//Создаем новый SOAP-сервер
		$server = LogisticServer::init();
		
		//Регистрируем класс обработчик
		$server->setClass("\Ali\Logistic\Soap\Server\ServerHandler");
		
		//Запускаем сервер
		$server->handle();
	} catch (\Exception $e) {
		
	}
	
}

?>