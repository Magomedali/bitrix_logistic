<?php
IncludeModuleLangFile(__FILE__);

if(\Bitrix\Main\ModuleManager::isModuleInstalled('ali.logistic')){

  $aMenu = [
    "parent_menu" => "global_menu_content", // поместим в раздел "Сервис"
    "sort"        => 100,                    // вес пункта меню
    "url"         => "ali.logistic_main.php",  // ссылка на пункте меню
    "text"        => GetMessage("ADMIN_ALI_LOGISTIC_MENU_TITLE"),       // текст пункта меню
    "title"       => GetMessage("ADMIN_ALI_LOGISTIC_MENU_TITLE"), // текст всплывающей подсказки
   //"icon"        => "form_menu_icon", // малая иконка
   //"page_icon"   => "form_page_icon", // большая иконка
    "items_id"    => "menu_chat",  // идентификатор ветви
    "items"       => array(),          // остальные уровни меню сформируем ниже.
  ];

  $aMenu['items'][]=[
        "url" => "ali.logistic_loadcustomers.php",
        "text"        => GetMessage("ADMIN_ALI_LOGISTIC_MENU_LOAD_CUSTOMERS"),
        "title"       => GetMessage("ADMIN_ALI_LOGISTIC_MENU_LOAD_CUSTOMERS"),
  ];

  $aMenu['items'][]=[
        "url" => "ali.logistic_settingscustomers.php",
        "text"        => GetMessage("ADMIN_ALI_LOGISTIC_MENU_SETTING_CUSTOMERS"),
        "title"       => GetMessage("ADMIN_ALI_LOGISTIC_MENU_SETTING_CUSTOMERS"),
  ];
}else{
  $aMenu = false;
}


return $aMenu;