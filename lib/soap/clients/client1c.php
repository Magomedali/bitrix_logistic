<?php

namespace Ali\Logistic\soap\clients;


/**
* 
*/
abstract class Client1C 
{
	
	protected static $wsdl_url = "http://217.76.41.228/basetest/ws/ws1.1cws?wsdl";
	protected static $user = "Федулов Денис";
	protected static $password = "TporgS4";

	public $success = false;
	public $uuid;
	public $error_msg = null;
	public $doc_number = null;
	public $revise = null;


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

		if(isset($response->return) && isset($response->return->td) && $response->return->td != null){
			$this->revise = $response->return->td;
		}

		//$this->log("responce",json_encode($response));
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