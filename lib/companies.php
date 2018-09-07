<?php


namespace Ali\Logistic;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;
use Ali\Logistic\Schemas\CompaniesSchemaTable;
use Ali\Logistic\Schemas\ContractorsSchemaTable;
use Ali\Logistic\Schemas\CompanyEmployeeSchemaTable;
use Ali\Logistic\User;

class Companies
{   

    


    public static function hasComanyContractors($company_id){
        $contractors = ContractorsSchemaTable::getList(array('select'=>array("ID","COMPANY_ID","NAME","INTEGRATED_ID"),'filter'=>array("COMPANY_ID"=>$company_id,"!=INTEGRATED_ID"=>'')))->fetchAll();

        return $contractors;
    }


    public static function registeUser($company_id,$user_id){
        return CompanyEmployeeSchemaTable::add(['COMPANY_ID'=>$company_id,'EMPLOYEE_ID'=>$user_id]);
    }


    public static function createCompanyForCurrentUser(){
        global $USER;

        if($USER->GetId() && !User::hasCompany($USER->GetId())){
            $res = CompaniesSchemaTable::add(['OWNER_ID'=>$USER->GetId()]);
            return $res->isSuccess() ? true :false;
        }
        return false;
    }




    public static function createCompanyForUser($id){
        
        if($id){
            $res = CompaniesSchemaTable::add(['OWNER_ID'=>$id]);
            return $res->isSuccess() ? $res->getId() :false;
        }
        return false;
    }
}