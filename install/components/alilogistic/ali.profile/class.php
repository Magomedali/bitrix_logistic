<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserUtils;
use Bitrix\Main\UserTable;
use Ali\Logistic\Companies;
use Ali\Logistic\Contractors;
use \Bitrix\Main\Application;
use Bitrix\Main\Entity\Result;
use Ali\Logistic\soap\clients\CheckINN;


class AliProfile extends CBitrixComponent
{

    protected $pageName = "personal";
    
    public function getPageName(){
        return $this->pageName;
    }


    public function getUrl($action, $params = array()){

        $url = "/".$this->pageName."/index.php?r=".$action;
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

        if(isset($request['r'])){

            $action = strtolower(trim(strip_tags($request['r'])))."Action";

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
        
        $this->arResult['id'] = $id;

        //Редирект
        // LocalRedirect("/chat/profile");
        return $this->arResult;
    }


    public function personalAction(){
        $id = CUser::GetID();

        return "personal/data";
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


}