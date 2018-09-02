<?php

namespace Ali\Logistic\Schemas;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;

class ReviseSchemaTable extends Entity\DataManager
{   

    public static function getTableName()
    {
        return 'ali_logistic_revises';
    }



    public static function getMap()
    {
        return array(
            //ID
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            
            new Entity\IntegerField('CONTRACTOR_ID'),

            new Entity\ReferenceField(
                'CONTRACTOR',
                '\Ali\Logistic\Schemas\ContractorsSchemaTable',
                array('=this.DEAL_ID' => 'ref.ID'),
                array('join_type' => 'INNER')
            ),


            new Entity\StringField('CONTRACTOR_UUID',array(
                'required'=>false,
                'default_value'=>function(){
                    return "";
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return strlen($value) ? $value : '';
                        }
                    );
                }
            )),



            new Entity\DatetimeField('DATE_START',array(
                'default_value'=>function(){
                    return new \Bitrix\Main\Type\DateTime();
                }
            )),


            new Entity\DatetimeField('DATE_FINISH',array(
                'default_value'=>function(){
                    return new \Bitrix\Main\Type\DateTime();
                }
            )),

            new Entity\StringField('FILE',array(
                'title'=>'Акт',
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),


            new Entity\BooleanField('WITH_NDS',array(
                'title'=>'С НДС',
                'default_value'=>function(){
                    return false;
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


}