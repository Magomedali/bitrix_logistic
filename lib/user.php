<?php


namespace Ali\Logistic;

use Ali\Logistic\Schemas\CompaniesSchemaTable;
use Ali\Logistic\Schemas\ContractorsSchemaTable;
use Ali\Logistic\Schemas\DealsSchemaTable;
use Ali\Logistic\Schemas\DealFilesSchemaTable;
use Ali\Logistic\helpers\ArrayHelper;
use Ali\Logistic\Companies;
use Ali\Logistic\CompanyEmployee;

class User
{   

    public static function hasCompany($id){
        
        return self::getUserCompany($id);

    }

    
    public static function getUserContractors($id){

        $contractors = ContractorsSchemaTable::getList(array('select'=>array("ID","COMPANY_ID","NAME","INTEGRATED_ID"),'filter'=>array("OWNER_ID"=>$id,"!=INTEGRATED_ID"=>'')))->fetchAll();

        return $contractors;
    }



    public static function getUserCompany($id){

        $company = CompaniesSchemaTable::getRow(array('select'=>array("ID"),'filter'=>array("OWNER_ID"=>$id)));

        
        return isset($company['ID']) ? $company['ID'] : null;
    }











    public static function hasCurrentUserHasComany(){
        return self::getCurrentUserCompany();
    }



    public static function getCurrentUserCompany(){
        global $USER;

        $companies = array();

        $results = CompanyEmployee::getUserIsEmployeeCompanies($USER->GetId());
        
        if($results && count($results)){
            foreach ($results as $key => $value) {
                $companies[] = $value['COMPANY_ID'];
            }
        }

        $company = self::getUserCompany($USER->GetId());

        if($company && count($company)) $companies[] = $company;

        return $companies;
    }


    public static function getCurrentUserContractors(){
        global $USER;

        $contractors = ContractorsSchemaTable::getList(array('select'=>array("ID","COMPANY_ID","NAME","INTEGRATED_ID"),'filter'=>array("OWNER_ID"=>$USER->GetId())))->fetchAll();

        return $contractors;
    }










    public static function getCurrentUserIntegratedContractors(){
        global $USER;

        $companies = self::getCurrentUserCompany();

        if(!is_array($companies) || !count($companies)) return array();

        $contractors = ContractorsSchemaTable::getList(array('select'=>array("ID","COMPANY_ID","NAME","INTEGRATED_ID"),'filter'=>array("COMPANY_ID"=>$companies,"!=INTEGRATED_ID"=>'')))->fetchAll();

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