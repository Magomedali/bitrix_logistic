<?php

namespace Ali\Logistic;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;
use Ali\Logistic\Schemas\CompanyEmployeeSchemaTable;

class CompanyEmployee{




	public static function getUserIsEmployeeCompanies($user_id){

		$row = CompanyEmployeeSchemaTable::getList(['select'=>['*'],'filter'=>['EMPLOYEE_ID'=>$user_id]])->fetchAll();

		return $row;
	}

}