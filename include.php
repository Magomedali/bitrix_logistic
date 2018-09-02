<?php

define("ALI_MODULE_NAME","ali.logistic");
define("ALI_COMPONENTS_NS","alilogistic");

define("ALI_FILE_PATH", $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/".ALI_MODULE_NAME."/files/");

define("ALI_FILE_BILLS_PATH", 			ALI_FILE_PATH."/bills/");
define("ALI_FILE_INVOICES_PATH", 		ALI_FILE_PATH."/invoices/");
define("ALI_FILE_ACTS_PATH", 			ALI_FILE_PATH."/acts/");
define("ALI_FILE_CONTRACTS_PATH", 		ALI_FILE_PATH."/contracts/");
define("ALI_FILE_DRIVER_ATTORNEY_PATH", ALI_FILE_PATH."/driver_attorney/");
define("ALI_FILE_PRINT_FORM_PATH", 		ALI_FILE_PATH."/print_form/");
define("ALI_FILE_TTH_PATH", 			ALI_FILE_PATH."/tth/");

define("ALI_DEAL_PRINT_FORM_PATH", 		ALI_FILE_PATH."/dealprintform/");

define("ALI_REVISES_PATH", 		ALI_FILE_PATH."/revises/");



define("ALI_LOG_SOAP_SERVER_PATH", $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/".ALI_MODULE_NAME."/lib/soap/Server/output/");

//Количество сообщении на страницу(на загрузку)
define("MESSAGE_LIMIT", 15);

define("ALI_SMALL_IMAGE_SIZE",525);

define("ALI_MIDDLE_IMAGE_SIZE",550);


//онлайн пользователь
// if($GLOBALS['USER']->IsAuthorized()) {
//     CUser::SetLastActivityDate($GLOBALS['USER']->GetID());
// }




// class AliEvents{
// 	// создаем обработчик события "OnAfterUserRegister"
//     function OnAfterUserRegisterHandler(&$arFields)
//     {
//         // если регистрация успешна то
//         if($arFields["USER_ID"]>0)
//         {	
//         	try{
//         		//Добавляем в таблицу members
// 	            \Social\Chat\MembersTable::addNewMember($arFields["USER_ID"]);
//         	}catch(Exception $e){

//         	}
            
//         }
//         return $arFields;
//     }
// }


// регистрируем обработчик события "OnAfterUserRegister"
//RegisterModuleDependences("main", "OnAfterUserRegister", "social.chat", "AliEvents", "OnAfterUserRegisterHandler");