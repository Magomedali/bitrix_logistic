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
            "/alk/index.php?r=organisations",
            array(),
            "",
            ""
        )
	);


    public $protected_menu = array(
        array(
            "Создать заявку",
            "/alk/index.php?r=dealform",
            array(),
            "",
            ""
        ),
        array(
            "Текущие заявки",
            "/alk/index.php?r=deals",
            array(),
            "",
            ""
        ),
        array(
            "Выполненые заявки",
            "/alk/index.php?r=completeddeals",
            array(),
            "",
            ""
        ),
        array(
            "Поиск заявок",
            "/alk/index.php?r=searchdeals",
            array(),
            "",
            ""
        ),
        array(
            "Черновики",
            "/alk/index.php?r=draftdeals",
            array(),
            "",
            ""
        ),
        array(
            "Архив",
            "/alk/index.php?r=archive",
            array(),
            "",
            ""
        ),

        array(
            "Счета",
            "/alk/index.php?r=bills",
            array(),
            "",
            ""
        ),


        array(
            "Акты",
            "/alk/index.php?r=acts",
            array(),
            "",
            ""
        ),
        array(
            "Акты сверок",
            "/alk/index.php?r=report",
            array(),
            "",
            ""
        ),
        array(
            "Счета-фактуры",
            "/alk/index.php?r=invoices",
            array(),
            "",
            ""
        ),

        array(
            "Товаро-транспортные документы",
            "/alk/index.php?r=tth",
            array(),
            "",
            ""
        ),

        array(
            "Договоры",
            "/alk/index.php?r=docs",
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