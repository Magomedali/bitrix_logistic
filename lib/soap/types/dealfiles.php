<?php

namespace Ali\Logistic\soap\Types;

use Ali\Logistic\Schemas\DealsSchemaTable;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Result;
use Bitrix\Main\Error;
use Bitrix\Main\Entity\Query;

class DealFiles 
{
	
	public $dealUuid;
	public $fileNumber;
	public $fileDate;
	public $binaryFile;

	protected $deal_id = null;



	function __construct($data)
	{
		$this->dealUuid = $data['dealUuid'];
		$this->fileNumber = $data['fileNumber'];
		$this->fileDate = $data['fileDate'];
		$this->binaryFile = $data['binaryFile'];
	}

	public function getDealId(){
		return $this->deal_id;
	}

	public function checkDealExists($uuid=null){

		$dealUuid = $uuid ? $uuid : $this->dealUuid;
        $res = new Result();
        $row = DealsSchemaTable::getRow(array('select'=>array('ID'),'filter'=>array('INTEGRATED_ID'=>$dealUuid)));
        if(!isset($row['ID']) || empty($row['ID']) || $row['ID'] == ''){
            $res->addError(new Error("Заявка не найдена",1));
        }else{
        	$this->deal_id = (int)$row['ID'];
        }

        return $res;
    } 


	
}