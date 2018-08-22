<?php

namespace Ali\Logistic;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;
use \Ali\Logistic\Schemas\DealsSchemaTable;


class Deals{




	public static function defaultSelect(){
        return array(
            'ID','OWNER_ID','NAME'
        );
    }



    public static function save($data){
       
        
        $res = new Result();
        
        $primary = isset($data['ID']) ? ['ID'=>$data['ID']] : null;
        DealsSchemaTable::checkFields($res,$primary,$data);
        

        if(!$res->isSuccess()){
            
            return $res;
        }



        if(isset($data['ID']))
            $result = DealsSchemaTable::update(['ID'=>$data['ID']],$data);
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