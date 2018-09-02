<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserUtils;
use Bitrix\Main\UserTable;
use Ali\Logistic\Companies;
use Ali\Logistic\Deals;
use Ali\Logistic\User;
use Ali\Logistic\Contractors;
use Ali\Logistic\Routes;
use \Bitrix\Main\Application;
use Bitrix\Main\Entity\Result;
use Ali\Logistic\soap\clients\CheckINN;
use Ali\Logistic\helpers\ArrayHelper;
use Ali\Logistic\soap\clients\Sverka1C;
use Ali\Logistic\Schemas\DealsSchemaTable;
use Ali\Logistic\Dictionary\DealFileType;
use Ali\Logistic\Dictionary\DealStates;
use Ali\Logistic\Schemas\ReviseSchemaTable;

class AliProfile extends CBitrixComponent
{

    protected $pageName = "alk";
    
    public function getPageName(){
        return $this->pageName;
    }


    public function getUrl($subpage = null,$params = array()){

        $page = $subpage ? $this->pageName."/".$subpage : $this->pageName;

        
        if(!empty($params) && is_array($params) && count($params)){
            $url = "/".$page."/";
            $arr_q = array();
            foreach ($params as $key => $value) {
                $arr_q[] = $key."=".$value;
            }
            if(count($arr_q)){
                $query_string = implode("&", $arr_q);
                $url .="?".$query_string; 
            }
        }else{
            $url = "/".$page."/";
        }
        
        return $url;
    }

    public function getActionUrl($action=null,$params = array()){
        $page = $this->pageName;

        if($action != null || !empty($action)){
            $url = "/".$page."/index.php?r=".$action;
            if(!empty($params) && is_array($params) && count($params)){
                $arr_q = array();
                foreach ($params as $key => $value) {
                    $arr_q[] = $key."=".$value;
                }
                if(count($arr_q)){
                    $query_string = implode("&", $arr_q);
                    $url .="&".$query_string; 
                }
            }
        }else{
            $url = "/".$page."/";
        }
        
        return $url;
    }



    protected function checkModules()
    {
        if (!Loader::includeModule('ali.logistic'))
        {
            ShowError(Loc::getMessage('ALI_MODULE_NOT_INSTALLED'));
            return false;
        }

        return true;
    }


    public function executeAction(){

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();


        $route = isset($this->arParams['route']) ? $this->arParams['route'] : null;

       
        if(isset($request['r']) || $route){

            $route = isset($request['r']) ? $request['r'] : $route;
            $action = strtolower(trim(strip_tags($route)))."Action";

            if(method_exists($this, $action)){
                return $this->$action();
            }else{
                return $this->defaultAction();
            }

        }else{
            return $this->defaultAction();
        }
    }


    public function executeComponent()
    {
        $this->includeComponentLang('class.php');




        if($this->checkModules())
        {   
            
            $template = $this->executeAction();

            $context = Application::getInstance()->getContext();
            $request = $context->getRequest();
            if($request->isAjaxRequest()){
                
                // $this->returnAjaxJsonResult();
                if($template){
                    $this->returnAjaxHtmlResult($template);
                }else{
                    $this->returnAjaxJsonResult();
                }
            }else{
                $this->includeComponentTemplate($template);
            }
        }
    }

    protected function returnAjaxJsonResult(){

        $jsonData = \Bitrix\Main\Web\Json::encode($this->arResult);
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        echo $jsonData; 
        exit;
    }


    protected function returnAjaxHtmlResult($html_page=""){
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        $this->includeComponentTemplate($html_page);
        exit;
    }







    public function defaultAction(){
        
        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        $id = CUser::GetID();
        
        $user = UserTable::getRowById($id);

        $this->arResult['user'] = $user;

        return $this->arResult;
    }

    public function alkAction(){
        return $this->defaultAction();
    }










    public function profileformAction(){
        
        $id = (int)CUser::GetID();
        if(!$id) LocalRedirect($this->getUrl());

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        $errors = array();

        $user = UserTable::getRow(['select'=>['*'],'filter'=>['ID'=>$id]]);

        if($request->isPost() && isset($request['USER']) && is_array($request['USER']) && count($request['USER'])){
            $user = $request['USER'];
            
            $CUser =  new CUser;

            if($CUser->Update($id,$user)){
                LocalRedirect($this->getUrl());
            }else{
                $errors = [$CUser->LAST_ERROR];
            }
        }

        $this->arResult = [
            'user' => $user,
            'errors'=>$errors
        ];

        return "personal/form";
    }












    /**
    *
    * @return template name 
    */
    public function organisationsAction(){
        
        $id = CUser::GetID();

        $orgs = Contractors::getOrgs(null,$params);

        $this->arResult = [
            'orgs'=>$orgs
        ];


        return "orgs/organisations";
    }




















    /**
    *
    * @return template name 
    */
    public function formorgAction(){
        
        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        $errors = array();
        $org = array();
        if($request->isPost() && isset($request['ORG'])){
            
            $org = $request['ORG'];
            $res = Contractors::save($request['ORG']);
            
            if(!$res->isSuccess()){
                $errors = $res->getErrorMessages();
            }else{
                LocalRedirect($this->getUrl("organisations"));
            }
        }elseif(isset($request['id'])){
            $org = Contractors::getOrgs((int)$request['id']);
        }


        $this->arResult = [
            'errors'=>$errors,
            'org'=>$org
        ];


        return "orgs/formOrg";
    }
















    public function checkinnAction(){

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        if(!$request->isAjaxRequest()){
           LocalRedirect($this->getUrl("organisations")); 
        }
        
        $state = array(
            'success'=>0,
            'msg'=>'ИНН отсутствует'
        );
        if(isset($request['inn'])){
            $inn = trim(strip_tags($request['inn']));
            
            $state = CheckINN::check($inn);
            $state['INN'] = $inn;
        }

            
        $this->arResult = $state;
    }

















    public function vieworgAction(){

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        $org = null;
        if(isset($request['id'])){
            $org = Contractors::getOrgs((int)$request['id']);
        }

        if(!$org || !isset($org['ID'])){
            LocalRedirect($this->getUrl("organisations"));
        }


        $this->arResult = [
            'org'=>$org
        ];

        return "orgs/viewOrg";
    }














    public function removeorgAction(){

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        
        $org = null;

        if($request->isPost() && isset($request['id']) && (int)$request['id'] && isset($request['id']) && (int)$request['confirm']){
            $org = Contractors::getOrgs((int)$request['id']);

            if($org && isset($org['ID'])){
                Contractors::delete($org['ID']);
                LocalRedirect($this->getUrl("organisations"));
            }

        }elseif(isset($request['id'])){
            $org = Contractors::getOrgs((int)$request['id']);
        }

        if(!$org || !isset($org['ID'])){
            LocalRedirect($this->getUrl("organisations"));
        }

        $this->arResult = [
            'org'=>$org
        ];

        return "orgs/confirmRemove";
    }









    public function dealformAction(){
        $contractors = User::getCurrentUserIntegratedContractors();

        if(!is_array($contractors) || !count($contractors)){
            LocalRedirect($this->getUrl());
        }

        
        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        $errors = array();
        $deal = array();
        $routes = array();
        $replicate = false;
        if($request->isPost() && isset($request['DEAL'])){
            
            $deal = array();
            if(isset($request['DEAL']['ID'])){
                $deal = DealsSchemaTable::getRow(['select'=>['*'],'filter'=>['ID'=>(int)$request['DEAL']['ID']]]);
                if(!isset($deal['ID'])) LocalRedirect($this->getUrl("deals"));
            }
            $deal = array_merge($deal,$request['DEAL']);

            
            $contrs_uuids = ArrayHelper::map($contractors,'ID','INTEGRATED_ID');

            if(!isset($deal['CONTRACTOR_ID']) || !array_key_exists($deal['CONTRACTOR_ID'], $contrs_uuids)){
                LocalRedirect($this->getUrl());
            }

            $user_id = CUser::GetID();
            $deal['OWNER_ID'] = $user_id;
            $deal['IS_DRAFT'] = isset($request['how_draft']);
            
            $routes = isset($request['ROUTES']) && is_array($request['ROUTES']) ? $request['ROUTES'] : array();

            if(count($routes) >= 2){
                $res = Deals::save($deal);
            
                if(!$res->isSuccess()){
                    $errors = $res->getErrorMessages();

                    
                }else{
                    $deal['ID']= $res->getId();
                    
                    if(count($routes)){
                        $tmpRoute = array();

                        foreach ($routes as $key => $rData) {
                            $rData['DEAL_ID'] = $deal['ID'];
                            $rData['OWNER_ID'] = $user_id;
                            $res = Routes::save($rData);
                            if(!$res->isSuccess()){
                                $errors = array_merge($errors,$res->getErrorMessages());
                            }else{
                                $rData['ID'] = $res->getId();
                            }
                            $tmpRoute[]=$rData;
                        }

                        $routes = count($tmpRoute) ? $tmpRoute : $routes;
                    }

                    
                    if(!count($errors)){
                        
                        if($deal['IS_DRAFT']){
                            LocalRedirect($this->getUrl("draftdeals"));
                        }else{

                            $deal['ROUTES']=$routes;

                            $deal['CONTRACTOR_INTEGRATED_ID'] = $contrs_uuids[$deal['CONTRACTOR_ID']];
                            $res = Deals::integrateDealTo1C($deal);

                            if($res->isSuccess()){
                                LocalRedirect($this->getUrl("deals"));
                            }else{
                                LocalRedirect($this->getUrl("draftdeals"));
                            }
                        }
                        
                    }

                    
                }
            }else{
                $errors[] = "Добавьте пожалуйста машруты, необходимо минимум 2!";
            }

            
        }elseif(isset($request['id'])){
            $deal = Deals::getDeals((int)$request['id']);
            $routes = Routes::getRoutes((int)$request['id']);

            if((int)$deal['STATE'] >= DealStates::IN_PLANNING){
                LocalRedirect($this->getUrl());
            } 

        }elseif(isset($request['replicate'])){
            $deal = Deals::getDeals((int)$request['replicate']);
            unset($deal['ID']);
            $routes = Routes::getRoutes((int)$request['replicate']);
            $replicate = true;
        }

        $this->arResult = [
            'errors'=>$errors,
            'deal'=>$deal,
            'contractors'=>$contractors,
            'routes'=>$routes,
            'replicate'=>$replicate
        ];

        return "deals/form";
    }





    public function getrowrouteAction(){
        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        $number = isset($request['number']) && (int)$request['number'] ? (int)$request['number'] : 0;
        $this->arResult = [
            'number'=>$number
        ];

        return "deals/rowRoute";
    }



    public function rmrouteAction(){
        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        $success = false;

        if($request->isAjaxRequest() && isset($request['id'])){
            
            $user_id = CUser::GetID();
            $success = Routes::delete((int)$request['id'],$user_id);
        }

        $this->arResult = [
            'success'=>$success
        ];
    }






    public function dealsAction(){

        $id = CUser::GetID();

        $params['filter']['IS_ACTIVE'] = true;
        $deals = Deals::getDeals(null,$params);

        $this->arResult = [
            'deals'=>$deals,
            'type'=>'IS_ACTIVE',
        ];


        return "deals/deals";
    }



    public function draftdealsAction(){

        $id = CUser::GetID();

        $params['filter']['IS_DRAFT'] = true;
        $deals = Deals::getDeals(null,$params);

        $this->arResult = [
            'deals'=>$deals,
            'type'=>'IS_DRAFT'
        ];


        return "deals/deals";
    }







    public function completeddealsAction(){

        $id = CUser::GetID();

        $params['filter']['COMPLETED'] = true;
        $deals = Deals::getDeals(null,$params);

        $this->arResult = [
            'deals'=>$deals,
            'type'=>'COMPLETED'
        ];


        return "deals/deals";
    }





    public function archiveAction(){

        $id = CUser::GetID();

        $params['filter']['IS_DELETED'] = true;
        $deals = Deals::getDeals(null,$params);

        $this->arResult = [
            'deals'=>$deals,
            'type'=>'IS_DELETED'
        ];


        return "deals/deals";
    }





    public function viewdealAction(){
        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        $deal = null;
        $user_id = CUser::GetID();
        
        

        if(isset($request['id'])){
            $deal = Deals::getDeals((int)$request['id']);
            $routes = Routes::getRoutes((int)$request['id']);
        }

        if(!$deal || !isset($deal['ID']) || $deal['OWNER_ID'] != $user_id){
            LocalRedirect($this->getUrl("deals"));
        }

        

        $this->arResult = [
            'deal'=>$deal,
            'routes'=>$routes
        ];

        return "deals/view";
    }





    public function searchdealsAction(){

        $params = [];
        $deals = Deals::getDeals(null,$params);

        $this->arResult = [
            'deals'=>$deals,
            'type'=>'IS_DELETED'
        ];


        return "deals/deals";
    }



    public function billsAction(){
        $files = User::getFiles(DealFileType::FILE_BILL);

        $this->arResult=[
            'files'=>$files,
            'type'=>DealFileType::FILE_BILL
        ];

        return 'bills';
    }



    public function actsAction(){
        $files = User::getFiles(DealFileType::FILE_ACT);

        $this->arResult=[
            'files'=>$files,
            'type'=>DealFileType::FILE_ACT
        ];

        return 'bills';
    }


    public function invoicesAction(){
        $files = User::getFiles(DealFileType::FILE_INVOICE);

        $this->arResult=[
            'files'=>$files,
            'type'=>DealFileType::FILE_INVOICE
        ];

        return 'bills';
    }



    public function tthAction(){
        $files = User::getFiles(DealFileType::FILE_TTH);

        $this->arResult=[
            'files'=>$files,
            'type'=>DealFileType::FILE_TTH
        ];

        return 'bills';
    }


    public function docsAction(){
        $files = User::getFiles(DealFileType::FILE_CONTRACT);

        $this->arResult=[
            'files'=>$files,
            'type'=>DealFileType::FILE_CONTRACT
        ];

        return 'bills';
    }



    public function downloadFileAction(){

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        if(isset($request['f']) && (int)$request['f']){
            $file = User::getDealFile($request['f']);

            if(isset($file['ID']) && $file['ID'] && isset($file['FILE'])){
                $path = DealFileType::getFilePath($file['FILE_TYPE']);
               
                if(file_exists($path.$file['FILE'])){

                    global $APPLICATION;
                    $APPLICATION->RestartBuffer();

                    header("Content-type; text/pdf; charset='utf-8'");
                    header("Cache-Control: no-cache");
                    // header("Content-Description: File Transfer");
                    // header("Content-Disposition: attachment; filename=file");
                    header("Content-Type: application/pdf");
                    header("Content-Transfer-Encoding: binary");
                    readfile($path.$file['FILE']);
                    exit;

                }
            }
                
            $this->arResult=[
                'error'=>"Файл не найден!"
            ];
            return "error";
        }
        
        LocalRedirect($this->getUrl());
        $this->arResult=[
            'files'=>$bills
        ];

        return 'bills';
    }




    public function reportAction(){

        $contractors = User::getCurrentUserIntegratedContractors();

        if(!is_array($contractors) || !count($contractors)){
            LocalRedirect($this->getUrl());
        }

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        $contrs_uuids = ArrayHelper::map($contractors,'ID','INTEGRATED_ID');
        $parameters = array();
        
        $errors = null;
        $revice = null;
        $oldRevises = null;
        if($request->isPost() && isset($request['dateFrom']) && isset($request['dateTo']) && $request['dateFrom'] && $request['dateTo'] && isset($request['contractor']) && array_key_exists($request['contractor'], $contrs_uuids)){

            
            $parameters['with_nds'] = isset($request['with_nds']);
            $parameters['contractor'] = $contrs_uuids[$request['contractor']];
            $parameters['contractor_id']=$request['contractor'];
            $parameters['dateFrom'] = date("Y-m-d H:i",strtotime($request['dateFrom']));
            $parameters['dateTo'] = date("Y-m-d H:i",strtotime($request['dateTo']));

            $oldRevises = ReviseSchemaTable::getList(['select'=>['*'],'filter'=>['CONTRACTOR_ID'=>$request['contractor']]])->fetchAll();

            $result = Sverka1C::get($parameters);
            
            if($result->isSuccess()){
                $revice_id = $result->getId();

                $revice = ReviseSchemaTable::getRow(['select'=>['*'],'filter'=>['CONTRACTOR_ID'=>$request['contractor'],'ID'=>$revice_id]]);
            }else{
                $errors = $result->getErrorMessages();
            }
        }

        $this->arResult = [
            'contractors'=>$contractors,
            'parameters'=>$parameters,
            'errors'=>$errors,
            'revice'=>$revice,
            'oldRevises'=>$oldRevises
        ];

        return "report";
    }


}