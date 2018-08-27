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

	public $success = false;
	public $uuid;
	public $error_msg = null;


	public function parseResponce($response){


		if(isset($response->return) && isset($response->return->success) && $response->return->success){
			$this->success = true;
		}

		if(isset($response->return) && isset($response->return->uuid) && $response->return->uuid){
			$this->uuid = $response->return->uuid;
		}

		
		if(isset($response->return) && isset($response->return->error) && $response->return->error != null){
			$this->error_msg = $response->return->error;
		}


	}

	public function init(){


		$option = array(
	        'login'=>self::$user,
	        'password'=>self::$password
        );
		try {
			$client = new \SoapClient(self::$wsdl_url,$option);
			
			return $client;

		} catch (\Exception $e) {
			
			return false;
		}
	
	}
}