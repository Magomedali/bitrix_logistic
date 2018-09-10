<?php

namespace Ali\Logistic\soap\clients;


use Ali\Logistic\Dictionary\RoutesKind;
use Ali\Logistic\Dictionary\DealStates;
use Ali\Logistic\Dictionary\WayOfTransportation;
use Ali\Logistic\Dictionary\TypeOfVehicle;
use Ali\Logistic\Dictionary\AdditionalEquipment;
use Ali\Logistic\Dictionary\SpecialEquipment;
use Ali\Logistic\Dictionary\Documents;
use Ali\Logistic\Dictionary\LoadingMethod;
use Ali\Logistic\Dictionary\HowPacked;

use Ali\Logistic\soap\Types\Deal;
use Ali\Logistic\Schemas\DealsSchemaTable;
use Bitrix\Main\Type\DateTime;

use Bitrix\Main\Result;
use Bitrix\Main\Error;
/**
* 
*/
class Deals1C extends Client1C 
{
	
	


	/**
	* @return Result object
	*/
	public static function save($params){
      	
		$result = new Result;

      	$client = self::init();

		if(!$client){
			$result->addError(new Error("Сервер 1С не доступен",404));
			return $result;
		} 

        $data['uuid'] = $params['INTEGRATED_ID'];
		$data['datedoc'] = $params['CREATED_AT'] instanceof DateTime ? $params['CREATED_AT']->format("Y-m-d H:i") : $params['CREATED_AT'] ;
		$data['uuidcustomer'] = $params['CONTRACTOR_INTEGRATED_ID'];
		$data['namecargo'] = $params['NAME'];
		$data['weight'] = $params['WEIGHT'];

		$data['ts'] = is_array($params['TYPE_OF_VEHICLE']) ? $params['TYPE_OF_VEHICLE'] : explode(TypeOfVehicle::getDelimiter(), $params['TYPE_OF_VEHICLE']);

		

		$data['comments'] = null;
		$data['nds'] = $params['WITH_NDS'];

		$data['cargohandling'] = $params['CARGO_HANDLING'];
        $data['crossdocking'] = $params['CROSS_DOCKING'];
        $data['securestorage'] = $params['SECURE_STORAGE'];
        $data['reqrussiandriver'] = $params['REQUIRED_RUSSIAN_DRIVER'];
        $data['adrclass'] = $params['ADR_CLASS'];

		$data['countloaders'] = $params['COUNT_LOADERS'];
		$data['quantityofhours'] = $params['COUNT_HOURS'];
		$data['countplace'] = $params['COUNT_PLACE'];
		$data['insurance'] = $params['REQUIRES_INSURANCE'];
		
		$data['sum'] = $params['SUM'];
		
		$data['temperaturefrom'] = $params['REQUIRES_TEMPERATURE_FROM'];
		$data['temperatureto'] = $params['REQUIRES_TEMPERATURE_TO'];
		$data['additionalequipment'] = is_array($params['ADDITIONAL_EQUIPMENT']) ? $params['ADDITIONAL_EQUIPMENT'] : explode(AdditionalEquipment::getDelimiter(), $params['ADDITIONAL_EQUIPMENT']);

		$data['specialequipment'] = is_array($params['SPECIAL_EQUIPMENT']) ? $params['SPECIAL_EQUIPMENT'] : explode(SpecialEquipment::getDelimiter(), $params['SPECIAL_EQUIPMENT']);

		$data['escort'] = $params['SUPPORT_REQUIRED'];
		$data['documentation'] = is_array($params['REQUIRED_DOCUMENTS']) ? $params['REQUIRED_DOCUMENTS'] : explode(Documents::getDelimiter(), $params['REQUIRED_DOCUMENTS']);
		$data['size'] = $params['SPACE'];
		$data['length'] = $params['LENGTH'];
		$data['width'] = $params['WIDTH'];
		$data['height'] = $params['HEIGHT'];
		$data['sum'] = $params['SUM'];

		$data['comments'] = $params['COMMENTS'];
		
		$data['methodofloading'] = is_array($params['LOADING_METHOD']) ? $params['LOADING_METHOD'] : explode(LoadingMethod::getDelimiter(), $params['LOADING_METHOD']);
		$data['methodofunloading'] = is_array($params['UNLOADING_METHOD']) ? $params['LOADING_METHOD'] : explode(LoadingMethod::getDelimiter(), $params['LOADING_METHOD']);

		$data['howpacked'] = HowPacked::getLabels($params['HOW_PACKED']);
		$data['methodoftransportation'] = WayOfTransportation::getLabels($params['WAY_OF_TRANSPORTATION']);
      	$data['routes'] = array();


		if(isset($params['ROUTES']) && count($params['ROUTES'])){
			foreach ($params['ROUTES'] as $r) {
				$route = array();
				$route['typeshipment'] = (int)$r['KIND'] != RoutesKind::LOADING;
				$route['datefrom'] = date("Y-m-d H:i",strtotime($r['START_AT']));
				$route['dateby'] = date("Y-m-d H:i",strtotime($r['FINISH_AT']));
				$route['town'] = $r['TOWN'];
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
			
			if($integrator->success  && $integrator->uuid){
				$res = DealsSchemaTable::update($params['ID'],['IS_DRAFT'=>false,'IS_ACTIVE'=>true,'IS_INTEGRATED'=>true,'INTEGRATED_ID'=>$integrator->uuid,'INTEGRATE_ERROR'=>false,'INTEGRATE_ERROR_MSG'=>"",'STATE'=>DealStates::IN_CONFIRMING,'DOCUMENT_NUMBER'=>$integrator->doc_number]);

				return $res;
			}else{
				$res = DealsSchemaTable::update($params['ID'],['IS_DRAFT'=>true,'IS_ACTIVE'=>false,'IS_INTEGRATED'=>false,'INTEGRATE_ERROR'=>true,'INTEGRATE_ERROR_MSG'=>$integrator->error_msg]);

				$result->addError(new Error($integrator->error_msg,500));
				return $result;
			}

		} catch (\Exception $e) {
			
			DealsSchemaTable::update($params['ID'],['IS_DRAFT'=>true,'IS_ACTIVE'=>false,'IS_INTEGRATED'=>false,'INTEGRATE_ERROR'=>true,'INTEGRATE_ERROR_MSG'=>$e->getMessage()]);

			$result->addError(new Error("Произошла ошибка при интеграции заявки в 1С. Пожалуйста обратитесь в тех. поддержку",500));
			return $result;
		}

		$err = "Произошла ошибка при интеграции заявки в 1С. Пожалуйста обратитесь в тех. поддержку";
		DealsSchemaTable::update($params['ID'],['IS_DRAFT'=>true,'IS_ACTIVE'=>false,'IS_INTEGRATED'=>false,'INTEGRATE_ERROR'=>true,'INTEGRATE_ERROR_MSG'=>$err]);

		$result->addError(new Error($err,500));
		return $result;
	}



	public static function delete($id){
		return true;
	}
	
}