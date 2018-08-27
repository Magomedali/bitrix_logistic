<?php

namespace Ali\Logistic\soap\clients;

use Ali\Logistic\Dictionary\ContractorsType;
use Ali\Logistic\Schemas\ContractorsSchemaTable;
use Ali\Logistic\soap\Types\Customer;

/**
* 
*/
class Contractors1C extends Client1C 
{
	
	

	public static function save($data){
		

		$client = self::init();

		if(!$client) return false;
		
		$soap_data['inn'] = $data['INN'];
		$soap_data['name'] = $data['NAME'];
		$soap_data['type'] = ContractorsType::getLabels($data['ENTITY_TYPE']);
		$soap_data['address'] = $data['LEGAL_ADDRESS'];
		$soap_data['kpp'] = $data['KPP'];
		$soap_data['ogrn'] = $data['OGRN'];
		$soap_data['bik'] = $data['BANK_BIK'];
		$soap_data['namebank'] = $data['BANK_NAME'];
		$soap_data['bankaccount'] = $data['CHECKING_ACCOUNT'];
		$soap_data['corraccount'] = $data['CORRESPONDENT_ACCOUNT'];
		$soap_data['namecontact'] = $data['USER_NAME'];
		$soap_data['email'] = $data['USER_EMAIL'];;
		$soap_data['numberphone'] = $data['USER_PHONE'];;
		
		try {
			$response = $client->createcustomer(['customer'=>$soap_data]);

			$integrator = new self();
			$integrator->parseResponce($response);

			if($integrator->success  && $integrator->uuid){
				$res = ContractorsSchemaTable::update($data['ID'],['IS_INTEGRATED'=>true,'INTEGRATED_ID'=>$integrator->uuid,'INTEGRATE_ERROR'=>false,'INTEGRATE_ERROR_MSG'=>""]);

				return $res->isSuccess();
			}else{
				$res = ContractorsSchemaTable::update($params['ID'],['INTEGRATE_ERROR'=>true,'INTEGRATE_ERROR_MSG'=>$integrator->error_msg]);

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
	




	public static function loadContractors(){
		$client = self::init();
		
		if(!$client) return false;

		try {
			$responce = $client->getcustomers();

			if(isset($responce->return) && isset($responce->return->customer)){
				return self::saveToSite($responce->return->customer);
			}

		} catch (\Exception $e) {
			return false;
		}

		return false;
	}





	public static function saveToSite($data){



		$log = array();
		if(is_array($data)){
			foreach ($data as $key => $row) {
				$c_data = json_decode(json_encode($row),true);
				
				$c = new Customer($c_data);

				$res = $c->save();

				if($res->isSuccess()){
					$log['success_log'][] = $c_data['name']." - ".$c_data['inn'];
				}else{
					$log['error_log'][] = $c_data['name']." - ".$c_data['inn'];
				}
			}
		}

		return $log;
	}
}