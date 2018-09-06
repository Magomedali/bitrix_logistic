<?php


namespace Ali\Logistic\Dictionary;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;

class Documents extends \Ali\Logistic\Dictionary\Dictionary{

	const MEDICAL_BOOK = 1;
	const SANITIZATION= 2;


	protected static $labels = array(
		self::MEDICAL_BOOK=>"Медкнижка",
		self::SANITIZATION=>"Санобработка",
	); 




}