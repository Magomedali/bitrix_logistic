<?php


namespace Ali\Logistic\Dictionary;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;

class LoadingMethod extends \Ali\Logistic\Dictionary\Dictionary{

	const TOP = 1;
	const SIDE = 2;
	const BACKSIDE = 3;


	protected static $labels = array(
		self::TOP=>"Верх",
		self::SIDE=>"Бок",
		self::BACKSIDE=>"Зад",
	); 




}