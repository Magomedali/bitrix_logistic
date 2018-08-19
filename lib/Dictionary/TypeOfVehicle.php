<?php


namespace Ali\Logistic\Dictionary;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;

class TypeOfVehicle extends \Ali\Logistic\Dictionary\Dictionary{

	const AWNING = 1;
	const REF = 2;
	const THERMOS = 3;


	protected static $labels = array(
		self::AWNING=>"Тент",
		self::REF=>"Реф",
		self::THERMOS=>"Термос",
	); 

}