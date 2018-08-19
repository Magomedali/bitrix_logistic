<?php


namespace Ali\Logistic\Dictionary;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;

class DealStates extends \Ali\Logistic\Dictionary\Dictionary{

	const CREATED = 1;
	const IN_PLANNING = 2;
	const ON_PERFOMANCE= 3;
	const CARGO_DELIVERED = 4;
	const EXECUTED = 5;
	const CANSELED = 6;


	protected static $labels = array(
		self::CREATED=>"Создана",
		self::IN_PLANNING=>"В планировании",
		self::ON_PERFOMANCE=>"На исполнении",
		self::CARGO_DELIVERED=>"Груз доставлен",
		self::EXECUTED=>"Выполнено",
		self::CANSELED=>"Отменено",
	); 




}