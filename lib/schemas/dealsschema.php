<?php

namespace Ali\Logistic\Schemas;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;


class DealsSchemaTable extends Entity\DataManager{



	public static function getTableName()
    {
        return 'ali_logistic_deals';
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
                'title'=>'Интегрирована в 1С',
                'default_value'=>function(){
                    return false;
                }
            )),

            //INTEGRATED_ID
            new Entity\StringField('INTEGRATED_ID', array(
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return $value;
                        }
                    );
                }
            )),

            //DOC_NUMBER
            new Entity\StringField('DOCUMENT_NUMBER', array(
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return $value;
                        }
                    );
                }
            )),

            new Entity\BooleanField('INTEGRATE_ERROR', array(
                'title'=>'Ошибка интеграции в 1С',
                'default_value'=>function(){
                    return false;
                }
            )),

            new Entity\TextField('INTEGRATE_ERROR_MSG', array(
                'title'=>'Описание ошибки интеграции в 1С',
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return $value;
                        }
                    );
                }
            )),
            



            new Entity\IntegerField('CONTRACTOR_ID',array(
                'title'=>'Контрагент',
                'required'=>true,
            )),


            new Entity\ReferenceField(
                'CONTRACTOR',
                '\Ali\Logistic\Schemas\ContractorsSchemaTable',
                array('=this.CONTRACTOR_ID' => 'ref.ID'),
                array('join_type' => 'INNER')
            ),


            new Entity\IntegerField('OWNER_ID'),

            new Entity\ReferenceField(
                'OWNER',
                '\Bitrix\Main\UserTable',
                array('=this.OWNER_ID' => 'ref.ID'),
                array('join_type' => 'INNER')
            ),



            new Entity\StringField('NAME',array(
                'title'=>'Наименование груза',
                'required'=>true,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),

            new Entity\FloatField('WEIGHT',array(
                'title'=>'Вес груза',
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

            new Entity\FloatField('SPACE',array(
                'title'=>'Объем (куб. м.)',
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

            new Entity\FloatField('WIDTH',array(
                'title'=>'Ширина (м)',
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

            new Entity\FloatField('HEIGHT',array(
                'title'=>'Высота (м)',
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

            new Entity\FloatField('LENGTH',array(
                'title'=>'Длина (м)',
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

            new Entity\TextField('TYPE_OF_VEHICLE',array(
                'title'=>'Тип транспортного средства',
                'required'=>false,
                'default_value'=>function(){
                    return "";
                },
                'validation'=>function(){
                    return array(
                        function($v,$pr,$row,$f){

                            // $types = \Ali\Logistic\Dictionary\TypeOfVehicle::getLabels();
                            
                            // if(array_key_exists($v, $types) == false){
                            //     return "Недопустимое значение типа!";
                            // }

                            return true;
                        }
                    );
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return strlen($value) ? $value : "";
                        }
                    );
                }
            )),


            new Entity\TextField('LOADING_METHOD',array(
                'title'=>'Способ погрузки',
                'required'=>false,
                'default_value'=>function(){
                    return "";
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return strlen($value) ? $value : "";
                        }
                    );
                }
            )),


            new Entity\TextField('UNLOADING_METHOD',array(
                'title'=>'Способ разгрузки',
                'required'=>false,
                'default_value'=>function(){
                    return "";
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return strlen($value) ? $value : "";
                        }
                    );
                }
            )),


            new Entity\IntegerField('WAY_OF_TRANSPORTATION',array(
                'title'=>'Способ перевозки',
                'required'=>true,
                'default_value'=>function(){
                    return $v ? $v : 0;
                },
                'validation'=>function(){
                    return array(
                        function($v,$pr,$row,$f){

                            if($v){
                               $types = \Ali\Logistic\Dictionary\WayOfTransportation::getLabels();
                                if(array_key_exists($v, $types) == false){
                                    return "Недопустимое значение для 'Способ перевозки'!";
                                }
                            }
                            

                            return true;
                        }
                    );
                }
            )),


            

            new Entity\BooleanField('REQUIRES_LOADER',array(
                'title'=>'Требуется грузчик',
                'default_value'=>function(){
                    return false;
                }
            )),

            new Entity\IntegerField('COUNT_LOADERS',array(
                'title'=>'Количество грузчиков',
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

            new Entity\IntegerField('COUNT_HOURS',array(
                'title'=>'Количество часов',
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


            new Entity\BooleanField('REQUIRES_INSURANCE',array(
                'title'=>'Требуется страхование',
                'default_value'=>function(){
                    return false;
                }
            )),


            new Entity\IntegerField('REQUIRES_TEMPERATURE_FROM',array(
                'title'=>'Темп. от',
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



            new Entity\IntegerField('REQUIRES_TEMPERATURE_TO',array(
                'title'=>'Темп. до',
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

            
            new Entity\BooleanField('SUPPORT_REQUIRED',array(
                'title'=>'Требуется сопровождение',
                'default_value'=>function(){
                    return false;
                }
            )),

            new Entity\BooleanField('CARGO_HANDLING',array(
                'title'=>'Погрузо-разгрузочные работы',
                'default_value'=>function(){
                    return false;
                }
            )),

            new Entity\BooleanField('SECURE_STORAGE',array(
                'title'=>'Ответственное хранение',
                'default_value'=>function(){
                    return false;
                }
            )),

            new Entity\BooleanField('CROSS_DOCKING',array(
                'title'=>'Кросс-докинг',
                'default_value'=>function(){
                    return false;
                }
            )),


            new Entity\BooleanField('ARMED_ESCORT',array(
                'title'=>'Вооруженное сопровождение',
                'default_value'=>function(){
                    return false;
                }
            )),



            new Entity\TextField('ADDITIONAL_EQUIPMENT',array(
                'title'=>'Требуются дополнительные оборудования',
                'required'=>false,
                'default_value'=>function(){
                    return "";
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return strlen($value) ? $value : "";
                        }
                    );
                }
            )),



            new Entity\TextField('SPECIAL_EQUIPMENT',array(
                'title'=>'Специальное оборудования',
                'default_value'=>function(){
                    return "";
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return strlen($value) ? $value : "";
                        }
                    );
                }
            )),

            


            new Entity\TextField('REQUIRED_DOCUMENTS',array(
                'title'=>'Требуется документы',
                'required'=>false,
                'default_value'=>function(){
                    return false;
                },
                'validation'=>function(){
                    return array(
                        function($v,$pr,$row,$f){

                            // $types = \Ali\Logistic\Dictionary\Documents::getLabels();
                            // if(array_key_exists($v, $types) == false){
                            //     return "Недопустимое значение типа!";
                            // }

                            return true;
                        }
                    );
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return strlen($value) ? $value : "";
                        }
                    );
                }
            )),



            new Entity\TextField('HOW_PACKED',array(
                'title'=>'Как упакован',
                'default_value'=>function(){
                    return false;
                },
                'validation'=>function(){
                    return array(
                        function($v,$pr,$row,$f){

                            return true;
                        }
                    );
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return strlen($value) ? $value : "";
                        }
                    );
                }
            )),



            new Entity\BooleanField('WITH_NDS',array(
                'title'=>'С НДС',
                'default_value'=>function(){
                    return 0;
                }
            )),

            new Entity\FloatField('SUM',array(
                'title'=>'Стоимость',
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

            new Entity\IntegerField('STATE',array(
                'title'=>'Статус',
                'default_value'=>function(){
                    return \Ali\Logistic\Dictionary\DealStates::CREATED;
                },
                'validation'=>function(){
                    return array(
                        function($v,$pr,$row,$f){

                            $types = \Ali\Logistic\Dictionary\DealStates::getLabels();
                            if(array_key_exists($v, $types) == false){
                                return "Недопустимое значение Статуса!";
                            }

                            return true;
                        }
                    );
                }
            )),


            new Entity\IntegerField('COUNT_PLACE',array(
                'title'=>'Количество мест',
                'default_value'=>function(){
                    return 0;
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return (int)$value;
                        }
                    );
                }
            )),



            new Entity\IntegerField('ADR_CLASS',array(
                'title'=>'Адр класс',
                'default_value'=>function(){
                    return 0;
                },
                'validation'=>function(){
                    return array(
                        function($v,$pr,$row,$f){

                           
                            if((int)$v && !((int)$v >= 1 && (int)$v <= 10)){
                                return "Недопустимое значение 'Адр класс', Адр класс может принимать значение только от 1 до 10!";
                            }

                            return true;
                        }
                    );
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return (int)$value;
                        }
                    );
                }
            )),



            new Entity\BooleanField('REQUIRED_RUSSIAN_DRIVER',array(
                'title'=>'Водитель гражданин России',
                'default_value'=>function(){
                    return false;
                }
            )),



            new Entity\StringField('DRIVER_INFO',array(
                'title'=>'Водитель',
                'required'=>false,
                'default_value'=>function(){
                    return "";
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),


            new Entity\StringField('VEHICLE',array(
                'title'=>'Транспортное средство',
                'required'=>false,
                'default_value'=>function(){
                    return "";
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),


            new Entity\StringField('LOADING_PLACE',array(
                'title'=>'Место погрузки',
                'required'=>false,
                'default_value'=>function(){
                    return "";
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),


            new Entity\StringField('UNLOADING_PLACE',array(
                'title'=>'Место разгрузки',
                'required'=>false,
                'default_value'=>function(){
                    return "";
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),


            new Entity\StringField('PRINT_FORM',array(
                'title'=>'Печатная форма',
                'required'=>false,
                'default_value'=>function(){
                    return "";
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),

            new Entity\TextField('COMMENTS',array(
                'title'=>'Комментарии',
                'required'=>false,
                'default_value'=>function(){
                    return "";
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return $value ? trim(strip_tags($value)) : "";
                        }
                    );
                }
            )),

            new Entity\BooleanField('IS_DELETED',array(
                'title'=>'В архиве',
                'default_value'=>function(){
                    return false;
                }
            )),

            new Entity\BooleanField('IS_ACTIVE',array(
                'title'=>'Активна',
                'default_value'=>function(){
                    return false;
                }
            )),

            new Entity\BooleanField('COMPLETED',array(
                'title'=>'Завершена',
                'default_value'=>function(){
                    return false;
                }
            )),


            new Entity\BooleanField('IS_DRAFT',array(
                'title'=>'Черновик',
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