<?php

namespace Ali\Logistic\soap\Types;

use Ali\Logistic\Dictionary\ContractorsType;
use Ali\Logistic\Schemas\ContractorsSchemaTable;

class Deal 
{

    public $uuid;
    public $number;
    public $datedoc;
    public $uuidcustomer;
    public $namecargo;
    public $weight;
    public $ts;
    public $comments;
    public $nds;
    public $countloaders;
    public $quantityofhours;
    public $insurance;
    public $sum;
    public $temperaturefrom;
    public $temperatureto;
    public $additionalequipment;
    public $escort;
    public $documentation;
    public $size;
    public $length;
    public $width;
    public $method;
    public $routes = array();

    function __construct($data)
    {
        $this->uuid = isset($data['uuid']) ? $data['uuid'] : null;
        $this->number = isset($data['number']) ? $data['number'] : "228352172612048";
        $this->datedoc = $data['datedoc'];
        $this->uuidcustomer = $data['uuidcustomer'];
        $this->namecargo = $data['namecargo'];
        $this->weight = $data['weight'];
        $this->ts = $data['ts'];
        $this->comments = $data['comments'];
        $this->nds = $data['nds'];
        $this->countloaders = $data['countloaders'];
        $this->quantityofhours = $data['quantityofhours'];
        $this->insurance = $data['insurance'];
        $this->sum = isset($data['sum']) ? $data['sum'] : null;
        $this->temperaturefrom = $data['temperaturefrom'];
        $this->temperatureto = $data['temperatureto'];
        $this->additionalequipment = $data['additionalequipment'];
        $this->escort = $data['escort'];
        $this->documentation = $data['documentation'];
        $this->size = $data['size'];
        $this->length = $data['length'];
        $this->width = $data['width'];
        $this->height = $data['height'];
        $this->method = $data['method'];

        if(isset($data['routes']) && is_array($data['routes']) && count($data['routes'])){
            foreach ($data['routes'] as $r) {
                array_push($this->routes, new Route($r));
            }
        }
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

    	$row = DealsSchemaTable::getRow(array('select'=>array('ID'),'filter'=>array('INTEGRATED_ID'=>$this->uuid)));

    	if(is_array($row) && isset($row['ID']) && (int)$row['ID']){
    		$res = DealsSchemaTable::update((int)$row['ID'],$data);
    	}else{
    		$res = DealsSchemaTable::add($data);
    	}

    	return $res;
    }

}
?>