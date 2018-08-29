<?php


namespace Ali\Logistic\Dictionary;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;

class DealStates extends \Ali\Logistic\Dictionary\Dictionary{

	const CREATED = 1;
	const IN_CONFIRMING = 2;
	const IN_PLANNING = 3;
	const ON_PERFOMANCE= 4;
	const CARGO_DELIVERED = 5;
	const EXECUTED = 6;
	const CANSELED = 7;


	protected static $labels = array(
		self::CREATED=>"Создана",
		self::IN_CONFIRMING=>"В обработке",
		self::IN_PLANNING=>"В планировании",
		self::ON_PERFOMANCE=>"На исполнении",
		self::CARGO_DELIVERED=>"Груз доставлен",
		self::EXECUTED=>"Выполнено",
		self::CANSELED=>"Отменено",
	); 




}