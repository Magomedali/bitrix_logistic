<?php


namespace Ali\Logistic;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;
use \Bitrix\Main\Entity\Query;
use Ali\Logistic\Schemas\CompaniesSchemaTable;
use Ali\Logistic\Schemas\ContractorsSchemaTable;
use Ali\Logistic\helpers\ArrayHelper;
use Ali\Logistic\Schemas\DealsSchemaTable;
use Ali\Logistic\Schemas\DealFilesSchemaTable;
use Ali\Logistic\Dictionary\DealFileType;

class User
{   

    public static function hasCompany(){
        global $USER;

    }


    public static function hasCurrentUserHasComany(){
        return self::getCurrentUserCompany();
    }



    public static function getCurrentUserCompany(){
        global $USER;

        $company = CompaniesSchemaTable::getRow(array('select'=>array("ID"),'filter'=>array("OWNER_ID"=>$USER->GetId())));

        return isset($company['ID']) ? $company['ID'] : null;
    }


    public static function getCurrentUserContractors(){
        global $USER;

        $contractors = ContractorsSchemaTable::getList(array('select'=>array("ID","COMPANY_ID","NAME","INTEGRATED_ID"),'filter'=>array("OWNER_ID"=>$USER->GetId())))->fetchAll();

        return $contractors;
    }


    public static function getCurrentUserIntegratedContractors(){
        global $USER;

        $contractors = ContractorsSchemaTable::getList(array('select'=>array("ID","COMPANY_ID","NAME","INTEGRATED_ID"),'filter'=>array("OWNER_ID"=>$USER->GetId(),"!=INTEGRATED_ID"=>'')))->fetchAll();



        return $contractors;
    }


    /**
    * @return array
    */
    public static function getFiles($type){

        $files = array();
        $contractors = self::getCurrentUserIntegratedContractors();

        if(!is_array($contractors) || !count($contractors)){
            return array();
        }

        $contrs_uuids = ArrayHelper::map($contractors,'ID','ID');
        
        $deals = DealsSchemaTable::getList(['select'=>['ID'],'filter'=>['CONTRACTOR_ID'=>$contrs_uuids]])->fetchAll();

        if(is_array($deals) && count($deals)){
            $deals_id = ArrayHelper::map($deals,'ID','ID');
        
            $files = DealFilesSchemaTable::getList(['select'=>['*'],'filter'=>['DEAL_ID'=>$deals_id,'FILE_TYPE'=>$type]])->fetchAll();
        }

        return $files;
    }



    
    



    public static function getDealFile($id){

        $file = array();
        $contractors = self::getCurrentUserIntegratedContractors();

        if(!is_array($contractors) || !count($contractors)){
            return array();
        }

        $contrs_uuids = ArrayHelper::map($contractors,'ID','ID');
        
        $deals = DealsSchemaTable::getList(['select'=>['ID'],'filter'=>['CONTRACTOR_ID'=>$contrs_uuids]])->fetchAll();


        if(is_array($deals) && count($deals)){
            $deals_id = ArrayHelper::map($deals,'ID','ID');
            
            $file = DealFilesSchemaTable::getRow(['select'=>['*'],'filter'=>['ID'=>$id,'DEAL_ID'=>$deals_id]]);
        }


        return $file;
    }
}