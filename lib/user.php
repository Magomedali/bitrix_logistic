<?php


namespace Ali\Logistic;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;
use \Bitrix\Main\Entity\Query;
use Ali\Logistic\Schemas\CompaniesSchemaTable;

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
}