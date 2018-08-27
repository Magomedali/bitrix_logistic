<?php

namespace Ali\Logistic\soap\Types;

use Ali\Logistic\Dictionary\ContractorsType;
use Ali\Logistic\Schemas\ContractorsSchemaTable;

class Customer 
{

    public $uuid;
    public $inn;
    public $name;
    public $kpp;
    public $type;
    public $address;
    public $ogrn;
    public $bik;
    public $namebank;
    public $bankaccount;
    public $corraccount;
    public $namecontact;
    public $email;
    public $numberphone;
    
    public function __construct(array $data){

        $this->uuid = $data['uuid'];
        $this->name = $data['name'];
        $this->inn = $data['inn'];
        $this->kpp = $data['kpp'];
        $this->type = $data['type'];
        $this->address = $data['address'];
        $this->ogrn = $data['ogrn'];
        $this->bik = $data['bik'];
        $this->namebank = $data['namebank'];
        $this->bankaccount = $data['bankaccount'];
        $this->corraccount = $data['corraccount'];
        $this->namecontact = $data['namecontact'];
        $this->email = $data['email'];
        $this->numberphone = $data['numberphone'];
    }



    public function save(){


    	$data['IS_INTEGRATED']=true;
    	$data['INTEGRATED_ID']=$this->uuid;
    	$data['NAME']=$this->name;
    	$data['LEGAL_ADDRESS']=$this->address;
    	$data['ENTITY_TYPE']=ContractorsType::getCode($this->type);
    	$data['INN']=$this->inn;
    	$data['KPP']=$this->kpp;
    	$data['OGRN']=$this->ogrn;
    	$data['BANK_BIK']=$this->bik;
    	$data['BANK_NAME']=$this->namebank;
    	$data['CHECKING_ACCOUNT']=$this->bankaccount;
    	$data['CORRESPONDENT_ACCOUNT']=$this->corraccount;

    	$row = ContractorsSchemaTable::getRow(array('select'=>array('ID'),'filter'=>array('INTEGRATED_ID'=>$this->uuid)));

    	if(is_array($row) && isset($row['ID']) && (int)$row['ID']){
    		$res = ContractorsSchemaTable::update((int)$row['ID'],$data);
    	}else{
    		$res = ContractorsSchemaTable::add($data);
    	}

    	return $res;
    }

}
?>