<?php

namespace Ali\Logistic\soap\clients;

use Bitrix\Main\Result;
use Bitrix\Main\Error;

/**
* 
*/
class Sverka1C extends Client1C 
{
	
	

	public static function get($data){
		
		$result = new Result();

		$client = self::init();

		if(!$client){
			$result->addError(new Error("Сервер 1С не доступен",404));
			return $result;
		}
		
		$soap_data['organizationsnds'] = $data['with_nds'] && true;
		$soap_data['customeruuid'] = "10f36d90-6fea-11e4-bebb-d43d7ef75401";//$data['contractor'];
		$soap_data['datefrom'] = date("Y-m-d",strtotime($data['dateFrom']));
		$soap_data['dateby'] = date("Y-m-d",strtotime($data['dateTo']));
		
		
		try {

			$request = new \stdClass();
			$request->parametrs = $soap_data;
			$response = $client->reconciliationreport($request);

			$f = fopen(__DIR__."/sverka.pdf", "w");

			$tf = fopen(__DIR__."/sverka.txt", "w");
			fwrite($f, $response);
			fwrite($tf, $response);
			fclose($f);
			fclose($tf);
			exit;
		}catch(\SoapFault $e){
			
			print_r($e->getMessage());
			exit;
			
			$result->addError(new Error($e->getMessage(),$e->getCode()));
			return $result;
			
		}catch (\Exception $e) {

			print_r($e->getMessage());
			exit;
			
			$result->addError(new Error($e->getMessage(),$e->getCode()));
			return $result;
		}
		

		
		$result->addError(new Error("Сервер 1С не доступен",404));
		return $result;
	}


}