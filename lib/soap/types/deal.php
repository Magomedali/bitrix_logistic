<?php

namespace Ali\Logistic\soap\Types;

use Ali\Logistic\Dictionary\ContractorsType;
use Ali\Logistic\Schemas\DealsSchemaTable;
use Ali\Logistic\Schemas\ContractorsSchemaTable;
use Ali\Logistic\soap\Types\Route;
use Ali\Logistic\soap\Types\Costs;
use Ali\Logistic\Dictionary\WayOfTransportation;
use Ali\Logistic\Dictionary\DealStates;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Result;
use Bitrix\Main\Error;

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
    public $methodoftransportation;
    public $escort;
    public $documentation;
    public $size;
    public $length;
    public $width;
    public $methodofloading;
    public $routes = array();
    public $costs = array();
    public $driver;
    public $vehicle;
    public $status;

    function __construct($data)
    {
        $this->uuid = isset($data['uuid']) ? $data['uuid'] : null;
        $this->number = isset($data['number']) ? $data['number'] : null;
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
        $this->methodoftransportation = $data['methodoftransportation'];
        $this->escort = $data['escort'];
        $this->documentation = $data['documentation'];
        $this->size = $data['size'];
        $this->length = $data['length'];
        $this->width = $data['width'];
        $this->height = $data['height'];
        $this->methodofloading = $data['methodofloading'];
        $this->driver = $data['driver'];
        $this->vehicle = $data['vehicle'];
        $this->status = $data['status'];

        if(isset($data['routes']) && is_array($data['routes']) && count($data['routes'])){
            
            if(isset($data['routes']['typeshipment'])){
                $this->routes = array(new Route($data['routes']));
            }else{
                foreach ($data['routes'] as $r) {
                    array_push($this->routes, new Route($r));
                } 
            }

            
        }

        if(isset($data['costs']) && is_array($data['costs']) && count($data['costs'])){
            if(isset($data['costs']['servicetype'])){
                $this->costs = array(new Costs($data['costs']));
            }else{
                foreach ($data['costs'] as $c) {
                    array_push($this->costs, new Costs($c));
                }
            }
        }
    }



    public function save(){


    	$data['IS_INTEGRATED']=true;
    	$data['INTEGRATED_ID']=$this->uuid;
    	
        $data['NAME']=$this->namecargo;
        $data['DOCUMENT_NUMBER']=$this->number;

        

    	$data['WEIGHT']=$this->weight;
    	$data['SPACE']=$this->size;

    	$data['WIDTH']=$this->width;
    	$data['HEIGHT']=$this->height;
    	$data['LENGTH']=$this->length;

    	$data['TYPE_OF_VEHICLE']= is_array($this->ts) ? implode(";", $this->ts) : $this->ts;
    	$data['LOADING_METHOD']=is_array($this->methodofloading) ? implode(";", $this->methodofloading) : $this->methodofloading;

    	$data['WAY_OF_TRANSPORTATION'] = WayOfTransportation::getCode($this->methodoftransportation);
    	$data['REQUIRES_LOADER'] = boolval($this->countloaders);
        $data['COUNT_LOADERS'] = $this->countloaders;
        $data['COUNT_HOURS'] = $this->quantityofhours;
        $data['REQUIRES_INSURANCE'] = boolval($this->insurance);
        $data['REQUIRES_TEMPERATURE_FROM'] = $this->temperaturefrom;
        $data['REQUIRES_TEMPERATURE_TO'] = $this->temperatureto;

        $data['SUPPORT_REQUIRED'] = boolval($this->escort);
        $data['ADDITIONAL_EQUIPMENT'] = is_array($this->additionalequipment) ? implode(";", $this->additionalequipment) : $this->additionalequipment;
        $data['REQUIRED_DOCUMENTS'] = is_array($this->documentation) ? implode(";", $this->documentation) : $this->documentation;
        $data['WITH_NDS'] = boolval($this->nds);

        $data['STATE'] = DealStates::getCode($this->status);
        
        $data['DRIVER_INFO'] = $this->driver;
        
        $data['VEHICLE'] = $this->vehicle;

        $data['IS_ACTIVE'] = true;
        
        $data['CREATED_AT'] = DateTime::createFromTimestamp(strtotime($this->datedoc));

        //Определяем контрагента
        $contr = ContractorsSchemaTable::getRow(array('select'=>array('ID'),'filter'=>array('INTEGRATED_ID'=>$this->uuidcustomer)));

        if(!isset($contr['ID']) || empty($contr['ID']) || $contr['ID'] == ''){
            $res = new Result();
            $res->addError(new Error("Контрагент с uuid '".$this->uuidcustomer."' не найден!",1));
            return $res;
        }

        $data['CONTRACTOR_ID'] = $contr['ID'];

    	$row = DealsSchemaTable::getRow(array('select'=>array('ID'),'filter'=>array('INTEGRATED_ID'=>$this->uuid)));

    	
        try {
            if(is_array($row) && isset($row['ID']) && (int)$row['ID']){
                $res = DealsSchemaTable::update((int)$row['ID'],$data);
            }else{
                $res = DealsSchemaTable::add($data);
            }
            
            return $res;

        } catch (\Exception $e) {
            $res = new Result();
            $res->addError(new Error($e->getMessage(),$e->getCode()));
            return $res;
        }
    }





    public static function checkDealExists($dealUuid){
        $res = new Result();
        $row = DealsSchemaTable::getRow(array('select'=>array('ID'),'filter'=>array('INTEGRATED_ID'=>$dealUuid)));
        if(!isset($row['ID']) || empty($row['ID']) || $row['ID'] == ''){
            $res->addError(new Error("Заявка не найдена",1));
        }
        return $res;
    } 



    public static function sendFileBill($dealUuid,$fNumber,$file){
        $res = self::checkDealExists($dealUuid);
        if(!$res->isSuccess()) return $res;
        return \Ali\Logistic\DealFiles::sendFileBill($row['ID'],$fNumber,$file);
    }




    public static function sendFileAct($dealUuid,$fNumber,$file){
        $res = self::checkDealExists($dealUuid);
        if(!$res->isSuccess()) return $res;

        return \Ali\Logistic\DealFiles::sendFileAct($row['ID'],$fNumber,$file);
    }




    public static function sendFileInvoice($dealUuid,$fNumber,$file){
        $res = self::checkDealExists($dealUuid);
        if(!$res->isSuccess()) return $res;

        return \Ali\Logistic\DealFiles::sendFileInvoice($row['ID'],$fNumber,$file);
    }




    public static function sendFileContract($dealUuid,$fNumber,$file){
        $res = self::checkDealExists($dealUuid);
        if(!$res->isSuccess()) return $res;

        return \Ali\Logistic\DealFiles::sendFileContract($row['ID'],$fNumber,$file);
    }




    public static function sendFileDriverAttorney($dealUuid,$fNumber,$file){
        $res = self::checkDealExists($dealUuid);
        if(!$res->isSuccess()) return $res;

        return \Ali\Logistic\DealFiles::sendFileDriverAttorney($row['ID'],$fNumber,$file);
    }




    public static function sendFilePrintForm($dealUuid,$fNumber,$file){
        $res = self::checkDealExists($dealUuid);
        if(!$res->isSuccess()) return $res;

        return \Ali\Logistic\DealFiles::sendFilePrintForm($row['ID'],$fNumber,$file);
    }


    public static function sendFileTTH($dealUuid,$fNumber,$file){
        $res = self::checkDealExists($dealUuid);
        if(!$res->isSuccess()) return $res;

        return \Ali\Logistic\DealFiles::sendFileTTH($row['ID'],$fNumber,$file);
    }



}
?>