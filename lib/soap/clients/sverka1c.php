<?php

namespace Ali\Logistic\soap\clients;

use Bitrix\Main\Result;
use Bitrix\Main\Error;
use Bitrix\Main\Type\DateTime;
use Ali\Logistic\Schemas\ReviseSchemaTable;

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
		$soap_data['customeruuid'] = $data['contractor'];//"10f36d90-6fea-11e4-bebb-d43d7ef75401"
		$soap_data['datefrom'] = date("Y-m-d",strtotime($data['dateFrom']));
		$soap_data['dateby'] = date("Y-m-d",strtotime($data['dateTo']));
		
		try {

			$request = new \stdClass();
			$request->parametrs = $soap_data;
			$response = $client->reconciliationreport($request);

			$integrator = new self();
			$integrator->parseResponce($response);
			if($integrator->success && $integrator->revise){
				$r['DATE_START'] = DateTime::createFromTimestamp(strtotime($soap_data['datefrom']));
				$r['DATE_FINISH'] = DateTime::createFromTimestamp(strtotime($soap_data['dateby']));
				$r['CONTRACTOR_ID'] = $data['contractor_id'];
				$r['CONTRACTOR_UUID'] = $soap_data['customeruuid'];
				$r['WITH_NDS'] = $soap_data['organizationsnds']  && true;

				$file = "revise_".$soap_data['customeruuid']."_".date("YmdHis",time()).".pdf";
				$path = ALI_REVISES_PATH;
				$filePath = $path.$file;
				if($path){

					$f = fopen($filePath, "w");
					fwrite($f, $integrator->revise);
					fclose($f);
				}


				if(file_exists($filePath)){
					$r['FILE'] = $file;

					$result = ReviseSchemaTable::add($r);
					return $result;
				}else{
					$result->addError(new Error("Сверка не сохранена. Ошибка при сохранении сверки в файл!",404));
					return $result;
				}
			}else{
				$error = $integrator->error ? $integrator->error : "Сверка не получена!";
				$result->addError(new Error($error, 404));
				return $result;
			}

			
			
		}catch(\SoapFault $e){
			$result->addError(new Error($e->getMessage(),$e->getCode()));
			return $result;
			
		}catch (\Exception $e) {
			$result->addError(new Error($e->getMessage(),$e->getCode()));
			return $result;
		}
		

		
		$result->addError(new Error("Сервер 1С не доступен",404));
		return $result;
	}


}