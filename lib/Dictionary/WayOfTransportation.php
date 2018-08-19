<?php


namespace Ali\Logistic\Dictionary;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;

class WayOfTransportation extends \Ali\Logistic\Dictionary\Dictionary{

    const DEDICATED_TRANSPORT = 1;
    const CONSOLIDATED_CARGO = 2;
    const AFTERLOADS = 3;


    protected static $labels = array(
        self::DEDICATED_TRANSPORT=>"Выделенный транспорт",
        self::CONSOLIDATED_CARGO=>"Сборный груз",
        self::AFTERLOADS=>"Догруз",
    ); 



    

}