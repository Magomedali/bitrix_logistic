<?php

namespace Ali\Logistic\Dictionary;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;

class DealFileType extends \Ali\Logistic\Dictionary\Dictionary{

	const FILE_BILL = 1;
	const FILE_INVOICE = 2;
	const FILE_ACT = 3;
	const FILE_CONTRACT = 4;
	const FILE_DRIVER_ATTORNEY = 5;
	const FILE_PRINT_FORM = 6;
	const FILE_TTH = 7;


	protected static $labels = array(
		self::FILE_BILL=>"Счет",
		self::FILE_INVOICE=>"Счет фактура",
		self::FILE_ACT=>"Акт",
		self::FILE_CONTRACT=>"Договор",
		self::FILE_DRIVER_ATTORNEY=>"Доверенность на водителя",
		self::FILE_PRINT_FORM=>"Печатная форма заявки",
		self::FILE_TTH=>"Товаро-транспортные документы",
	); 



	public static function getFilePath($code){

		$path = null;
				switch ($code) {
                   case self::FILE_BILL:
                        $path = ALI_FILE_BILLS_PATH;
                        break;
                    case self::FILE_INVOICE:
                        $path = ALI_FILE_INVOICES_PATH;
                        break;
                    case self::FILE_ACT:
                        $path = ALI_FILE_ACTS_PATH;
                        break;
                    case self::FILE_CONTRACT:
                        $path = ALI_FILE_CONTRACTS_PATH;
                        break;
                    case self::FILE_DRIVER_ATTORNEY:
                        $path = ALI_FILE_DRIVER_ATTORNEY_PATH;
                        break;
                    case self::FILE_PRINT_FORM:
                        $path = ALI_FILE_PRINT_FORM_PATH;
                        break;
                    case self::FILE_TTH:
                        $path = ALI_FILE_TTH_PATH;
                        break;
                    
                    default:
                        $path = "";
                        break;
                }
        return $path;
	}

}