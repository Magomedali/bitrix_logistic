<?php

namespace Ali\Logistic;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;


class DealsTable extends Entity\DataManager{



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
            


            //INTEGRATED_ID
            new Entity\IntegerField('INTEGRATED_ID', array(
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return (int)$value;
                        }
                    );
                }
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



            new Entity\StringField('NAME',array(
                'title'=>'Наименование',
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
                'title'=>'Вес',
                'required'=>true,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return $value;
                        }
                    );
                }
            )),

            new Entity\FloatField('SPACE',array(
                'title'=>'Объем',
                'required'=>true,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return $value;
                        }
                    );
                }
            )),

            new Entity\FloatField('WIDTH',array(
                'title'=>'Ширина',
                'required'=>true,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return $value;
                        }
                    );
                }
            )),

            new Entity\FloatField('HEIGHT',array(
                'title'=>'Высота',
                'required'=>true,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return $value;
                        }
                    );
                }
            )),

            new Entity\IntegerField('TYPE_OF_VEHICLE',array(
                'title'=>'Тип транспортного средства',
                'required'=>true,
                'default_value'=>function(){
                    return \Ali\Logistic\Dictionary\TypeOfVehicle::AWNING;
                },
                'validation'=>function(){
                    return array(
                        function($v,$pr,$row,$f){

                            $types = \Ali\Logistic\Dictionary\TypeOfVehicle::getLabels();
                            if(array_key_exists($v, $types) == false){
                                return "Недопустимое значение типа!";
                            }

                            return true;
                        }
                    );
                }
            )),


            new Entity\IntegerField('LOADING_METHOD',array(
                'title'=>'Способ погрузки',
                'required'=>true,
                'default_value'=>function(){
                    return \Ali\Logistic\Dictionary\LoadingMethod::TOP;
                },
                'validation'=>function(){
                    return array(
                        function($v,$pr,$row,$f){

                            $types = \Ali\Logistic\Dictionary\LoadingMethod::getLabels();
                            if(array_key_exists($v, $types) == false){
                                return "Недопустимое значение типа!";
                            }

                            return true;
                        }
                    );
                }
            )),


            new Entity\IntegerField('WAY_OF_TRANSPORTATION',array(
                'title'=>'Способ перевозки',
                'required'=>true,
                'default_value'=>function(){
                    return \Ali\Logistic\Dictionary\WayOfTransportation::AWNING;
                },
                'validation'=>function(){
                    return array(
                        function($v,$pr,$row,$f){

                            $types = \Ali\Logistic\Dictionary\WayOfTransportation::getLabels();
                            if(array_key_exists($v, $types) == false){
                                return "Недопустимое значение типа!";
                            }

                            return true;
                        }
                    );
                }
            )),


            

            new Entity\BooleanField('REQUIRES_LOADER',array(
                'title'=>'Требуется грузчик',
                'default_value'=>function(){
                    return 0;
                }
            )),

            new Entity\IntegerField('COUNT_LOADERS',array(
                'title'=>'Количество грузчиков',
                'default_value'=>function(){
                    return 0;
                }
            )),

            new Entity\IntegerField('COUNT_HOURS',array(
                'title'=>'Количество часов',
                'default_value'=>function(){
                    return 0;
                }
            )),


            new Entity\BooleanField('REQUIRES_INSURANCE',array(
                'title'=>'Требуется страхование',
                'default_value'=>function(){
                    return 0;
                }
            )),


            new Entity\IntegerField('REQUIRES_TEMPERATURE_FROM',array(
                'title'=>'Темп. от',
                'default_value'=>function(){
                    return 0;
                }
            )),



            new Entity\IntegerField('REQUIRES_TEMPERATURE_TO',array(
                'title'=>'Темп. от',
                'default_value'=>function(){
                    return 0;
                }
            )),



            new Entity\IntegerField('ADDITIONAL_EQUIPMENT',array(
                'title'=>'Требуются дополнительные оборудования',
                'validation'=>function(){
                    return array(
                        function($v,$pr,$row,$f){

                            $types = \Ali\Logistic\Dictionary\AdditionalEquipment::getLabels();
                            if(array_key_exists($v, $types) == false){
                                return "Недопустимое значение типа!";
                            }

                            return true;
                        }
                    );
                }
            )),



        );
    }
}