<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserUtils;
use Bitrix\Main\UserTable;
use Ali\Logistic\Companies;

use \Bitrix\Main\Application;
use Bitrix\Main\Entity\Result;

use Ali\Logistic\User;

class AliMenu extends CBitrixComponent
{

	public $menu_items = array(
		array(
		    "Личный кабинет",
		    "/personal/",
		    array(),
		    "",
		    ""
		),
        array(
            "Мои организации",
            "/personal/index.php?r=organisations",
            array(),
            "",
            ""
        )
	);


    public $protected_menu = array(
        array(
            "Новая заявка",
            "/personal/index.php?r=dealform",
            array(),
            "",
            ""
        ),
        array(
            "Активные заявки",
            "/personal/index.php?r=deals",
            array(),
            "",
            ""
        ),
    );

    protected function checkModules()
    {
        if (!Loader::includeModule('ali.logistic'))
        {
            ShowError(Loc::getMessage('ALI_MODULE_NOT_INSTALLED'));
            return false;
        }

        return true;
    }


    public function executeComponent()
    {

        if($this->checkModules())
        {   
            $context = Application::getInstance()->getContext();
            $request = $context->getRequest();

            $menus = $this->menu_items;
            if(User::hasCurrentUserHasComany()){

                $menus = array_merge($menus,$this->protected_menu);
            }


            return $menus;
        }

        return array();
    }

}