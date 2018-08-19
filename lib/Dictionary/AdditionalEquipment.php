<?php


namespace Ali\Logistic\Dictionary;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;

class AdditionalEquipment extends \Ali\Logistic\Dictionary\Dictionary{

	const CONICS = 1;
	const SIDE = 2;
	const BACKSIDE = 3;


	protected static $labels = array(
		self::CONICS=>"Коники",
		self::SIDE=>"Бок",
		self::BACKSIDE=>"Зад",
	); 




}