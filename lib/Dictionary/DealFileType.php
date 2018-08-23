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


	protected static $labels = array(
		self::FILE_BILL=>"Счет",
		self::FILE_INVOICE=>"Счет фактура",
		self::FILE_ACT=>"Акт",
		self::FILE_CONTRACT=>"Договор",
		self::FILE_DRIVER_ATTORNEY=>"Доверенность на водителя",
		self::FILE_PRINT_FORM=>"Печатная форма заявки",
	); 

}