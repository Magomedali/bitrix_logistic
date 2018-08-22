<?php

namespace Ali\Logistic\soap\clients;

use Ali\Logistic\Dictionary\ContractorsType;

/**
* 
*/
class Contractors1C extends Client1C 
{
	


	public static function save($data){
		

		$client = self::init();

		$soap_data['inn'] = $data['INN'];
		$soap_data['name'] = $data['NAME'];
		$soap_data['type'] = ContractorsType::getLabels($data['ENTITY_TYPE']);
		$soap_data['address'] = $data['LEGAL_ADDRESS'];
		$soap_data['kpp'] = $data['KPP'];
		$soap_data['ogrn'] = $data['OGRN'];
		
		try {
			$output = $client->getcustomer(['customer'=>$soap_data]);

			if($output->success && $output->uuid){

			}
		} catch (Exception $e) {
			
		}
		

		var_dump($output);
		// print_r($soap_data);
		exit;
		return true;
	}



	public static function delete($id){
		return true;
	}
	
}