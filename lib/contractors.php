<?php


namespace Ali\Logistic;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;
use Ali\Logistic\ContractorsType;


class ContractorsTable extends Entity\DataManager
{   

    public static function getTableName()
    {
        return 'ali_logistic_contractors';
    }



    public static function getMap()
    {
        return array(
            //ID
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            
            new Entity\IntegerField('COMPANY_ID'),

            new Entity\ReferenceField(
                'COMPANY',
                '\Ali\Logistic\CompaniesTable',
                array('=this.COMPANY_ID' => 'ref.ID'),
                array('join_type' => 'INNER')
            ),
            
            new Entity\IntegerField('OWNER_ID'),

            new Entity\ReferenceField(
                'OWNER',
                '\Bitrix\Main\UserTable',
                array('=this.OWNER_ID' => 'ref.ID'),
                array('join_type' => 'INNER')
            ),

            new Entity\StringField('NAME'),
            new Entity\StringField('LEGAL_ADDRESS'),
            new Entity\StringField('PHYSICAL_ADDRESS'),
            new Entity\IntegerField('ENTITY_TYPE',array('default_value'=>ContractorsType::IP)),


            new Entity\IntegerField('INN'),

            new Entity\IntegerField('KPP'),

            new Entity\IntegerField('OGRN'),

            new Entity\StringField('BANK_BIK'),
            new Entity\StringField('BANK_NAME'),
            new Entity\StringField('CHECKING_ACCOUNT'),
            new Entity\StringField('CORRESPONDENT_ACCOUNT'),
        );
    }



}