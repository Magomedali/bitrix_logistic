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
            "Создать заявку",
            "/personal/index.php?r=dealform",
            array(),
            "",
            ""
        ),
        array(
            "Текущие заявки",
            "/personal/index.php?r=deals",
            array(),
            "",
            ""
        ),
        array(
            "Выполненые заявки",
            "/personal/index.php?r=completeddeals",
            array(),
            "",
            ""
        ),
        array(
            "Поиск заявок",
            "/personal/index.php?r=searchdeals",
            array(),
            "",
            ""
        ),
        array(
            "Черновики",
            "/personal/index.php?r=draftdeals",
            array(),
            "",
            ""
        ),
        array(
            "Архив",
            "/personal/index.php?r=archive",
            array(),
            "",
            ""
        ),

        array(
            "Счета",
            "/personal/index.php?r=bills",
            array(),
            "",
            ""
        ),


        array(
            "Акты",
            "/personal/index.php?r=acts",
            array(),
            "",
            ""
        ),

        array(
            "Счета-фактуры",
            "/personal/index.php?r=invoices",
            array(),
            "",
            ""
        ),

        array(
            "Товаро-транспортные документы",
            "/personal/index.php?r=tth",
            array(),
            "",
            ""
        ),

        array(
            "Договоры",
            "/personal/index.php?r=docs",
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
            $contractors = User::getCurrentUserContractors();
            if(is_array($contractors) && count($contractors)){

                $menus = array_merge($menus,$this->protected_menu);
            }


            return $menus;
        }

        return array();
    }

}