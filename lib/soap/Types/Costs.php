<?php

namespace Ali\Logistic\soap\Types;

use Ali\Logistic\Schemas\DealCostingsSchemaTable;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Result;
use Bitrix\Main\Error;
use Bitrix\Main\Entity\Query;

class Costs 
{
	
	private $deal_id;

	public $uuid = null;
	public $servicetype;
	public $cost;
	public $quantity;
	public $sum;


	function __construct($cost, $deal_id = null)
	{
		$this->servicetype = $cost['servicetype'];
		$this->cost = $cost['cost'];
		$this->quantity = $cost['quantity'];
		$this->sum = $cost['sum'];
		$this->uuid = isset($cost['uuid']) ? $cost['uuid'] : null;

		$this->deal_id = $deal_id ? $deal_id : null;
	}


	
	public static function deleteDealCosts($deal_id){
		if($deal_id){
			global $DB;
			$sql = "DELETE FROM ".DealCostingsSchemaTable::getTableName(). " WHERE DEAL_ID = ".$deal_id;
			$DB->Query($sql);
		}
	}



	public function save(){


		if(!$this->deal_id){
			$res = new Result();
            $res->addError(new Error("Заявка не определена при сохранении расчета стоимости!",3));
            return $res;
		}

    	$data['DEAL_ID'] = $this->deal_id;

    	$data['INTEGRATED_ID']=$this->uuid;
    	
        $data['KIND_SERVICE'] = $this->servicetype;
    	$data['COST'] = $this->cost;
    	$data['QUANTITY'] = $this->quantity;
    	$data['AMOUNT'] = $this->sum;


    	return DealCostingsSchemaTable::add($data);
	}
}