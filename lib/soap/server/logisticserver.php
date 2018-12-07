<?php

namespace Ali\Logistic\soap\Server;

use Ali\Logistic\soap\Server\ServerHandler;

class LogisticServer{


	public static function init(){

		$logFile = ALI_LOG_SOAP_SERVER_PATH."log.log";

		$f = fopen($logFile, "a+");
		fwrite($f, "\n Запрос на сервер ".date("H:i:s d.m.Y")." \n");
		try {
			$server = new \SoapServer("https://".$_SERVER['HTTP_HOST']."/alkserver/wsdl.php");

			fclose($f);
			
			return $server;
		} catch (\Exception $e) {
			fwrite($f, "\n Ошибка: \n");
			fwrite($f, "\n ".$e->getMessage()."\n");
			fwrite($f, "\n ".$e->getTraceAsString()."\n");
			fclose($f);
		}
		
	} 
}

?>