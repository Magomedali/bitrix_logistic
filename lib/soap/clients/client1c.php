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
	public static $log_file = "logs.log";


	protected $client;

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
	        'password'=>self::$password,
	        'trace'=>1
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




	public static function printLogLastRequest($client){
		if($client){
			$path = ALI_LOG_SOAP_CLIENT_PATH;
			$file = $path.self::$log_file;
			$f = fopen($file, "a+");

			fwrite($f, "\n\n-------Request Headers------".date("H:i d.m.Y")."\n\n");
			fwrite($f, $client->__getLastRequestHeaders());
			fwrite($f, "\n\n-------Request------".date("H:i d.m.Y")."\n\n");
			fwrite($f, $client->__getLastRequest());
			fclose($f);
		}
		
	}



	public static function printLogLastResponse($client){
		if($client){
			$path = ALI_LOG_SOAP_CLIENT_PATH;
			$file = $path.self::$log_file;
			$f = fopen($file, "a+");

			fwrite($f, "\n\n-------Response Headers------".date("H:i d.m.Y")."\n\n");
			fwrite($f, $client->__getLastResponseHeaders());
			fwrite($f, "\n\n-------Response------".date("H:i d.m.Y")."\n\n");
			fwrite($f, $client->__getLastResponse());
			fclose($f);
		}
		
	}
}