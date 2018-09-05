<?php


namespace Ali\Logistic;

use Ali\Logistic\Schemas\SettingsSchemaTable;
use Ali\Logistic\helpers\ArrayHelper;

class Settings
{   

    public static function getByName($name){
        return SettingsSchemaTable::getRow([
            'select'=>['*'],
            'filter'=>['=NAME'=>$name]
        ]);
    }


    public static function getById($id){
        return SettingsSchemaTable::getRow([
            'select'=>['*'],
            'filter'=>['=ID'=>$id]
        ]);
    }
}