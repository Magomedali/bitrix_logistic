<?php

namespace Ali\Logistic\soap\Types;

use Ali\Logistic\Dictionary\RoutesKind;
use Ali\Logistic\Schemas\RoutesSchemaTable;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Result;
use Bitrix\Main\Error;
use Bitrix\Main\Entity\Query;

class Route 
{
	
	private $deal_id;

	public $uuid;
	public $typeshipment;
	public $datefrom;
	public $dateby;
	public $location;
	public $shipper;
	public $contactname;
	public $numberphone;
	public $comment;


	function __construct($route, $deal_id = null)
	{
		$this->typeshipment = $route['typeshipment'];
		$this->datefrom = $route['datefrom'];
		$this->dateby = $route['dateby'];
		$this->location = $route['location'];
		$this->shipper = $route['shipper'];
		$this->contactname = $route['contactname'];
		$this->numberphone = $route['numberphone'];
		$this->comment = $route['comment'];
		
		$this->uuid = isset($route['uuid']) ? $route['uuid'] : null;

		$this->deal_id = $deal_id ? $deal_id : null;
	}

	public function setDealId($deal_id){
		$this->deal_id = $deal_id ? $deal_id : null;
	}
	
	public static function deleteDealRoutes($deal_id){
		if($deal_id){
			global $DB;
			$sql = "DELETE FROM ".RoutesSchemaTable::getTableName(). " WHERE DEAL_ID = ".$deal_id;
			$DB->Query($sql);
		}
	}



	public function save(){


		if(!$this->deal_id){
			$res = new Result();
            $res->addError(new Error("Заявка не определена при сохранении маршрута!",2));
            return $res;
		}

		$data['IS_INTEGRATED']=true;
    	$data['INTEGRATED_ID']=$this->uuid;
    	
        $data['KIND'] = !boolval($this->typeshipment) ? RoutesKind::LOADING : RoutesKind::UNLOADING;
        $data['START_AT']=DateTime::createFromTimestamp(strtotime($this->datefrom));
    	$data['FINISH_AT']=DateTime::createFromTimestamp(strtotime($this->dateby));

    	$data['ADDRESS'] = $this->location;
    	$data['COMMENT'] = $this->comment;
    	$data['ORGANISATION'] = $this->shipper;
    	$data['PERSON'] = $this->contactname;
    	$data['PHONE'] = $this->numberphone;
    	$data['DEAL_ID'] = $this->deal_id;

    	try {
    		$res = RoutesSchemaTable::add($data);
            return $res;
        
        } catch (\Exception $e) {
            $res = new Result();
            $res->addError(new Error($e->getMessage(),$e->getCode()));
            return $res;
        }
    	
	}
}