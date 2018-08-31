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
	public $doc_number = null;



	public $log_path = "logs/";

	public function log($tag,$msg){
		$l = "log.log";
		$f = fopen($l, "w");
		
		fwrite($f, "\n\n--".$tag."--".date("H:i d.m.Y",time()));
		fwrite($f, "\n\n--".$msg);

		fclose($f);
	}

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

		if(isset($response->return) && isset($response->return->number) && $response->return->number != null){
			$this->doc_number = $response->return->number;
		}

		$this->log("responce",json_encode($response));
	}

	public function init(){


		$option = array(
	        'login'=>self::$user,
	        'password'=>self::$password
        );
		try {
			header('Cache-Control: no-store, no-cache');
			ini_set("soap.wsdl_cache_enabled", "0"); 
			$client = new \SoapClient(self::$wsdl_url,$option);
			
			return $client;

		} catch (\Exception $e) {
			
			return false;
		}
	
	}
}