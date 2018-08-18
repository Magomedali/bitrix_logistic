<?php


namespace Ali\Logistic;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Entity\Result;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;
use Ali\Logistic\ContractorsType;
use Ali\Logistic\User;
use Ali\Logistic\CompaniesTable;

class ContractorsTable extends Entity\DataManager
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
            
            //ID
            new Entity\IntegerField('INTEGRATED_ID', array(
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return (int)$value;
                        }
                    );
                }
            )),

            new Entity\IntegerField('COMPANY_ID',array(
                'required'=>true,
            )),


            new Entity\ReferenceField(
                'COMPANY',
                '\Ali\Logistic\CompaniesTable',
                array('=this.COMPANY_ID' => 'ref.ID'),
                array('join_type' => 'INNER')
            ),
            
            new Entity\IntegerField('OWNER_ID',array(
                'required'=>true,
            )),

            new Entity\ReferenceField(
                'OWNER',
                '\Bitrix\Main\UserTable',
                array('=this.OWNER_ID' => 'ref.ID'),
                array('join_type' => 'INNER')
            ),

            new Entity\StringField('NAME',array(
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
                'required'=>true,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),


            new Entity\IntegerField('ENTITY_TYPE',array(
                'required'=>true,
                'default_value'=>function(){
                    return \Ali\Logistic\ContractorsType::IP;
                },
                'validation'=>function(){
                    return array(
                        function($v,$pr,$row,$f){

                            $types = \Ali\Logistic\ContractorsType::getLabels();
                            if(array_key_exists($v, $types) == false){
                                return "Недопустимое значение типа!";
                            }

                            return true;
                        }
                    );
                }
            )),


            new Entity\StringField('INN',array(
                'required'=>1,
                'unique'=>1,
                'validation'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            $length = strlen($value);
                            $validLeng = $row['ENTITY_TYPE'] == \Ali\Logistic\ContractorsType::IP ? 12 : 10; 
                            if($length != $validLeng){
                                $msg = $row['ENTITY_TYPE'] == \Ali\Logistic\ContractorsType::LEGAL ? "Юр.лицо" : "ИП";
                                return "Неправильный формат ИНН для ".$msg.". Номер должен состоять из ".$validLeng." цифр";
                            }

                            return true;
                        },
                        new Entity\Validator\Unique('Организация с таким ИНН зарегистрирован на сайте'),
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
                'unique'=>1,
                'default_value'=>function(){
                    return null;
                },
                'validation'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            $validLeng = 9;
                            $len = strlen($value);
                            $type = $row['ENTITY_TYPE'] == \Ali\Logistic\ContractorsType::IP;
                            
                            if($row['ENTITY_TYPE'] == \Ali\Logistic\ContractorsType::IP && $len > 0){
                                return "У ИП должен отсутствовать код КПП";
                            }

                            if($row['ENTITY_TYPE'] == \Ali\Logistic\ContractorsType::LEGAL && $len != $validLeng){
                                return "Код КПП должен состоять из ".$validLeng." цифр";
                            }

                            if($row['ENTITY_TYPE'] == \Ali\Logistic\ContractorsType::LEGAL){
                                //Для юр лиц обязателен и должен быть уникальным
                                $field->addValidator(new \Bitrix\Main\Entity\Validator\Unique('Организация с таким КПП зарегистрирован на сайте'));
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
                'required'=>1,
                'unique'=>1,
                'validation'=>function(){
                    return array(
                        new Entity\Validator\Unique('Организация с таким OGRN зарегистрирован на сайте'),
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
                'required'=>1,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),

            new Entity\StringField('BANK_NAME',array(
                'required'=>true,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),

            new Entity\StringField('CHECKING_ACCOUNT',array(
                'required'=>true,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return trim(strip_tags($value));
                        }
                    );
                }
            )),

            new Entity\StringField('CORRESPONDENT_ACCOUNT',array(
                'required'=>true,
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



    public static function defaultSelect(){
        return array(
            'ID','OWNER_ID','NAME','LEGAL_ADDRESS',
            'PHYSICAL_ADDRESS','ENTITY_TYPE','INN',
            'KPP','OGRN','BANK_NAME','BANK_BIK','CHECKING_ACCOUNT',
            'CORRESPONDENT_ACCOUNT'
        );
    }



    public static function save($data){
        global $USER;
        if(!($company_id = CompaniesTable::hasCurrentUserHasComany())){
            if(CompaniesTable::createCompanyForCurrentUser()){
                $company_id = CompaniesTable::getCurrentUserCompany();
            }
        }

        if(!$company_id) return false;

        $data['COMPANY_ID'] = $company_id;
        $data['OWNER_ID'] = $USER::GetId();
        
        $res = new Result();
        
        $primary = isset($data['ID']) ? ['ID'=>$data['ID']] : null;
        self::checkFields($res,$primary,$data);
        

        if(!$res->isSuccess()){
            
            return $res;
        }

        if(isset($data['ID']))
            $result = self::update(['ID'=>$data['ID']],$data);
        else
            $result = self::add($data);
        
        return $result;
    }




    public static function getOrgs($id = null,$parameters = array()){
        global $USER;
        
        $company_id = CompaniesTable::getCurrentUserCompany();
        if(!$company_id){

            //Проверка на присоединение к компании в таблице ali_logistic_company_employee
            return array();
        } 

        $local_params = array(
            'select'=>self::defaultSelect()
        );
        $params = array_merge($local_params,$parameters);
        
        $params['filter']['COMPANY_ID']=$company_id;
        
        if($id){
            
            $local_params['filter']['ID']=$id;

            return self::getRow($params);
        }else{
            return self::getList($params)->fetchAll();
        }

    }
}