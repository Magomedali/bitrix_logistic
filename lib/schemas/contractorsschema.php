<?php


namespace Ali\Logistic\Schemas;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Entity\Result;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;
use Ali\Logistic\Dictionary\ContractorsType;
use Ali\Logistic\User;
use Ali\Logistic\CompaniesTable;
use Ali\Logistic\soap\clients\Contractors1C;

class ContractorsSchemaTable extends Entity\DataManager
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
            
            
            new Entity\BooleanField('IS_INTEGRATED',array(
                'title'=>'Интегрирован в 1С',
                'default_value'=>function(){
                    return 0;
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

             new Entity\BooleanField('INTEGRATE_ERROR', array(
                'title'=>'Ошибка интеграции в 1С',
                'default_value'=>function(){
                    return 0;
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

            new Entity\IntegerField('COMPANY_ID',array(
                'required'=>false,
            )),


            new Entity\ReferenceField(
                'COMPANY',
                '\Ali\Logistic\Schemas\CompaniesSchemaTable',
                array('=this.COMPANY_ID' => 'ref.ID'),
                array('join_type' => 'INNER')
            ),
            
            new Entity\IntegerField('OWNER_ID',array(
                'required'=>false,
            )),

            new Entity\ReferenceField(
                'OWNER',
                '\Bitrix\Main\UserTable',
                array('=this.OWNER_ID' => 'ref.ID'),
                array('join_type' => 'INNER')
            ),

            new Entity\StringField('NAME',array(
                'title'=>'Наименование организации',
                'required'=>true,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),


            new Entity\StringField('LEGAL_ADDRESS',array(
                'title'=>'Юридический адрес',
                'required'=>true,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),


            new Entity\StringField('PHYSICAL_ADDRESS',array(
                'title'=>'Физический организации',
                'required'=>false,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),


            new Entity\IntegerField('ENTITY_TYPE',array(
                'title'=>'Вид организации',
                'required'=>true,
                'default_value'=>function(){
                    return \Ali\Logistic\Dictionary\ContractorsType::IP;
                },
                'validation'=>function(){
                    return array(
                        function($v,$pr,$row,$f){

                            $types = \Ali\Logistic\Dictionary\ContractorsType::getLabels();
                            if(array_key_exists($v, $types) == false){
                                return "Недопустимое значение типа!";
                            }

                            return true;
                        }
                    );
                }
            )),


            new Entity\StringField('INN',array(
                'title'=>'ИНН',
                'required'=>1,
                'unique'=>1,
                'validation'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            $length = strlen($value);
                            $validLeng = $row['ENTITY_TYPE'] == \Ali\Logistic\Dictionary\ContractorsType::IP ? 12 : 10; 
                            
                            if($length != $validLeng){
                                $msg = $row['ENTITY_TYPE'] == \Ali\Logistic\Dictionary\ContractorsType::LEGAL ? "Юр.лицо" : "ИП";
                                return "Неправильный формат ИНН для ".$msg.". Номер должен состоять из ".$validLeng." цифр";
                            }

                            if(!isset($row['INTEGRATED_ID']) || empty($row['INTEGRATED_ID']) || $row['INTEGRATED_ID'] == ''){
                                
                                $state = \Ali\Logistic\soap\clients\CheckINN::check($value);
                                if(!$state['success']){
                                    return $state['msg'];
                                }

                                //Проверка на уникальность ИНН и КПП
                                if($row['ENTITY_TYPE'] == \Ali\Logistic\Dictionary\ContractorsType::LEGAL){
                                    
                                    if($primary){
                                        $id = is_array($primary) ? reset($primary) : (int)$primary;
                                        $c = ContractorsSchemaTable::getRow(['select'=>['ID'],'filter'=>['INN'=>$row['INN'],'KPP'=>$row['KPP'],'!=ID'=>$id]]);
                                    }else{
                                        $c = ContractorsSchemaTable::getRow(['select'=>['ID'],'filter'=>['INN'=>$row['INN'],'KPP'=>$row['KPP']]]);
                                    }

                                    if(isset($c['ID']) && $c['ID']){
                                        return 'Юр. лицо с таким ИНН и КПП зарегистрирован на сайте';
                                    }
                                }else{

                                    if($primary){
                                        $id = is_array($primary) ? reset($primary) : (int)$primary;
                                        $c = ContractorsSchemaTable::getRow(['select'=>['ID'],'filter'=>['INN'=>$row['INN'],'!=ID'=>$row['ID']]]);
                                    }else{
                                        $c = ContractorsSchemaTable::getRow(['select'=>['ID'],'filter'=>['INN'=>$row['INN']]]);
                                    }
                                    
                                    if(isset($c['ID']) && $c['ID']){
                                        return 'ИП с таким ИНН зарегистрирован на сайте';
                                    }
                                }
                                
                            }
                                

                            return true;
                        }
                    );
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),

            new Entity\StringField('KPP',array(
                'title'=>'КПП',
                'unique'=>1,
                'default_value'=>function(){
                    return null;
                },
                'validation'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            $validLeng = 9;
                            $len = strlen($value);
                            if($len){
                                $type = $row['ENTITY_TYPE'] == \Ali\Logistic\Dictionary\ContractorsType::IP;
                            
                                if($row['ENTITY_TYPE'] == \Ali\Logistic\Dictionary\ContractorsType::IP && $len > 0){
                                    return "У ИП должен отсутствовать код КПП";
                                }

                                if($row['ENTITY_TYPE'] == \Ali\Logistic\Dictionary\ContractorsType::LEGAL){
                                    //Для юр лиц обязателен и должен быть уникальным
                                    //$field->addValidator(new \Bitrix\Main\Entity\Validator\Unique('Организация с таким КПП зарегистрирован на сайте'));
                                }
                            }
                            
                            if($row['ENTITY_TYPE'] == \Ali\Logistic\Dictionary\ContractorsType::LEGAL && $len != $validLeng){
                                return "Код КПП для юр.лиц должен состоять из ".$validLeng." цифр.";
                            }

                            return true;
                        }
                        
                    );
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),

            new Entity\StringField('OGRN',array(
                'title'=>'ОГРН',
                'required'=>false,
                'unique'=>1,
                'validation'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            $length = strlen($value);
                            if($length){
                                $validLeng = $row['ENTITY_TYPE'] == \Ali\Logistic\Dictionary\ContractorsType::IP ? 15 : 13; 
                                if($length != $validLeng){
                                    $msg = $row['ENTITY_TYPE'] == \Ali\Logistic\Dictionary\ContractorsType::LEGAL ? "Юр.лицо" : "ИП";
                                    return "Неправильный формат ОГРН для ".$msg.". Номер должен состоять из ".$validLeng." цифр";
                                }
                            }
                            
                            return true;
                        },
                    );
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),

            new Entity\StringField('BANK_BIK',array(
                'title'=>'Бик банка',
                'required'=>false,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),

            new Entity\StringField('BANK_NAME',array(
                'title'=>'Наименование банка',
                'required'=>false,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),

            new Entity\StringField('CHECKING_ACCOUNT',array(
                'title'=>'Расчетный счет',
                'required'=>false,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),

            new Entity\StringField('CORRESPONDENT_ACCOUNT',array(
                'title'=>'Корреспондентский счет',
                'required'=>false,
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
            ))


        );
    }

}