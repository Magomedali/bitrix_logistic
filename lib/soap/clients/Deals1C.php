<?php

namespace Ali\Logistic\soap\clients;


use Ali\Logistic\Dictionary\RoutesKind;
use Ali\Logistic\soap\Types\Deal;
use Ali\Logistic\Schemas\DealsSchemaTable;
use Bitrix\Main\Type\DateTime;

/**
* 
*/
class Deals1C extends Client1C 
{
	
	

	public static function save($params){
      	

      	$client = self::init();

		if(!$client) return false;

        $data['uuid'] = $params['INTEGRATED_ID'];
		$data['datedoc'] = $params['CREATED_AT'] instanceof DateTime ? $params['CREATED_AT']->format("Y-m-d H:i") : $params['CREATED_AT'] ;
		$data['uuidcustomer'] = $params['CONTRACTOR_INTEGRATED_ID'];
		$data['namecargo'] = $params['NAME'];
		$data['weight'] = $params['WEIGHT'];

		$data['ts'] = $params['TYPE_OF_VEHICLE'];

		$data['comments'] = "";
		$data['nds'] = $params['WITH_NDS'];
		$data['countloaders'] = $params['COUNT_LOADERS'];
		$data['quantityofhours'] = $params['COUNT_HOURS'];
		$data['insurance'] = $params['REQUIRES_INSURANCE'];
		
		//$data['sum'] = $params['CONTRACTOR_ID'];
		
		$data['temperaturefrom'] = $params['REQUIRES_TEMPERATURE_FROM'];
		$data['temperatureto'] = $params['REQUIRES_TEMPERATURE_TO'];
		$data['additionalequipment'] = $params['ADDITIONAL_EQUIPMENT'];
		$data['escort'] = $params['SUPPORT_REQUIRED'];
		$data['documentation'] = $params['REQUIRED_DOCUMENTS'];
		$data['size'] = $params['SPACE'];
		$data['length'] = $params['LENGTH'];
		$data['width'] = $params['WIDTH'];
		$data['height'] = $params['HEIGHT'];
		$data['method'] = $params['LOADING_METHOD'];
      	$data['routes'] = array();


		if(isset($params['ROUTES']) && count($params['ROUTES'])){
			foreach ($params['ROUTES'] as $r) {
				$route = array();
				$route['typeshipment'] = RoutesKind::getLabels($r['KIND']);
				$route['datefrom'] = date("Y-m-d H:i",strtotime($r['START_AT']));
				$route['timeby'] = date("Y-m-d H:i",strtotime($r['FINISH_AT']));
				$route['location'] = $r['ADDRESS'];
				$route['shipper'] = $r['ORGANISATION'];
				$route['contactname'] = $r['PERSON'];
				$route['numberphone'] = $r['PHONE'];
				$route['comment'] = $r['COMMENT'];

				array_push($data['routes'], $route);
			}
		}

		
		$dealObject = new Deal($data);
		
     	try {
			$request = new \stdClass();
			$request->application = $dealObject;
			$response = $client->uploadapplication($request);

			$integrator = new self();
			$integrator->parseResponce($response);

			// print_r($response);
			// exit;
			if($integrator->success  && $integrator->uuid){
				$res = DealsSchemaTable::update($params['ID'],['IS_INTEGRATED'=>true,'INTEGRATED_ID'=>$integrator->uuid,'INTEGRATE_ERROR'=>false,'INTEGRATE_ERROR_MSG'=>""]);

				return $res->isSuccess();
			}else{
				$res = DealsSchemaTable::update($params['ID'],['INTEGRATE_ERROR'=>true,'INTEGRATE_ERROR_MSG'=>$integrator->error_msg]);

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