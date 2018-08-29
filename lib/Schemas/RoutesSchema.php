<?php

namespace Ali\Logistic\Schemas;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\Application;

class RoutesSchemaTable extends Entity\DataManager
{   

    public static function getTableName()
    {
        return 'ali_logistic_deal_routes';
    }



    public static function getMap()
    {
        return array(
            //ID
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            
            new Entity\BooleanField('IS_INTEGRATED',array(
                'title'=>'Интегрирован в 1С',
                'default_value'=>function(){
                    return 0;
                }
            )),
            
            new Entity\StringField('INTEGRATED_ID',array(
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

            new Entity\IntegerField('DEAL_ID'),

            new Entity\ReferenceField(
                'DEAL',
                '\Ali\Logistic\Deals',
                array('=this.DEAL_ID' => 'ref.ID'),
                array('join_type' => 'INNER')
            ),


            new Entity\IntegerField('KIND',array(
                'title'=>'Тип маршрута',
                'required'=>true,
                'default_value'=>function(){
                    return \Ali\Logistic\Dictionary\RoutesKind::LOADING;
                },
                'validation'=>function(){
                    return array(
                        function($v,$pr,$row,$f){

                            $types = \Ali\Logistic\Dictionary\RoutesKind::getLabels();
                            if(array_key_exists($v, $types) == false){
                                return "Недопустимое значение 'Тип маршрута' ".$v.". Возможные значения(".implode(",", $types).")!";
                            }

                            return true;
                        }
                    );
                }
            )),

            new Entity\DatetimeField('START_AT',array(
                'title'=>'Время начала',
                'required'=>true,
                'default_value'=>function(){
                    return new \Bitrix\Main\Type\DateTime();
                }
            )),


            new Entity\DatetimeField('FINISH_AT',array(
                'title'=>'Время окончания',
                'required'=>true,
                'default_value'=>function(){
                    return new \Bitrix\Main\Type\DateTime();
                }
            )),

            new Entity\TextField('ADDRESS',array(
                'title'=>'Адрес',
                'required'=>true,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),


            new Entity\StringField('COMMENT',array(
                'title'=>'Комментарий',
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),

            new Entity\StringField('ORGANISATION',array(
                'title'=>'Организация',
                'required'=>true,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),

            new Entity\StringField('PERSON',array(
                'title'=>'Контактное лицо',
                'required'=>true,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),

            new Entity\StringField('PHONE',array(
                'title'=>'Телефон',
                'required'=>true,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),

            new Entity\IntegerField('OWNER_ID'),

            new Entity\ReferenceField(
                'OWNER',
                '\Bitrix\Main\UserTable',
                array('=this.OWNER_ID' => 'ref.ID'),
                array('join_type' => 'INNER')
            ),

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