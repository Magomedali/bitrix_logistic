<?php


namespace Ali\Logistic;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;

class CompaniesTable extends Entity\DataManager
{   

    public static function getTableName()
    {
        return 'ali_logistic_companies';
    }



    public static function getMap()
    {
        return array(
            //ID
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            
            new Entity\IntegerField('OWNER_ID'),

            new Entity\ReferenceField(
                'OWNER',
                '\Bitrix\Main\UserTable',
                array('=this.OWNER_ID' => 'ref.ID'),
                array('join_type' => 'INNER')
            )
        );
    }



    public static function hasCurrentUserHasComany(){
        return self::getCurrentUserCompany();
    }



    public static function getCurrentUserCompany(){
        global $USER;

        $company = self::getRow(array('select'=>array("ID"),'filter'=>array("OWNER_ID"=>$USER->GetId())));

        return isset($company['ID']) ? $company['ID'] : null;
    }



    public static function createCompanyForCurrentUser(){
        global $USER;

        if(!self::hasCurrentUserHasComany() && $USER->GetId()){
            $res = self::add(['OWNER_ID'=>$USER->GetId()]);
            return $res->isSuccess() ? true :false;
        }
    }
}