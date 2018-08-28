<?php

namespace Ali\Logistic\soap\clients;



/**
* 
*/
class Sverka1C extends Client1C 
{
	
	

	public static function get($data){
		

		$client = self::init();

		if(!$client) return false;
		
		$soap_data['organizationsnds'] = $data['with_nds'] && true;
		$soap_data['customeruuid'] = $data['contractor'];
		$soap_data['datefrom'] = date("Y-m-d",strtotime($data['dateFrom']));
		$soap_data['dateby'] = date("Y-m-d",strtotime($data['dateTo']));
		
		try {

			$request = new \stdClass();
			$request->parametrs = $soap_data;
			$response = $client->reconciliationreport($request);

			$f = fopen("sverka.pdf", "w");
			fwrite($f, $response);
			fclose($f);
			exit;

			$integrator = new self();
			$integrator->parseResponce($response);

			if($integrator->success  && $integrator->uuid){
				$res = ContractorsSchemaTable::update($data['ID'],['IS_INTEGRATED'=>true,'INTEGRATED_ID'=>$integrator->uuid,'INTEGRATE_ERROR'=>false,'INTEGRATE_ERROR_MSG'=>""]);

				return $res->isSuccess();
			}else{
				$res = ContractorsSchemaTable::update($params['ID'],['INTEGRATE_ERROR'=>true,'INTEGRATE_ERROR_MSG'=>$integrator->error_msg]);

				return $res->isSuccess();
			}

		}catch(\SoapFault $e){
			
			print_r($e->getMessage());
			exit;
			return false;
		}catch (\Exception $e) {
			return false;
		}
		

		
		return false;
	}


}