<?php

namespace Ali\Logistic\Schemas;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;

class SettingsSchemaTable extends Entity\DataManager
{   

    public static function getTableName()
    {
        return 'ali_logistic_settings';
    }



    public static function getMap()
    {
        return array(
            //ID
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            
            
            new Entity\StringField('NAME',array(
                'title'=>'Имя параметра',
                'required'=>1,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return $value ? trim(strip_tags($value)) : "";
                        }
                    );
                }
            )),
           

            new Entity\TextField('VALUE',array(
                'title'=>'Значение',
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return $value ? $value : "";
                        }
                    );
                }
            )),
            

            new Entity\DatetimeField('CREATED_AT',array(
                'default_value'=>function(){
                    return new \Bitrix\Main\Type\DateTime();
                }
            )),


            new Entity\DatetimeField('UPDATED_AT',array(
                'default_value'=>function(){
                    return new \Bitrix\Main\Type\DateTime();
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return new \Bitrix\Main\Type\DateTime();
                        }
                    );
                }
            )),
        );
    }




    public static function defaultParameters(){


        $sql = <<<SQL
            INSERT INTO `ali_logistic_settings` (`ID`, `NAME`, `VALUE`, `CREATED_AT`, `UPDATED_AT`) VALUES
            (1, 'contract_nds', 'contract_nds.doc', '2018-09-05 00:00:00', '2018-09-05 00:00:00'),
            (2, 'contract_nonds', 'contract_nonds.docx', '2018-09-05 00:00:00', '2018-09-05 00:00:00');
SQL;
        
        
        global $DB;


        $DB->query($sql);
        
    }
}