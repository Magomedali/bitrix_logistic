<?php


namespace Ali\Logistic;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;
use Ali\Logistic\Schemas\DealCostingsSchemaTable;

class DealCostings
{   

	public static function defaultSelect(){
        return array(
            '*'
        );
    }

    public static function getCosts($deal_id){

        $local_params = array(
            'select'=>self::defaultSelect()
        );
        $params = array_merge($local_params,$parameters);
        
        $params['filter']['DEAL_ID']=$deal_id;
        
        
        return DealCostingsSchemaTable::getList($params)->fetchAll();
    }

    
}