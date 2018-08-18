<?php


namespace Ali\Logistic;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;
use \Bitrix\Main\Entity\Query;

class User extends UserTable
{   

    public static function hasCompany(){
        global $USER;

    }
}