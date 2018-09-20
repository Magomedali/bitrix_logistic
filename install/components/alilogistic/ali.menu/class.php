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
		    "/alk/",
		    array(),
		    "",
		    ""
		),
        array(
            "Мои организации",
            "/alk/organisations",
            array(),
            "",
            ""
        )
	);


    public $protected_menu = array(
        array(
            "Создать заявку",
            "/alk/dealform",
            array(),
            "",
            ""
        ),
        array(
            "Заявки",
            "/alk/deals",
            array(),
            "",
            ""
        ),

        array(
            "Счета",
            "/alk/bills",
            array(),
            "",
            ""
        ),


        array(
            "Акты",
            "/alk/acts",
            array(),
            "",
            ""
        ),
        array(
            "Акты сверок",
            "/alk/report",
            array(),
            "",
            ""
        ),
        array(
            "Счета-фактуры",
            "/alk/invoices",
            array(),
            "",
            ""
        ),

        array(
            "Товаро-транспортные документы",
            "/alk/tth",
            array(),
            "",
            ""
        ),

        array(
            "Договоры",
            "/alk/docs",
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
            $contractors = User::getCurrentUserIntegratedContractors();
            if(is_array($contractors) && count($contractors)){

                $menus = array_merge($menus,$this->protected_menu);
            }


            return $menus;
        }

        return array();
    }

}