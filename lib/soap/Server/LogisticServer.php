<?php

namespace Ali\Logistic\soap\Server;

use Ali\Logistic\soap\Server\ServerHandler;

class LogisticServer{


	public static function init(){

		
		// $server = new \SoapServer($_SERVER['HTTP_HOST']."/alklogistic/wsdl.php");
		$server = new \SoapServer("http://rus:8080/alklogistic/wsdl.php");

		return $server;
	} 
}

?>