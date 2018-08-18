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
            new Entity\IntegerField('ID', array(
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


            new Entity\IntegerField('INN',array(
                'required'=>1,
                'unique'=>1,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return (int)$value;
                        }
                    );
                }
            )),

            new Entity\IntegerField('KPP',array(
                'required'=>1,
                'unique'=>1,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return (int)$value;
                        }
                    );
                }
            )),

            new Entity\IntegerField('OGRN',array(
                'required'=>1,
                'unique'=>1,
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return (int)$value;
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
                    return date("Y-m-d\TH:i:s",time());
                }
            )),

            new Entity\DatetimeField('UPDATED_AT',array(
                'default_value'=>function(){
                    return date("Y-m-d\TH:i:s",time());
                },
                'save_data_modification'=>function(){
                    return array(
                        function($value,$primary,$row,$field){
                            return date("Y-m-d\TH:i:s",time());
                        }
                    );
                }
            ))


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
        
        $data = self::checkFields($res,['ID'],$data);
        
        if(!$res->isSuccess()){
            print_r($res->getErrorMessages());
        }
        

        exit;
        //фильтрация данных
        // $data['NAME'] = isset($data['NAME']) ? trim(strip_tags($data['NAME'])) : "";
        // $data['LEGAL_ADDRESS'] = isset($data['LEGAL_ADDRESS']) ? trim(strip_tags($data['LEGAL_ADDRESS'])) : "";
        // $data['PHYSICAL_ADDRESS'] = isset($data['PHYSICAL_ADDRESS']) ? trim(strip_tags($data['PHYSICAL_ADDRESS'])) : "";
        // $data['ENTITY_TYPE'] = isset($data['ENTITY_TYPE']) ? trim(strip_tags($data['ENTITY_TYPE'])) : "";
        // $data['INN'] = isset($data['INN']) ? (int)$data['INN'] : ""; 

        $result = self::add($data);
        
        return $result->isSuccess() ? true :false;
    }

}