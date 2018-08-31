<?php


use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config as Conf;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Entity\Base;
use \Bitrix\Main\Application;

Loc::loadMessages(__FILE__);
Class ali_logistic extends CModule
{
    var $exclusionAdminFiles;
    var $errors = array();
    var $pages = array();

	function __construct()
	{
		$arModuleVersion = array();
		include(__DIR__."/version.php");

        $this->exclusionAdminFiles=array(
            '..',
            '.',
            'menu.php',
            'operation_description.php',
            'task_description.php',
            'top.php',
            'bottom.php',
        );

        $this->pages = array(
            'alkserver',
            'alk'
        );

        $this->MODULE_ID = 'ali.logistic';
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = Loc::getMessage("ALI_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("ALI_MODULE_DESC");

		$this->PARTNER_NAME = Loc::getMessage("ALI_PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("ALI_PARTNER_URI");

        $this->MODULE_SORT = 1;
        $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS='Y';
        $this->MODULE_GROUP_RIGHTS = "Y";
	}

    //Определяем место размещения модуля
    public function GetPath($notDocumentRoot=false)
    {
        
        if($notDocumentRoot){
            return "/bitrix/modules/".$this->MODULE_ID;
        }else{
            return dirname(__DIR__);
        }
    }

    //Проверяем что система поддерживает D7
    public function isVersionD7()
    {
        return CheckVersion(\Bitrix\Main\ModuleManager::getVersion('main'), '14.00.00');
    }

    function InstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        if(!Application::getConnection(\Ali\Logistic\Schemas\CompaniesSchemaTable::getConnectionName())->isTableExists(
            Base::getInstance('\Ali\Logistic\Schemas\CompaniesSchemaTable')->getDBTableName()
            )
        )
        {
            Base::getInstance('\Ali\Logistic\Schemas\CompaniesSchemaTable')->createDbTable();
        }

        if(!Application::getConnection(\Ali\Logistic\Schemas\ContractorsSchemaTable::getConnectionName())->isTableExists(
            Base::getInstance('\Ali\Logistic\Schemas\ContractorsSchemaTable')->getDBTableName()
            )
        )
        {
            Base::getInstance('\Ali\Logistic\Schemas\ContractorsSchemaTable')->createDbTable();
        }


        if(!Application::getConnection(\Ali\Logistic\Schemas\CompanyEmployeeSchemaTable::getConnectionName())->isTableExists(
            Base::getInstance('\Ali\Logistic\Schemas\CompanyEmployeeSchemaTable')->getDBTableName()
            )
        )
        {
            Base::getInstance('\Ali\Logistic\Schemas\CompanyEmployeeSchemaTable')->createDbTable();
        }


        if(!Application::getConnection(\Ali\Logistic\Schemas\DealsSchemaTable::getConnectionName())->isTableExists(
            Base::getInstance('\Ali\Logistic\Schemas\DealsSchemaTable')->getDBTableName()
            )
        )
        {
            Base::getInstance('\Ali\Logistic\Schemas\DealsSchemaTable')->createDbTable();
        }


        if(!Application::getConnection(\Ali\Logistic\Schemas\RoutesSchemaTable::getConnectionName())->isTableExists(
            Base::getInstance('\Ali\Logistic\Schemas\RoutesSchemaTable')->getDBTableName()
            )
        )
        {
            Base::getInstance('\Ali\Logistic\Schemas\RoutesSchemaTable')->createDbTable();
        }


        if(!Application::getConnection(\Ali\Logistic\Schemas\DealCostingsSchemaTable::getConnectionName())->isTableExists(
            Base::getInstance('\Ali\Logistic\Schemas\DealCostingsSchemaTable')->getDBTableName()
            )
        )
        {
            Base::getInstance('\Ali\Logistic\Schemas\DealCostingsSchemaTable')->createDbTable();
        }



        if(!Application::getConnection(\Ali\Logistic\Schemas\DealFilesSchemaTable::getConnectionName())->isTableExists(
            Base::getInstance('\Ali\Logistic\Schemas\DealFilesSchemaTable')->getDBTableName()
            )
        )
        {
            Base::getInstance('\Ali\Logistic\Schemas\DealFilesSchemaTable')->createDbTable();
        }
        

        $this->installDbProgramming();

        return true;
    }

    function installDbProgramming(){

        global $DB;

        // $file = $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/{$this->MODULE_ID}/install/db/mysql/get_new_msg.sql";
        // $sql = file_get_contents($file);
        // $DB->QueryLong($sql);

        
    }

    function UnInstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        Application::getConnection(\Ali\Logistic\Schemas\CompaniesSchemaTable::getConnectionName())->
             queryExecute('drop table if exists '.Base::getInstance('\Ali\Logistic\Schemas\CompaniesSchemaTable')->getDBTableName());

        Application::getConnection(\Ali\Logistic\Schemas\ContractorsSchemaTable::getConnectionName())->
             queryExecute('drop table if exists '.Base::getInstance('\Ali\Logistic\Schemas\ContractorsSchemaTable')->getDBTableName());

        Application::getConnection(\Ali\Logistic\Schemas\CompanyEmployeeSchemaTable::getConnectionName())->
             queryExecute('drop table if exists '.Base::getInstance('\Ali\Logistic\Schemas\CompanyEmployeeSchemaTable')->getDBTableName());

        Application::getConnection(\Ali\Logistic\Schemas\DealsSchemaTable::getConnectionName())->
             queryExecute('drop table if exists '.Base::getInstance('\Ali\Logistic\Schemas\DealsSchemaTable')->getDBTableName());


        Application::getConnection(\Ali\Logistic\Schemas\RoutesSchemaTable::getConnectionName())->
             queryExecute('drop table if exists '.Base::getInstance('\Ali\Logistic\Schemas\RoutesSchemaTable')->getDBTableName());

        Application::getConnection(\Ali\Logistic\Schemas\DealCostingsSchemaTable::getConnectionName())->
             queryExecute('drop table if exists '.Base::getInstance('\Ali\Logistic\Schemas\DealCostingsSchemaTable')->getDBTableName());

        Application::getConnection(\Ali\Logistic\Schemas\DealFilesSchemaTable::getConnectionName())->
             queryExecute('drop table if exists '.Base::getInstance('\Ali\Logistic\Schemas\DealFilesSchemaTable')->getDBTableName());

            
        global $DB, $DBType, $APPLICATION;

        //$DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/{$this->MODULE_ID}/install/db/mysql/uninstall.sql");
        
    }

	function InstallEvents()
	{

        
        // \Bitrix\Main\EventManager::getInstance()->registerEventHandler($this->MODULE_ID, 'TestEventD7', $this->MODULE_ID, '\Academy\D7\Event', 'eventHandler');
	}

	function UnInstallEvents()
	{
        //\Bitrix\Main\EventManager::getInstance()->unRegisterEventHandler($this->MODULE_ID, 'TestEventD7', $this->MODULE_ID, '\Academy\D7\Event', 'eventHandler');
	}

	function InstallFiles($arParams = array())
	{  


        $path=$this->GetPath()."/install/components";

        if(\Bitrix\Main\IO\Directory::isDirectoryExists($path))
            CopyDirFiles($path, $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
        else
            throw new \Bitrix\Main\IO\InvalidPathException($path);

        $pathPage = $this->GetPath()."/install/pages";
        if(\Bitrix\Main\IO\Directory::isDirectoryExists($pathPage))
            CopyDirFiles($pathPage, $_SERVER["DOCUMENT_ROOT"]."/", true, true);
        else
            throw new \Bitrix\Main\IO\InvalidPathException($pathPage);


        if (\Bitrix\Main\IO\Directory::isDirectoryExists($path = $this->GetPath() . '/admin'))
        {
            CopyDirFiles($this->GetPath() . "/install/admin/", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin"); //если есть файлы для копирования
            if ($dir = opendir($path))
            {
                while (false !== $item = readdir($dir))
                {
                    if (in_array($item,$this->exclusionAdminFiles))
                        continue;
                    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.$this->MODULE_ID.'_'.$item,
                        '<'.'? require($_SERVER["DOCUMENT_ROOT"]."'.$this->GetPath(true).'/admin/'.$item.'");?'.'>');
                }
                closedir($dir);
            }
        }

        return true;
	}

	function UnInstallFiles()
	{
        

        

        foreach ($this->pages as $page) {
           \Bitrix\Main\IO\Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/{$page}/");
            \Bitrix\Main\IO\Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"] . "/{$page}/");
        }
        

        

        if (\Bitrix\Main\IO\Directory::isDirectoryExists($path = $this->GetPath() . '/admin')) {
            DeleteDirFiles($_SERVER["DOCUMENT_ROOT"] . $this->GetPath() . '/install/admin/', $_SERVER["DOCUMENT_ROOT"] . '/bitrix/admin');
            if ($dir = opendir($path)) {
                while (false !== $item = readdir($dir)) {
                    if (in_array($item, $this->exclusionAdminFiles))
                        continue;
                    \Bitrix\Main\IO\File::deleteFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/' . $this->MODULE_ID . '_' . $item);
                }
                closedir($dir);
            }
        }
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION;
        if($this->isVersionD7())
        {
            \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);

            $this->InstallDB();
            $this->InstallEvents();
            $this->InstallFiles();

            #работа с .settings.php
            $configuration = Conf\Configuration::getInstance();
            $ali_logistic_module=$configuration->get('ali_logistic_module');
            $ali_logistic_module['install']=$ali_logistic_module['install']+1;
            $configuration->add('ali_logistic_module', $ali_logistic_module);
            $configuration->saveConfiguration();
            #работа с .settings.php
        }
        else
        {
            $APPLICATION->ThrowException(Loc::getMessage("ALI_INSTALL_ERROR_VERSION"));
        }

        $APPLICATION->IncludeAdminFile(Loc::getMessage("ALI_INSTALL_TITLE"), $this->GetPath()."/install/step.php");
	}

	function DoUninstall()
	{

        global $APPLICATION;

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        if($request["step"]<2)
        {
            $APPLICATION->IncludeAdminFile(Loc::getMessage("ALI_UNINSTALL_TITLE"), $this->GetPath()."/install/unstep1.php");
        }
        elseif($request["step"]==2)
        {
            $this->UnInstallFiles();
			$this->UnInstallEvents();

            if($request["savedata"] != "Y")
                $this->UnInstallDB();

            \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);

            #работа с .settings.php
            $configuration = Conf\Configuration::getInstance();
            $ali_logistic_module=$configuration->get('ali_logistic_module');
            $ali_logistic_module['uninstall']=$ali_logistic_module['uninstall']+1;
            $configuration->add('ali_logistic_module', $ali_logistic_module);
            $configuration->saveConfiguration();
            #работа с .settings.php
            
            $APPLICATION->IncludeAdminFile(Loc::getMessage("ALI_UNINSTALL_TITLE"), $this->GetPath()."/install/unstep2.php");
        }
	}

    function GetModuleRightList()
    {
        return array(
            "reference_id" => array("D","K","S","W"),
            "reference" => array(
                "[D] ".Loc::getMessage("ALI_DENIED"),
                "[K] ".Loc::getMessage("ALI_READ_COMPONENT"),
                "[S] ".Loc::getMessage("ALI_WRITE_SETTINGS"),
                "[W] ".Loc::getMessage("ALI_FULL"))
        );
    }
}
?>