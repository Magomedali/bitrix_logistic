<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserUtils;
use Bitrix\Main\UserTable;
use Ali\Logistic\Companies;

use \Bitrix\Main\Application;
use Bitrix\Main\Entity\Result;


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


            return $this->menu_items;
        }

        return array();
    }

}