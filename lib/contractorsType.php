<?php


namespace Ali\Logistic;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;

class ContractorsType{

	const IP = 1;
	const LEGAL = 2;


	protected static $labels = array(
		self::IP=>"ИП",
		self::LEGAL=>"Юридическое лицо",
	); 



	public static function getLabels($code){
		if(in_array($code, self::$labels))
			return self::$labels[$code];


		return self::$labels;
	}

}