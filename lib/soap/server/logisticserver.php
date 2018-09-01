<?php

namespace Ali\Logistic\soap\Server;

use Ali\Logistic\soap\Server\ServerHandler;

class LogisticServer{


	public static function init(){

		$server = new \SoapServer("http://".$_SERVER['HTTP_HOST']."/alkserver/wsdl.php");
		return $server;
	} 
}

?>