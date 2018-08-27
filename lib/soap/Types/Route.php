<?php

namespace Ali\Logistic\soap\Types;

use Ali\Logistic\Dictionary\ContractorsType;
use Ali\Logistic\Schemas\ContractorsSchemaTable;


class Route 
{
	

	public $typeshipment;
	public $datefrom;
	public $timeby;
	public $location;
	public $shipper;
	public $contactname;
	public $numberphone;
	public $comment;


	function __construct($route)
	{
		$this->typeshipment = $route['typeshipment'];
		$this->datefrom = $route['datefrom'];
		$this->timeby = $route['timeby'];
		$this->location = $route['location'];
		$this->shipper = $route['shipper'];
		$this->contactname = $route['contactname'];
		$this->numberphone = $route['numberphone'];
		$this->comment = $route['comment'];
	}
}