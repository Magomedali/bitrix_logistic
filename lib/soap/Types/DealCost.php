DealCost.php
<?php

namespace Ali\Logistic\soap\Types;

use Ali\Logistic\Dictionary\ContractorsType;
use Ali\Logistic\Schemas\ContractorsSchemaTable;


class DealCost 
{
	

	public $servicetype;
	public $cost;
	public $quantity;
	public $sum;


	function __construct($costing)
	{
		$this->servicetype = $costing['servicetype'];
		$this->cost = $costing['cost'];
		$this->quantity = $costing['quantity'];
		$this->sum = $costing['sum'];
	}
}