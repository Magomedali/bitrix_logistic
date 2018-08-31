<?php

namespace Ali\Logistic\soap\Server;

use Ali\Logistic\soap\Server\ServerHandler;

class LogisticServer{


	public static function init(){

		
		// $server = new \SoapServer($_SERVER['HTTP_HOST']."/alklogistic/wsdl.php");
		$server = new \SoapServer("https://rusexpeditor.ru/alkserver/wsdl.php");

		return $server;
	} 
}

?>