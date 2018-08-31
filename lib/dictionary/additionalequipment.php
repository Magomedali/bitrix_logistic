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
	const MANIPULATOR = 4;
	const WRECKER = 5;
	const CRANE = 6;


	protected static $labels = array(
		self::CONICS=>"Коники",
		self::RAMPS=>"Аппарели",
		self::TAIL_LIFT=>"Гидроборт",
		self::MANIPULATOR=>"Манипулятор",
		self::WRECKER=>"Эвакуатор",
		self::CRANE=>"Кран",
	); 




}