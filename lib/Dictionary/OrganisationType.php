<?php

namespace Ali\Logistic\Dictionary;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;

class OrganisationType extends \Ali\Logistic\Dictionary\Dictionary{


	const WITHOUT_NDS = 0;
	const WITH_NDS = 1;


	protected static $labels = array(
		self::WITHOUT_NDS=>"С НДС",
		self::WITH_NDS=>"Без НДС",
	); 

}