<?php

namespace Ali\Logistic\soap\clients;

use Ali\Logistic\Dictionary\ContractorsType;
use Ali\Logistic\Schemas\ContractorsSchemaTable;

/**
* 
*/
class Contractors1C extends Client1C 
{
	
	public $success = false;
	public $uuid;


	public function parseResponce($response){


		if(isset($response->return) && isset($response->return->success) && $response->return->success){
			$this->success = true;
		}

		if(isset($response->return) && isset($response->return->uuid) && $response->return->uuid){
			$this->uuid = $response->return->uuid;
		}


	}

	public static function save($data){
		

		$client = self::init();

		$soap_data['inn'] = $data['INN'];
		$soap_data['name'] = $data['NAME'];
		$soap_data['type'] = ContractorsType::getLabels($data['ENTITY_TYPE']);
		$soap_data['address'] = $data['LEGAL_ADDRESS'];
		$soap_data['kpp'] = $data['KPP'];
		$soap_data['ogrn'] = $data['OGRN'];
		
		try {
			$response = $client->createcustomer(['customer'=>$soap_data]);

			$integrator = new self();
			$integrator->parseResponce($response);

			if($integrator->success  && $integrator->uuid){
				$res = ContractorsSchemaTable::update($data['ID'],['INTEGRATED_ID'=>$integrator->uuid]);

				return $res->isSuccess();
			}

		} catch (\Exception $e) {
			return false;
		}
		

		
		return false;
	}





	public static function delete($id){
		return true;
	}
	
}