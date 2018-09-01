<?php

define("ALI_MODULE_NAME","ali.logistic");
define("ALI_COMPONENTS_NS","alilogistic");

define("ALI_FILE_PATH", $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/".ALI_MODULE_NAME."/files/");

define("ALI_FILE_BILLS_PATH", $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/".ALI_MODULE_NAME."/files/bills/");
define("ALI_FILE_INVOICES_PATH", $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/".ALI_MODULE_NAME."/files/invoices/");
define("ALI_FILE_ACTS_PATH", $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/".ALI_MODULE_NAME."/files/acts/");
define("ALI_FILE_CONTRACTS_PATH", $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/".ALI_MODULE_NAME."/files/contracts/");
define("ALI_FILE_DRIVER_ATTORNEY_PATH", $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/".ALI_MODULE_NAME."/files/driver_attorney/");
define("ALI_FILE_PRINT_FORM_PATH", $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/".ALI_MODULE_NAME."/files/print_form/");
define("ALI_FILE_TTH_PATH", $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/".ALI_MODULE_NAME."/files/tth/");


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