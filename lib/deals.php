<?php

namespace Ali\Logistic;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Entity\Result;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;
use \Ali\Logistic\Schemas\DealsSchemaTable;
use \Ali\Logistic\Schemas\DealFilesSchemaTable;
use Ali\Logistic\soap\clients\Deals1C;
use Ali\Logistic\Dictionary\TypeOfVehicle;
use Ali\Logistic\Dictionary\LoadingMethod;
use Ali\Logistic\Dictionary\DealFileType;
use Ali\Logistic\Dictionary\AdditionalEquipment;
use Ali\Logistic\Dictionary\Documents;
use Ali\Logistic\Dictionary\SpecialEquipment;
use Ali\Logistic\Dictionary\HowPacked;
use Ali\Logistic\helpers\ArrayHelper;
use Ali\Logistic\Schemas\DealCostingsSchemaTable;
use Ali\Logistic\Helpers\Upload;

class Deals{




	public static function defaultSelect(){
        return array(
            '*'
        );
    }

    public static function normalizeData($data){


        if(isset($data['TYPE_OF_VEHICLE']) && is_array($data['TYPE_OF_VEHICLE'])){
            $typesOfVehicle = TypeOfVehicle::toString($data['TYPE_OF_VEHICLE']);
            $data['TYPE_OF_VEHICLE'] = $typesOfVehicle;
        }

        if(isset($data['LOADING_METHOD']) && is_array($data['LOADING_METHOD'])){
            $loadingMethods = LoadingMethod::toString($data['LOADING_METHOD']);
            $data['LOADING_METHOD'] = $loadingMethods;
        }

        if(isset($data['UNLOADING_METHOD']) && is_array($data['UNLOADING_METHOD'])){
            $unloadingMethods = LoadingMethod::toString($data['UNLOADING_METHOD']);
            $data['UNLOADING_METHOD'] = $unloadingMethods;
        }

        if(isset($data['ADDITIONAL_EQUIPMENT']) && is_array($data['ADDITIONAL_EQUIPMENT'])){
            $addEq = AdditionalEquipment::toString($data['ADDITIONAL_EQUIPMENT']);
            $data['ADDITIONAL_EQUIPMENT'] = $addEq;
        }

        if(isset($data['SPECIAL_EQUIPMENT']) && is_array($data['SPECIAL_EQUIPMENT'])){
            $sp = SpecialEquipment::toString($data['SPECIAL_EQUIPMENT']);
            $data['SPECIAL_EQUIPMENT'] = $sp;
        }

        if(isset($data['REQUIRED_DOCUMENTS']) && is_array($data['REQUIRED_DOCUMENTS'])){
            $docs = Documents::toString($data['REQUIRED_DOCUMENTS']);
            $data['REQUIRED_DOCUMENTS'] = $docs;
        }


        // if(isset($data['HOW_PACKED']) && is_array($data['HOW_PACKED'])){
        //     $hwp = HowPacked::toString($data['HOW_PACKED']);
        //     $data['HOW_PACKED'] = $hwp;
        // }



        $data['CARGO_HANDLING'] = isset($data['CARGO_HANDLING']) && boolval($data['CARGO_HANDLING']);
        $data['CROSS_DOCKING'] = isset($data['CROSS_DOCKING']) && boolval($data['CROSS_DOCKING']);
        $data['SECURE_STORAGE'] = isset($data['SECURE_STORAGE']) && boolval($data['SECURE_STORAGE']);
        $data['REQUIRED_RUSSIAN_DRIVER'] = isset($data['REQUIRED_RUSSIAN_DRIVER']) && boolval($data['REQUIRED_RUSSIAN_DRIVER']);
        $data['ARMED_ESCORT'] = isset($data['ARMED_ESCORT']) && boolval($data['ARMED_ESCORT']);


        $data['INTEGRATE_ERROR'] = isset($data['INTEGRATE_ERROR']) && boolval($data['INTEGRATE_ERROR']);
        $data['IS_INTEGRATED'] = isset($data['IS_INTEGRATED']) && boolval($data['IS_INTEGRATED']);
        $data['REQUIRES_LOADER'] = isset($data['REQUIRES_LOADER']) && boolval($data['REQUIRES_LOADER']);
        $data['REQUIRES_INSURANCE'] = isset($data['REQUIRES_INSURANCE']) && boolval($data['REQUIRES_INSURANCE']);
        $data['SUPPORT_REQUIRED'] = isset($data['SUPPORT_REQUIRED']) && boolval($data['SUPPORT_REQUIRED']);
        $data['WITH_NDS'] = isset($data['WITH_NDS']) && boolval($data['WITH_NDS']);
        $data['COMPLETED'] = isset($data['COMPLETED']) && boolval($data['COMPLETED']);

        $data['IS_ACTIVE']=false;


        if(!isset($data['CREATED_AT']))
            $data['CREATED_AT'] = new \Bitrix\Main\Type\DateTime();

        return $data;
    }

    public static function save($data){
       
        $data = self::normalizeData($data); 
        
        

        $primary = isset($data['ID']) ? ['ID'=>$data['ID']] : null;
        if($primary){
            $result = DealsSchemaTable::update($primary,$data);
        }else{
            $result = DealsSchemaTable::add($data);
        }
        
        // if($result->isSuccess() && !$data['IS_DRAFT']){
        //     $data['ID']=$result->getId();
        // }

        return $result;
    }




    public static function getDeals($id = null,$parameters = array()){
        global $USER;
        
        $contractors = User::getCurrentUserIntegratedContractors();
        
        if(empty($contractors) || (is_array($contractors) && !count($contractors))){
            //Проверка на присоединение к компании в таблице ali_logistic_company_employee
            return array();
        } 


        $select = self::defaultSelect();
        $select = array_merge($select,[
            'CONTRACTOR_NAME'=>"CONTRACTOR.NAME"
        ]);

        $local_params = array(
            'select'=>$select
        );
        $params = array_merge($local_params,$parameters);
        
        $contractors = ArrayHelper::map($contractors,'ID','ID');
        
        if(!isset($params['filter']['=CONTRACTOR_ID']))
            $params['filter']['=CONTRACTOR_ID'] = $contractors;
        
        
        $deals = array();
        if($id){
            $params['filter']['ID']=$id;

            $deals = DealsSchemaTable::getRow($params);
            if(isset($deals['ID'])){
                $deals['files'] = self::getDealFiles($deals['ID']);
            }
        }else{
            $results = DealsSchemaTable::getList($params)->fetchAll();

            foreach ($results as $d) {
                
                if(isset($d['ID'])){
                    $deals[$d['ID']]=$d;
                    $deals[$d['ID']]['files'] = self::getDealFiles($d['ID']);
                }
                
            }
        }

        return $deals;

    }


    public static function getDealsTotal($parameters = array()){
        global $USER;
        
        $contractors = User::getCurrentUserIntegratedContractors();
        
        if(empty($contractors) || (is_array($contractors) && !count($contractors))){
            //Проверка на присоединение к компании в таблице ali_logistic_company_employee
            return 0;
        }

        $local_params = array(
            'select'=>[new Entity\ExpressionField('TOTAL','COUNT(1)')]
        );
        $params = array_merge($local_params,$parameters);
        
        $contractors = ArrayHelper::map($contractors,'ID','ID');
        $params['filter']['CONTRACTOR_ID'] = $contractors;
        
        $deals = array();

        $results = DealsSchemaTable::getRow($params);

        
        return isset($results['TOTAL'])? $results['TOTAL'] : 0;

    }



    public static function getDealFile($deal_id,$type){

        if(!$deal_id || !$type) return null;

        

        $file = DealFilesSchemaTable::getRow([
             'select'=>['*'],
             'filter'=>['DEAL_ID'=>$deal_id,'FILE_TYPE'=>$type],
             'order'=>['CREATED_AT'=>'DESC']
        ]);

        return $file;
    }


    public static function getDealFiles($deal_id){

        $files = array();

        $files[DealFileType::FILE_BILL] = self::getDealFile($deal_id,DealFileType::FILE_BILL);
        $files[DealFileType::FILE_INVOICE] = self::getDealFile($deal_id,DealFileType::FILE_INVOICE);
        $files[DealFileType::FILE_ACT] = self::getDealFile($deal_id,DealFileType::FILE_ACT);
        $files[DealFileType::FILE_CONTRACT] = self::getDealFile($deal_id,DealFileType::FILE_CONTRACT);
        $files[DealFileType::FILE_DRIVER_ATTORNEY] = self::getDealFile($deal_id,DealFileType::FILE_DRIVER_ATTORNEY);
        $files[DealFileType::FILE_PRINT_FORM] = self::getDealFile($deal_id,DealFileType::FILE_PRINT_FORM);
        $files[DealFileType::FILE_TTH] = self::getDealFile($deal_id,DealFileType::FILE_TTH);


        return $files;
    }


    public static function delete($id){
        return DealsSchemaTable::delete($id);
    }



    public static function integrateDealTo1C($data){
        return Deals1C::save(self::normalizeData($data));
    }



    public static function getPublicPathDealFiles(){
        return str_replace($_SERVER['DOCUMENT_ROOT'], '', ALI_DEAL_FILES);
    } 


    public static function uploadFile($id,$file,$oldFile = null){
        if(!$id || !$file) return false;
        
        $handle = new Upload($file);
        $file_name = $id."_deal_file_".time();
        if ($handle->uploaded) {
            
             //переименовываем изображение
            $handle->file_new_name_body = $file_name;
            $file_ext = $file_name.".".$handle->file_src_name_ext;
            
            

            $handle->process(ALI_DEAL_FILES);
            if ($handle->processed) {
                $handle->clean();
                
                $res = DealsSchemaTable::update($id,['PRINT_FORM'=>$file_ext]);
                
                if($res->isSuccess()){
                    if($oldFile && file_exists(ALI_DEAL_FILES.$oldFile)){
                        unlink(ALI_DEAL_FILES.$oldFile);
                    }
                }else{
                    if($file_ext && file_exists(ALI_DEAL_FILES.$file_ext)){
                        unlink(ALI_DEAL_FILES.$file_ext);
                    }
                }

                return $res->isSuccess();
            }
        }
        return false;
    }
}