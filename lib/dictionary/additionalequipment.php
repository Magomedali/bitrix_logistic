<?php


namespace Ali\Logistic\Dictionary;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;

class AdditionalEquipment extends \Ali\Logistic\Dictionary\Dictionary{

	const CONICS = 1;
	const RAMPS = 2;
	const TAIL_LIFT = 3;
	const BELTS = 4;
	const RACKS = 5;
	const WOODEN_FLOOR = 6;
	const DOPELSTOKI = 7;
	const THERMAL_SENSOR = 8;
	const REF_SEPTUM = 9;


	protected static $labels = array(
		self::CONICS=>"Коники",
		self::RAMPS=>"Аппарели",
		self::TAIL_LIFT=>"Гидроборт",
		self::BELTS=>"Ремни",
		self::RACKS=>"Сдвижные/съемные стойки",
		self::WOODEN_FLOOR=>"Деревянный пол",
		self::DOPELSTOKI=>"Допельштоки",
		self::THERMAL_SENSOR=>"Термодатчик",
		self::REF_SEPTUM=>"Рефперегородка",
	); 




}