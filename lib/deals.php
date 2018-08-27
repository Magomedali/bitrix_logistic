<?php

namespace Ali\Logistic;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Entity\Result;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;
use \Ali\Logistic\Schemas\DealsSchemaTable;
use Ali\Logistic\soap\clients\Deals1C;
use Ali\Logistic\Dictionary\TypeOfVehicle;
use Ali\Logistic\Dictionary\LoadingMethod;
use Ali\Logistic\Dictionary\AdditionalEquipment;
use Ali\Logistic\Dictionary\Documents;


class Deals{




	public static function defaultSelect(){
        return array(
            '*'
        );
    }

    public static function normalizeData($data){


        if(isset($data['TYPE_OF_VEHICLE']) && is_array($data['TYPE_OF_VEHICLE'])){
            $typesOfVehicle = TypeOfVehicle::toString($data['TYPE_OF_VEHICLE']);
            $data['TYPE_OF_VEHICLE'] = $typesOfVehicle;
        }

        if(isset($data['LOADING_METHOD']) && is_array($data['LOADING_METHOD'])){
            $loadingMethods = LoadingMethod::toString($data['LOADING_METHOD']);
            $data['LOADING_METHOD'] = $loadingMethods;
        }

        if(isset($data['ADDITIONAL_EQUIPMENT']) && is_array($data['ADDITIONAL_EQUIPMENT'])){
            $addEq = AdditionalEquipment::toString($data['ADDITIONAL_EQUIPMENT']);
            $data['ADDITIONAL_EQUIPMENT'] = $addEq;
        }

        if(isset($data['REQUIRED_DOCUMENTS']) && is_array($data['REQUIRED_DOCUMENTS'])){
            $docs = Documents::toString($data['REQUIRED_DOCUMENTS']);
            $data['REQUIRED_DOCUMENTS'] = $docs;
        }
        

        $data['REQUIRES_LOADER'] = isset($data['REQUIRES_LOADER']);
        $data['REQUIRES_INSURANCE'] = isset($data['REQUIRES_INSURANCE']);
        $data['SUPPORT_REQUIRED'] = isset($data['SUPPORT_REQUIRED']);
        $data['WITH_NDS'] = isset($data['WITH_NDS']) && (int)$data['WITH_NDS'];


        if(!isset($data['CREATED_AT']))
            $data['CREATED_AT'] = new \Bitrix\Main\Type\DateTime();

        return $data;
    }

    public static function save($data){
       
        $data = self::normalizeData($data); 
        
        $data['IS_ACTIVE'] = !$data['IS_DRAFT'];

        $primary = isset($data['ID']) ? ['ID'=>$data['ID']] : null;
        if($primary)
            $result = DealsSchemaTable::update($primary,$data);
        else
            $result = DealsSchemaTable::add($data);
        


        // if($result->isSuccess() && !$data['IS_DRAFT']){
        //     $data['ID']=$result->getId();
        // }

        return $result;
    }




    public static function getDeals($id = null,$parameters = array()){
        global $USER;
        
        $company_id = Companies::getCurrentUserCompany();
        if(!$company_id){

            //Проверка на присоединение к компании в таблице ali_logistic_company_employee
            return array();
        } 

        $local_params = array(
            'select'=>self::defaultSelect()
        );
        $params = array_merge($local_params,$parameters);
        
        $params['filter']['COMPANY_ID']=$company_id;
        
        if($id){
            
            $local_params['filter']['ID']=$id;

            return DealsSchemaTable::getRow($params);
        }else{
            return DealsSchemaTable::getList($params)->fetchAll();
        }

    }



    public static function delete($id){
        return DealsSchemaTable::delete($id);
    }



    public static function integrateDealTo1C($data){
        return Deals1C::save(self::normalizeData($data));
    }

}