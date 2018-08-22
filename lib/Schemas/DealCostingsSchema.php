<?php

namespace Ali\Logistic\Schemas;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\Application;

class DealCostingsSchemaTable extends Entity\DataManager
{   

    public static function getTableName()
    {
        return 'ali_logistic_deal_costings';
    }



    public static function getMap()
    {
        return array(
            //ID
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            
            new Entity\StringField('INTEGRATED_ID'),

            new Entity\IntegerField('DEAL_ID'),

            new Entity\ReferenceField(
                'DEAL',
                '\Ali\Logistic\Deals',
                array('=this.DEAL_ID' => 'ref.ID'),
                array('join_type' => 'INNER')
            ),


            new Entity\StringField('KIND_SERVICE',array(
                'title'=>'Вид услуги',
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),


            new Entity\FloatField('COST',array(
                'title'=>'Цена',
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return $value;
                        }
                    );
                }
            )),

            new Entity\IntegerField('QUANTITY',array(
                'title'=>'Количество',
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return $value;
                        }
                    );
                }
            )),

            new Entity\FloatField('AMOUNT',array(
                'title'=>'Сумма',
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return $value;
                        }
                    );
                }
            )),


            new Entity\StringField('FILE',array(
                'title'=>'Файл',
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
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


}