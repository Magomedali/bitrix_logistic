<?php


namespace Ali\Logistic\Dictionary;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;

class Documents extends \Ali\Logistic\Dictionary\Dictionary{

	const PROCURATION = 1;
	const MEDICAL_BOOK = 2;
	const SANITIZATION= 3;


	protected static $labels = array(
		self::PROCURATION=>"Доверенность",
		self::MEDICAL_BOOK=>"Медкнижка",
		self::SANITIZATION=>"Санобработка",
	); 




}