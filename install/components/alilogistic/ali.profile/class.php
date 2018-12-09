<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserUtils;
use Bitrix\Main\UserTable;
use Ali\Logistic\Companies;
use Ali\Logistic\Deals;
use Ali\Logistic\User;
use Ali\Logistic\Contractors;
use Ali\Logistic\Settings;
use Ali\Logistic\Routes;
use Ali\Logistic\DealCostings;
use \Bitrix\Main\Application;
use Bitrix\Main\Entity\Result;
use Bitrix\Main\Error;
use Ali\Logistic\soap\clients\CheckINN;
use Ali\Logistic\helpers\ArrayHelper;
use Ali\Logistic\helpers\MimeTypes;
use Ali\Logistic\soap\clients\Sverka1C;
use Ali\Logistic\Schemas\DealsSchemaTable;
use Ali\Logistic\Schemas\RoutesSchemaTable;
use Ali\Logistic\Dictionary\DealFileType;
use Ali\Logistic\Dictionary\DealStates;
use Ali\Logistic\Schemas\ReviseSchemaTable;
use Bitrix\Main\Type\DateTime;

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
                if(is_array($value) && count($value)){
                    foreach ($value as $key2 => $value2) {
                        $arr_q[] = $key."[".$key2."]=".$value2;
                    }
                }else{
                   $arr_q[] = $key."=".$value; 
               }
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
            
            //Подключение общих сss и js
            global $APPLICATION;

            $APPLICATION->SetAdditionalCss($this->__path."/css/profile.css");
            
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
        $contractors = User::getCurrentUserIntegratedContractors();
        $hasCompany = is_array($contractors) && count($contractors);
        $orgs = Contractors::getOrgs(null,$params);

        $hasntContractors = isset($_SESSION['hasntContractors']) && boolval($_SESSION['hasntContractors']);  
        unset($_SESSION['hasntContractors']);

        $this->arResult['user'] = $user;
        $this->arResult['hasCompany'] = $hasCompany;
        $this->arResult['orgs'] = $orgs;
        $this->arResult['hasntContractors'] = $hasntContractors;
        
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

                if(isset($request['changePassword']) 
                    && (int)$request['changePassword'] 
                    && isset($request['new_password'])){

                    $newPass = trim(strip_tags($request['new_password']));
                    if(strlen($newPass) < 6){
                        $errors = array("Неправильный формат пароля! Пароль должен состоять минимум из 6 символов.");
                    }

                    if($CUser->Update($id,['PASSWORD'=>$newPass])){
                        LocalRedirect($this->getUrl());
                    }else{
                        $errors = [$CUser->LAST_ERROR];
                    }
                    //отправка на почту ссылку для смены пароля
                    // global $USER;
                    // $arResult = $USER->SendPassword($USER->GetLogin(), $USER->GetParam("EMAIL"));
                    // if($arResult["TYPE"] == "OK") 
                    //     $errors=["Контрольная строка для смены пароля выслана."];
                    // else 
                    //     $errors=["Введенные логин (email) не найдены."];

                    // global $USER;
                    // $arResult = $USER->ChangePassword($USER->GetLogin(), "WRD45GT", $newPass, $newPass);
                    // if($arResult["TYPE"] == "OK")
                    //     LocalRedirect($this->getUrl());
                    // else 
                    //     $errors=["не удалось сменить пароль!Обратитесь к администратору"];

                }else{
                    LocalRedirect($this->getUrl());
                }
                
            }else{
                $errors = [$CUser->LAST_ERROR];
            }
        }

        $title = "Форма пользователя";
        $this->arResult = [
            'user' => $user,
            'errors'=>$errors,
            'pageTitle'=>$title
        ];

        return "personal/form";
    }



    /**
     * Проверяем, является ли $password текущим паролем пользователя.
     *
     * @param int $userId
     * @param string $password
     *
     * @return bool
     */
    function checkPassword($userPassword, $password)
    {   
        // p6K[$T6i33d4824c30983e9ae498531c2b6e8a99
        $salt = substr($userPassword, 0, (strlen($userPassword) - 32));

        $realPassword = substr($userPassword, -32);
        $password = md5($salt.$password);
        
        return ($password == $realPassword);
    }



    /**
     * Получаем хэш пароля
     *
     * @param password
     *
     * @return hash
     */
    public function hashPassword($password)
    {   
        $salt = uniqid(mt_rand(), true);
        $salt = substr(md5($salt),0,8);

        $password = md5($salt.$password);
        
        return $salt.$password;
    }

    public function followAction(){

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        $errors = array();
        $id = (int)CUser::GetID();
        if(!$id) LocalRedirect($this->getUrl());

        $contractors = User::getCurrentUserIntegratedContractors();

        if(is_array($contractors) && count($contractors)){
            $errors[]="У вас уже есть доступные организации";
        }

        

        
        if(!count($errors) && $request->isPost() && isset($request['email']) && trim(strip_tags($request['email'])) && $request['password']){
            $email = trim(strip_tags($request['email']));
            $user = UserTable::getRow(['select'=>['*'],'filter'=>['=EMAIL'=>$email]]);
            $result = new Result();

            
            
            if(!isset($user['PASSWORD']) || !$this->checkPassword($user['PASSWORD'],$request['password'])){
            
                $result->addError(new Error("Некорректный логин или пароль!",404));
            
            }elseif($user['ID'] == $id){
            
                $result->addError(new Error("Введите данные другой учетной записи!",404));
            
            }else{

                $company_id = User::getUserCompany($user['ID']);
                if(!$company_id){
                    $result->addError(new Error("У пользователя нет организаций!",404));
                }
                
                $contractors = Companies::hasComanyContractors($company_id);
                if(!is_array($contractors) || !count($contractors)){
                    $result->addError(new Error("У пользователя нет организаций!",404));
                }

                if($result->isSuccess())
                    $result = Companies::registeUser($company_id,$id);
                
            }

            if(!$result->isSuccess())
                $errors = $result->getErrorMessages();
            else
                LocalRedirect($this->getUrl());

        }

        $this->arResult = [
            'errors'=>$errors
        ]; 

        return "personal/follow";
    }






    /**
    *
    * @return template name 
    */
    public function organisationsAction(){
        
        $id = CUser::GetID();

        $orgs = Contractors::getOrgs(null,$params);

        $this->arResult = [
            'orgs'=>$orgs,
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
            $_SESSION['hasntContractors'] = true;
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
            $oldFile = null;
            if(isset($request['DEAL']['ID'])){
                $deal = DealsSchemaTable::getRow(['select'=>['*'],'filter'=>['ID'=>(int)$request['DEAL']['ID']]]);
                if(!isset($deal['ID'])) LocalRedirect($this->getUrl("deals"));

                $oldFile = $deal['PRINT_FORM'];
            }
            $normalData = Deals::normalizeData($request['DEAL']);
           
            $deal = array_merge($deal,$normalData);
            
            
            $contrs_uuids = ArrayHelper::map($contractors,'ID','INTEGRATED_ID');

            if(!isset($deal['CONTRACTOR_ID']) || !array_key_exists($deal['CONTRACTOR_ID'], $contrs_uuids)){
                LocalRedirect($this->getUrl());
            }

            $user_id = CUser::GetID();
            $deal['OWNER_ID'] = $user_id;
            $deal['IS_DRAFT'] = isset($request['how_draft']);
            

            $routes_start = isset($request['ROUTES_START']) && is_array($request['ROUTES_START']) ? $request['ROUTES_START'] : array();
            $routes = isset($request['ROUTES']) && is_array($request['ROUTES']) ? $request['ROUTES'] : array();
            $routes_end = isset($request['ROUTES_END']) && is_array($request['ROUTES_END']) ? $request['ROUTES_END'] : array();

            if($routes_start && $routes_end){

                $deal['LOADING_PLACE'] = $routes_start['TOWN'];
                $deal['UNLOADING_PLACE'] = $routes_end['TOWN'];
                array_unshift($routes,$routes_start);
                array_push($routes, $routes_end);


                $res = Deals::save($deal);
            
                if(!$res->isSuccess()){
                    $errors = $res->getErrorMessages();
                }else{
                    $deal['ID']= $res->getId();
                    
                    //Сохранение файла, если был отправлен
                    if(isset($_FILES) && is_array($_FILES) && isset($_FILES['PRINT_FORM']) && is_array($_FILES['PRINT_FORM'])){
                        Deals::uploadFile($deal['ID'],$_FILES['PRINT_FORM'],$oldFile);
                    }
                    


                    if(count($routes)){
                        $tmpRoute = array();

                        foreach ($routes as $key => $rData) {
                            $rData['DEAL_ID'] = $deal['ID'];
                            $rData['OWNER_ID'] = $user_id;
                            $rData['ORDER'] = ++$key;
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
                $errors[] = "Добавьте пожалуйста машруты, необходимо минимум 2(адрес погрузки и адрес разгрузки)!";
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








    public function autocompleteOrgAction(){
        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        if(!$request->isAjaxRequest()){
            LocalRedirect($this->getUrl());
        }
        $list = array();
        try {
            if(isset($request['key']) && isset($request['contractor']) && (int)$request['contractor'] && $request['key']){
                $key = trim(strip_tags($request['key']));
                $key = \Bitrix\Main\Text\Encoding::convertEncodingToCurrent($key);
                $contractor = (int)$request['contractor'];

                $list = RoutesSchemaTable::getList(array(
                    'select'=>['ORGANISATION_DISTINCT'],
                    'filter'=>[
                        'DEAL.CONTRACTOR_ID'=>$contractor,
                        "%=ORGANISATION"=>"%{$key}%"
                    ],
                    'limit'=>10
                ))->fetchAll();
            }  
        } catch (\Exception $e) {}
        

        
        $this->arResult = [
            'list'=>$list
        ];

        return "deals/autocomlete_list";
    }


    public function autocompletePersonAction(){
        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        if(!$request->isAjaxRequest()){
            LocalRedirect($this->getUrl());
        }
        $list = array();
        try {
            if(isset($request['key']) && isset($request['contractor']) && isset($request['org']) && $request['org'] && (int)$request['contractor'] && $request['key']){
                $key = trim(strip_tags($request['key']));
                $key = \Bitrix\Main\Text\Encoding::convertEncodingToCurrent($key);
                $org = trim(strip_tags($request['org']));
                $org = \Bitrix\Main\Text\Encoding::convertEncodingToCurrent($org);
                $contractor = (int)$request['contractor'];
                global $DB;
                $results = $DB->Query("
                    SELECT DISTINCT dr.PERSON,dr.PHONE,dr.COMMENT FROM ".RoutesSchemaTable::getTableName()." dr 
                    INNER JOIN ".DealsSchemaTable::getTableName()." d ON d.ID =  dr.DEAL_ID
                    WHERE d.CONTRACTOR_ID = {$contractor} AND dr.ORGANISATION LIKE '%{$org}%' AND  dr.PERSON LIKE '%{$key}%'
                    LIMIT 10
                ");


                while ($row = $results->Fetch()){
                    array_push($list, $row);
                }
            }  
        } catch (\Exception $e) {}
        

        
        $this->arResult = [
            'list'=>$list
        ];

        return "deals/autocomlete_person_list";
    }









    public function getrowrouteAction(){
        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        if(!$request->isAjaxRequest()){
            LocalRedirect($this->getUrl());
        }

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
        }else{
            LocalRedirect($this->getUrl());
        }

        $this->arResult = [
            'success'=>$success
        ];
    }



    public function dealFilter($page_params){
        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        $page = 1;
        $offset =0;
        $limit = 10;
        $filtres = array();
        $params = array();
        
        $contractors = User::getCurrentUserIntegratedContractors();
        if(empty($contractors) || (is_array($contractors) && !count($contractors))){
            LocalRedirect($this->getUrl());
        }

        if(isset($request['Filter'])){
            $filtres=$request['Filter'];
            
            
            if(isset($filtres['date_from']) && strtotime($filtres['date_from']))
                $params['filter'][">=CREATED_AT"]=DateTime::createFromTimestamp(strtotime($filtres['date_from']));

            if(isset($filtres['date_to']) && strtotime($filtres['date_to']))
                $params['filter']["<=CREATED_AT"]=DateTime::createFromTimestamp(strtotime($filtres['date_to']));

            if(isset($filtres['number']) && trim(strip_tags($filtres['number'])) != "")
                $params['filter']["DOCUMENT_NUMBER"]=trim(strip_tags($filtres['number']))."%";
            
            if(isset($filtres['driver']) && trim(strip_tags($filtres['driver'])) != "")
                $params['filter']["DRIVER_INFO"]=trim(strip_tags($filtres['driver']))."%";

            if(isset($filtres['ts']) && trim(strip_tags($filtres['ts'])) != "")
                $params['filter']["VEHICLE"]=trim(strip_tags($filtres['ts']))."%";

            if(isset($filtres['loading']) && trim(strip_tags($filtres['loading'])) != "")
                $params['filter']["LOADING_PLACE"]=trim(strip_tags($filtres['loading']))."%";

            if(isset($filtres['unloading']) && trim(strip_tags($filtres['unloading'])) != "")
                $params['filter']["UNLOADING_PLACE"]=trim(strip_tags($filtres['unloading']))."%";

            if(isset($filtres['weight_f']) && $filtres['weight_f'] > 0)
                $params['filter'][">=WEIGHT"] = $filtres['weight_f'];

            if(isset($filtres['weight_t']) && $filtres['weight_t'] > 0)
                $params['filter']["<=WEIGHT"] = $filtres['weight_t'];

            if(isset($filtres['space_f']) && $filtres['space_f'] > 0)
                $params['filter'][">=SPACE"] = $filtres['space_f'];

            if(isset($filtres['space_t']) && $filtres['space_t'] > 0)
                $params['filter']["<=SPACE"] = $filtres['space_t'];

            if(isset($filtres['state']) && $filtres['state'] > 0)
                $params['filter']["=STATE"] = $filtres['state'];

            if(isset($filtres['stage']) && $filtres['stage'])
                $params['filter']["=".$filtres['stage']] = true;

            if(isset($filtres['contractor']) && (int)$filtres['contractor'])
                $params['filter']["=CONTRACTOR_ID"] = (int)$filtres['contractor'];
        }
        

        if(isset($request['page']) && $request['page'] && (int)$request['page'] > 0){
            $page = (int)$request['page'];
            $offset = $page * $limit - $limit;
        }

        if(isset($params['filter']) && isset($page_params['filter'])){
            $params['filter'] = array_merge($params['filter'],$page_params['filter']);
        }elseif (isset($page_params['filter'])) {
            if(array_key_exists("IS_DRAFT", $page_params['filter'])){
                $filtres['stage'] = 'IS_DRAFT';
            }elseif(array_key_exists("COMPLETED", $page_params['filter'])){
                $filtres['stage'] = 'COMPLETED';
            }elseif(array_key_exists("IS_ACTIVE", $page_params['filter'])){
                $filtres['stage'] = 'IS_ACTIVE';
            }

            $params['filter'] = $page_params['filter'];
        }

        if(isset($params['select']) || isset($page_params['select'])){
            $params['select'] = array_merge($params['select'],$page_params['select']);
        }
        
        
        $total = Deals::getDealsTotal($params);

        

        $params['order']=['CREATED_AT'=>'DESC'];
        $params['limit']=$limit;
        $params['offset']=$offset;
        $deals = Deals::getDeals(null,$params);

        $arResult = [
            'deals'=>$deals,
            'total'=>$total,
            'page'=>$page,
            'limit'=>$limit,
            'contractors'=>$contractors,
            'filtres'=>['Filter'=>$filtres]
        ];

        $this->arResult = array_merge($this->arResult,$arResult);


        return "deals/deals";
    }


    public function dealsAction(){

        $params = array();

        $this->arResult=[
            'pageName'=>'deals',
            'pageTitle'=>"Заявки"
        ];
        return $this->dealFilter($params);
    }



    public function draftdealsAction(){


        $params['filter']['IS_DRAFT'] = true;
        $this->arResult=[
            'pageName'=>'deals',
            'pageTitle'=>"Заявки"
        ];
        return $this->dealFilter($params);
    }







    public function completeddealsAction(){

        $this->arResult=[
            'pageName'=>'deals',
            'pageTitle'=>"Заявки"
        ];

        $params['filter']['COMPLETED'] = true;
        return $this->dealFilter($params);
    }





    public function archiveAction(){

        $this->arResult=[
            'pageName'=>'deals',
            'pageTitle'=>"Заявки"
        ];
        $params['filter']['IS_DELETED'] = true;
        return $this->dealFilter($params);
    }


    public function searchdealsAction(){

        $this->arResult=[
            'pageName'=>'deals',
            'pageTitle'=>"Заявки"
        ];
        $params = [];
        return $this->dealFilter($params);
    }


    public function viewdealAction(){
        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        $deal = null;
        $user_id = CUser::GetID();
        
        if(isset($request['id'])){
            $deal = Deals::getDeals((int)$request['id']);
            $routes = Routes::getRoutes((int)$request['id']);
            $costs = DealCostings::getCosts((int)$request['id']);
        }

        if(!$deal || !isset($deal['ID'])){
            LocalRedirect($this->getUrl("deals"));
        }

        

        $this->arResult = [
            'deal'=>$deal,
            'routes'=>$routes,
            'costs'=>$costs
        ];

        return "deals/view";
    }





    



    public function billsAction(){
        $files = User::getFiles(DealFileType::FILE_BILL);

        $this->arResult=[
            'files'=>$files,
            'type'=>DealFileType::FILE_BILL,
            'pageTitle'=>'Счета'
        ];

        return 'files';
    }



    public function actsAction(){
        $files = User::getFiles(DealFileType::FILE_ACT);

        $this->arResult=[
            'files'=>$files,
            'type'=>DealFileType::FILE_ACT,
            'pageTitle'=>'Акты'
        ];

        return 'files';
    }


    public function invoicesAction(){
        $files = User::getFiles(DealFileType::FILE_INVOICE);

        $this->arResult=[
            'files'=>$files,
            'type'=>DealFileType::FILE_INVOICE,
            'pageTitle'=>'Счета фактуры'
        ];

        return 'files';
    }



    public function tthAction(){
        $files = User::getFiles(DealFileType::FILE_TTH);

        $this->arResult=[
            'files'=>$files,
            'type'=>DealFileType::FILE_TTH,
            'pageTitle'=>'Товаро-транспортные документы'
        ];

        return 'files';
    }









    public function docsAction(){
        $contract_nds = Settings::getByName("contract_nds");
        $contract_nonds = Settings::getByName("contract_nonds");

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        $path = ALI_CONTRACT_PATH;
        if(isset($request['d']) && (int)$request['d']){
            $file = Settings::getById((int)$request['d']);

            if($file && isset($file['VALUE']) && file_exists($path.$file['VALUE'])){
                    $fname = $file['VALUE'];
                    $parts = explode(".", $fname);
                    $format = reset(array_reverse($parts));

                    global $APPLICATION;
                    $APPLICATION->RestartBuffer();

                    header("Content-type; text/pdf; charset='utf-8'");
                    header("Cache-Control: no-cache");

                    if($format == "doc" || $format == "docx"){
                        header("Content-Description: File Transfer");
                        header("Content-Disposition: attachment; filename=$fname");
                        header("Content-Type: application/msword");
                    }else{
                        header("Content-Type: application/$format");
                    }
                    
                    
                    header("Content-Transfer-Encoding: binary");
                    readfile($path.$fname);
                    exit;

                }
        }


        $this->arResult=[
            'contract_nds'=>$contract_nds,
            'contract_nonds'=>$contract_nonds,
            'pageTitle'=>'Договоры',
            'path'=>$path
        ];

        return 'docs';
    }








    public function getDealFileAction(){

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        if(isset($request['id']) && (int)$request['id']){
            $file = DealsSchemaTable::getRowById((int)$request['id']);

            if(isset($file['ID']) && $file['ID'] && isset($file['PRINT_FORM'])){
                $path = ALI_DEAL_FILES;

                if(file_exists($path.$file['PRINT_FORM'])){
                   
                    $fname = $file['PRINT_FORM'];

                    $parts = explode(".", $fname);
                    $format = strtolower(reset(array_reverse($parts)));

                    $mimeType = MimeTypes::getMimeTypeByExt($format);

                    global $APPLICATION;
                    $APPLICATION->RestartBuffer();
                    
                    header("Content-type; text/pdf; charset='utf-8'");
                    header("Cache-Control: no-cache");
                    header("Content-Type: $mimeType");
                    if($format != 'pdf'){
                        header("Content-Description: File Transfer");
                        header("Content-Disposition: attachment; filename=".$file['NAME'].'.'.$format);
                    }
                    
                    header("Content-Transfer-Encoding: binary");
                    readfile($path.$fname);
                    exit;

                }
            }
                
            $this->arResult=[
                'error'=>"Файл не найден!"
            ];
            return "error";
        }
        
        LocalRedirect($this->getUrl());
    }







    public function downloadFileAction(){

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        if(isset($request['f']) && (int)$request['f']){
            $file = User::getDealFile($request['f']);

            if(isset($file['ID']) && $file['ID'] && isset($file['FILE'])){
                $path = DealFileType::getFilePath($file['FILE_TYPE']);
               
                if(file_exists($path.$file['FILE'])){
                    $fname = $file['FILE'];
                    global $APPLICATION;
                    $APPLICATION->RestartBuffer();

                    header("Content-type; text/pdf; charset='utf-8'");
                    header("Cache-Control: no-cache");
                    // header("Content-Description: File Transfer");
                    // header("Content-Disposition: attachment; filename=$fname");
                    header("Content-Type: application/pdf");
                    header("Content-Transfer-Encoding: binary");
                    readfile($path.$fname);
                    exit;

                }
            }
                
            $this->arResult=[
                'error'=>"Файл не найден!"
            ];
            return "error";
        }
        
        LocalRedirect($this->getUrl());
    }




    public function reportAction(){

        $contractors = User::getCurrentUserIntegratedContractors();

        if(!is_array($contractors) || !count($contractors)){
            LocalRedirect($this->getUrl());
        }

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        $contrs_uuids = ArrayHelper::map($contractors,'ID','INTEGRATED_ID');
        $contrs_ids = ArrayHelper::map($contractors,'ID','ID');
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

            $result = Sverka1C::get($parameters);
            
            if($result->isSuccess()){
                $revice_id = $result->getId();

                $revice = ReviseSchemaTable::getRow(['select'=>['*'],'filter'=>['CONTRACTOR_ID'=>$request['contractor'],'ID'=>$revice_id]]);
            }else{
                $errors = $result->getErrorMessages();
            }
        }else{
            $oldRevises = ReviseSchemaTable::getList(['select'=>['*'],'filter'=>['CONTRACTOR_ID'=>$contrs_ids],'order'=>['CREATED_AT'=>'DESC']])->fetchAll();
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



    public function downloadReviceAction(){


        $contractors = User::getCurrentUserIntegratedContractors();

        if(!is_array($contractors) || !count($contractors)){
            LocalRedirect($this->getUrl());
        }
        $contrs_ids = ArrayHelper::map($contractors,'ID','ID');

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        if(isset($request['revice']) && (int)$request['revice']){
            $file = ReviseSchemaTable::getRow(['select'=>['*'],'filter'=>['CONTRACTOR_ID'=>$contrs_ids,'ID'=>(int)$request['revice']]]);

            if(isset($file['ID']) && $file['ID'] && isset($file['FILE'])){
                $path = ALI_REVISES_PATH;
               
                if(file_exists($path.$file['FILE'])){

                    global $APPLICATION;
                    $APPLICATION->RestartBuffer();

                    header("Content-type; text/pdf; charset='utf-8'");
                    header("Cache-Control: no-cache");
                    // header("Content-Description: File Transfer");
                    // header("Content-Disposition: attachment; filename=АктСверки");
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
    }

}
?>