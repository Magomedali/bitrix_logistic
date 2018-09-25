<?php

namespace Ali\Logistic\Schemas;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;

class DealFilesSchemaTable extends Entity\DataManager
{   

    public static function getTableName()
    {
        return 'ali_logistic_deal_files';
    }



    public static function getMap()
    {
        return array(

            //ID
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),

            
            new Entity\IntegerField('DEAL_ID'),


            new Entity\ReferenceField(
                'DEAL',
                '\Ali\Logistic\Schemas\DealsSchemaTable',
                array('=this.DEAL_ID' => 'ref.ID'),
                array('join_type' => 'INNER')
            ),


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


            new Entity\FloatField('SUM',array(
                'title'=>'Сумма счета',
                'required'=>false,
                'default_value'=>function(){
                    return 0;
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return $value ? $value : 0 ;
                        }
                    );
                }
            )),


            new Entity\FloatField('SUM_PAID',array(
                'title'=>'Сумма оплаты',
                'required'=>false,
                'default_value'=>function(){
                    return 0;
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return $value ? $value : 0 ;
                        }
                    );
                }
            )),


            new Entity\StringField('FILE_NUMBER',array(
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


            new Entity\DatetimeField('FILE_DATE',array(
                'default_value'=>function(){
                    return new \Bitrix\Main\Type\DateTime();
                }
            )),


            new Entity\IntegerField('FILE_TYPE',array(
                'title'=>'Тип файла',
                'default_value'=>function(){
                    return \Ali\Logistic\Dictionary\DealFileType::FILE_BILL;
                },
                'validation'=>function(){
                    return array(
                        function($v,$pr,$row,$f){

                            $types = \Ali\Logistic\Dictionary\DealFileType::getLabels();
                            if(array_key_exists($v, $types) == false){
                                return "Недопустимое значение типа!";
                            }

                            return true;
                        }
                    );
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


            new Entity\DatetimeField('CREATED_AT',array(
                'default_value'=>function(){
                    return new \Bitrix\Main\Type\DateTime();
                }
            )),


            new Entity\DatetimeField('PAID_AT',array(
                'default_value'=>function(){
                    return null;
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

            new Entity\ExpressionField('COUNT_FILE',
                'COUNT(1)'
            )
        );
    }


}