<?php


namespace Ali\Logistic\Dictionary;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;

class SpecialEquipment extends \Ali\Logistic\Dictionary\Dictionary{

	const MANIPULATOR = 1;
	const WRECKER = 2;
	


	protected static $labels = array(
		self::MANIPULATOR=>"Манипулятор",
		self::WRECKER=>"Эвакуатор",
	); 




}