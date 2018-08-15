<?php

namespace Ali\Logistic;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;


class CompanyEmployeeTable extends Entity\DataManager{



	public static function getTableName()
    {
        return 'ali_logistic_company_employee';
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


            new Entity\IntegerField('EMPLOYEE_ID'),

            new Entity\ReferenceField(
                'EMPLOYEE',
                '\Bitrix\Main\UserTable',
                array('=this.EMPLOYEE_ID' => 'ref.ID'),
                array('join_type' => 'INNER')
            ),



        );
    }
}