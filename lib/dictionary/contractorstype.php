<?php

namespace Ali\Logistic\Dictionary;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;

class ContractorsType extends \Ali\Logistic\Dictionary\Dictionary{

	const IP = 1;
	const LEGAL = 2;


	protected static $labels = array(
		self::IP=>"физлицо",
		self::LEGAL=>"юрлицо",
	); 

}