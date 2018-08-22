<?php

namespace Ali\Logistic;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Entity\Result;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;
use \Ali\Logistic\Schemas\DealsSchemaTable;
use Ali\Logistic\soap\clients\Deals1C;



class Deals{




	public static function defaultSelect(){
        return array(
            '*'
        );
    }



    public static function save($data){
       
        
        

        $data['REQUIRES_LOADER'] = isset($data['REQUIRES_LOADER']);
        $data['REQUIRES_INSURANCE'] = isset($data['REQUIRES_INSURANCE']);
        $data['SUPPORT_REQUIRED'] = isset($data['SUPPORT_REQUIRED']);
        $data['ADDITIONAL_EQUIPMENT'] = isset($data['ADDITIONAL_EQUIPMENT']);
        $data['ADDITIONAL_EQUIPMENT_CONICS'] = isset($data['ADDITIONAL_EQUIPMENT_CONICS']);
        $data['ADDITIONAL_EQUIPMENT_RAMPS'] = isset($data['ADDITIONAL_EQUIPMENT_RAMPS']);
        $data['ADDITIONAL_EQUIPMENT_TAIL_LIFT'] = isset($data['ADDITIONAL_EQUIPMENT_TAIL_LIFT']);
        $data['ADDITIONAL_EQUIPMENT_MANIPULATOR'] = isset($data['ADDITIONAL_EQUIPMENT_MANIPULATOR']);
        $data['ADDITIONAL_EQUIPMENT_WRECKER'] = isset($data['ADDITIONAL_EQUIPMENT_WRECKER']);
        $data['ADDITIONAL_EQUIPMENT_CRANE'] = isset($data['ADDITIONAL_EQUIPMENT_CRANE']);
        $data['REQUIRED_DOCUMENTS'] = isset($data['REQUIRED_DOCUMENTS']);
        $data['REQUIRED_DOCUMENTS_PROCURATION'] = isset($data['REQUIRED_DOCUMENTS_PROCURATION']);
        $data['REQUIRED_DOCUMENTS_MEDICAL_BOOK'] = isset($data['REQUIRED_DOCUMENTS_MEDICAL_BOOK']);
        $data['REQUIRED_DOCUMENTS_SANITIZATION'] = isset($data['REQUIRED_DOCUMENTS_SANITIZATION']);
        $data['WITH_NDS'] = isset($data['WITH_NDS']) && (int)$data['WITH_NDS'];
        
        

        


        

        $primary = isset($data['ID']) ? ['ID'=>$data['ID']] : null;
        if($primary)
            $result = DealsSchemaTable::update($primary,$data);
        else
            $result = DealsSchemaTable::add($data);
        


        if($result->isSuccess()){
            $responce = Deals1C::save($data);
        }

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

}