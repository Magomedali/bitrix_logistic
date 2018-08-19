<?php


namespace Ali\Logistic;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;
use Ali\Logistic\Schemas\CompaniesSchemaTable;

class Companies
{   

    

    public static function hasCurrentUserHasComany(){
        return self::getCurrentUserCompany();
    }



    public static function getCurrentUserCompany(){
        global $USER;

        $company = CompaniesSchemaTable::getRow(array('select'=>array("ID"),'filter'=>array("OWNER_ID"=>$USER->GetId())));

        return isset($company['ID']) ? $company['ID'] : null;
    }



    public static function createCompanyForCurrentUser(){
        global $USER;

        if(!self::hasCurrentUserHasComany() && $USER->GetId()){
            $res = CompaniesSchemaTable::add(['OWNER_ID'=>$USER->GetId()]);
            return $res->isSuccess() ? true :false;
        }
    }
}