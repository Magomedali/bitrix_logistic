<?php

namespace Ali\Logistic\Dictionary;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\Application;

class RoutesKind extends \Ali\Logistic\Dictionary\Dictionary{

	const LOADING = 1;
	const UNLOADING = 2;


	protected static $labels = array(
		self::LOADING=>"Загрузка",
		self::UNLOADING=>"Выгрузка",
	); 

}