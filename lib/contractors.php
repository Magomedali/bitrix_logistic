<?php


namespace Ali\Logistic;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Entity\Result;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;
use Ali\Logistic\Dictionary\ContractorsType;
use Ali\Logistic\User;
use Ali\Logistic\Companies;
use Ali\Logistic\soap\clients\Contractors1C;
use Ali\Logistic\Schemas\ContractorsSchemaTable;
use Bitrix\Main\Error;

class Contractors
{   

    public static function defaultSelect(){
        return array(
            'ID','OWNER_ID','INTEGRATED_ID','NAME','LEGAL_ADDRESS',
            'PHYSICAL_ADDRESS','ENTITY_TYPE','INN',
            'KPP','OGRN','BANK_NAME','BANK_BIK','CHECKING_ACCOUNT',
            'CORRESPONDENT_ACCOUNT'
        );
    }

   

    public static function save($data){
        global $USER;

        $company_id = 0;
        
        $companyies = User::getCurrentUserCompany();

        if(!is_array($companyies) || !count($companyies)){
            Companies::createCompanyForCurrentUser();
            $company_id = User::getUserCompany($USER::GetId());
        }else{
            $company_id = reset($companyies);
        }
        

        if(!$company_id){
            $res = new Result();
            $res->addError(new Error("Ошибка! Не найдена компания!",404));
            return $res;
        }

        

        $data['COMPANY_ID'] = $company_id;
        $data['OWNER_ID'] = $USER::GetId();
        

        $primary = isset($data['ID']) ? ['ID'=>$data['ID']] : null;
        if($primary)
            $result = ContractorsSchemaTable::update($primary,$data);
        else
            $result = ContractorsSchemaTable::add($data);
        


        if($result->isSuccess()){
            $data['ID']=$result->getId();


            $user = UserTable::getRowById($data['OWNER_ID']);
            if($user){
               $data['USER_NAME'] = $user['SECOND_NAME']." ".$user['NAME'];
               $data['USER_EMAIL'] = $user['EMAIL'];
               $data['USER_PHONE'] = $user['PERSONAL_PHONE']; 
            }
            
            $responce = self::integrateTo1C($data);
        }

        return $result;
    }




    public static function getOrgs($id = null,$parameters = array()){
        global $USER;
        
        $company_ids = User::getCurrentUserCompany();
        if(!is_array($company_ids) || !count($company_ids)){

            //Проверка на присоединение к компании в таблице ali_logistic_company_employee
            return array();
        } 

        $local_params = array(
            'select'=>self::defaultSelect()
        );
        $params = array_merge($local_params,$parameters);
        
        $params['filter']['COMPANY_ID']=$company_ids;
        
        if($id){
            
            $local_params['filter']['ID']=$id;

            return ContractorsSchemaTable::getRow($params);
        }else{
            return ContractorsSchemaTable::getList($params)->fetchAll();
        }

    }



    public static function delete($id){
        return ContractorsSchemaTable::delete($id);
    }



    public static function integrateTo1C($data){
        return Contractors1C::save($data);
    }
}