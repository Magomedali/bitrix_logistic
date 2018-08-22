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
                '\Ali\Logistic\Schemas\CompaniesSchemaTable',
                array('=this.COMPANY_ID' => 'ref.ID'),
                array('join_type' => 'INNER')
            ),


            new Entity\IntegerField('CONTRACTOR_ID'),


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
                'title'=>'Вес груза',
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
                'title'=>'Объем (куб. м.)',
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
                'title'=>'Ширина (м)',
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
                'title'=>'Высота (м)',
                'required'=>true,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return $value;
                        }
                    );
                }
            )),

            new Entity\FloatField('LENGTH',array(
                'title'=>'Длина (м)',
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
                    return 0;
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
                    return 0;
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
                    return 0;
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
                'title'=>'Темп. до',
                'default_value'=>function(){
                    return 0;
                }
            )),

            
            new Entity\BooleanField('SUPPORT_REQUIRED',array(
                'title'=>'Требуется сопровождение',
                'default_value'=>function(){
                    return 0;
                }
            )),



            new Entity\BooleanField('ADDITIONAL_EQUIPMENT',array(
                'title'=>'Требуются дополнительные оборудования',
                'default_value'=>function(){
                    return 0;
                }
            )),

            new Entity\BooleanField('ADDITIONAL_EQUIPMENT_CONICS',array(
                'title'=>'Требуются коники',
                'default_value'=>function(){
                    return 0;
                }
            )),

            new Entity\BooleanField('ADDITIONAL_EQUIPMENT_RAMPS',array(
                'title'=>'Требуются аппарели',
                'default_value'=>function(){
                    return 0;
                }
            )),


            new Entity\BooleanField('ADDITIONAL_EQUIPMENT_TAIL_LIFT',array(
                'title'=>'Требуются гидроборт',
                'default_value'=>function(){
                    return 0;
                }
            )),


            new Entity\BooleanField('ADDITIONAL_EQUIPMENT_MANIPULATOR',array(
                'title'=>'Требуются  манипулятор',
                'default_value'=>function(){
                    return 0;
                }
            )),


            new Entity\BooleanField('ADDITIONAL_EQUIPMENT_WRECKER',array(
                'title'=>'Требуются эвакуатор',
                'default_value'=>function(){
                    return 0;
                }
            )),

            new Entity\BooleanField('ADDITIONAL_EQUIPMENT_CRANE',array(
                'title'=>'Требуется кран',
                'default_value'=>function(){
                    return 0;
                }
            )),


            new Entity\BooleanField('REQUIRED_DOCUMENTS',array(
                'title'=>'Требуется документы',
                'default_value'=>function(){
                    return 0;
                }
            )),


            new Entity\BooleanField('REQUIRED_DOCUMENTS_PROCURATION',array(
                'title'=>'Требуется доверенность',
                'default_value'=>function(){
                    return 0;
                }
            )),

            new Entity\BooleanField('REQUIRED_DOCUMENTS_MEDICAL_BOOK',array(
                'title'=>'Требуется медкнижка',
                'default_value'=>function(){
                    return 0;
                }
            )),

            new Entity\BooleanField('REQUIRED_DOCUMENTS_SANITIZATION',array(
                'title'=>'Требуется санобработка',
                'default_value'=>function(){
                    return 0;
                }
            )),



            new Entity\BooleanField('WITH_NDS',array(
                'title'=>'С НДС',
                'default_value'=>function(){
                    return 0;
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
                                return "Недопустимое значение типа!";
                            }

                            return true;
                        }
                    );
                }
            )),


            new Entity\BooleanField('IS_DELETED',array(
                'title'=>'В архиве',
                'default_value'=>function(){
                    return 0;
                }
            )),

            new Entity\BooleanField('IS_ACTIVE',array(
                'title'=>'Активна',
                'default_value'=>function(){
                    return 1;
                }
            )),

            new Entity\BooleanField('COMPLETED',array(
                'title'=>'Завершена',
                'default_value'=>function(){
                    return 0;
                }
            )),


            new Entity\BooleanField('IS_DRAFT',array(
                'title'=>'Черновик',
                'default_value'=>function(){
                    return 0;
                }
            )),



            new Entity\StringField('FILE_BILL',array(
                'title'=>'Счет',
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),


            new Entity\StringField('FILE_INVOICE',array(
                'title'=>'Счет фактура',
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),

            new Entity\StringField('FILE_AСT',array(
                'title'=>'Акт',
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),


            new Entity\StringField('FILE_CONTRACT',array(
                'title'=>'Договор',
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),



            new Entity\StringField('FILE_DRIVER_ATTORNEY',array(
                'title'=>'Доверенность на водителя',
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),



            new Entity\StringField('FILE_PRINT_FORM',array(
                'title'=>'Печатная форма заявки',
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