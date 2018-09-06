<?php

namespace Ali\Logistic\Dictionary;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\Application;

class HowPacked extends \Ali\Logistic\Dictionary\Dictionary{

	const PALLETS = 1;
	const BARREL = 2;
	const CASE = 3;
	const IN_BULK = 4;
	const BIG_BAG = 5;
	const COIL = 6;
	const BOX = 7;


	protected static $labels = array(
		self::PALLETS=>"палет",
		self::BARREL=>"бочка",
		self::CASE=>"коробка",
		self::IN_BULK=>"навалом",
		self::BIG_BAG=>"биг-бэг",
		self::COIL=>"катушка",
		self::BOX=>"ящик",
	); 

}