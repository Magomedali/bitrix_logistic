<?php

namespace Ali\Logistic\soap\clients;


/**
* 
*/
abstract class Client1C 
{
	
	protected static $wsdl_url = "http://91.77.166.249/rusexeditorTest/ws/ws1.1cws?wsdl";
	protected static $user = "Федулов Денис";
	protected static $password = "TporgS4";


	public function init(){


		$option = array(
	        'login'=>self::$user,
	        'password'=>self::$password
        );
		try {
			$client = new \SoapClient(self::$wsdl_url,$option);
			
			return $client;

		} catch (Exception $e) {
			
			return false;
		}
	
	}
}