<?php


namespace Ali\Logistic;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;
use Ali\Logistic\Schemas\RoutesSchemaTable;
use Bitrix\Main\Type\DateTime;

class Routes
{   

	public static function defaultSelect(){
        return array(
            '*'
        );
    }

	public static function save($data){
       
        $data['START_AT'] = isset($data['START_AT']) ? DateTime::createFromTimestamp(strtotime($data['START_AT'])) : null;
        $data['FINISH_AT'] = isset($data['FINISH_AT']) ? DateTime::createFromTimestamp(strtotime($data['FINISH_AT'])) : null;

        $primary = isset($data['ID']) ? ['ID'=>$data['ID']] : null;
        if($primary)
            $result = RoutesSchemaTable::update($primary,$data);
        else
            $result = RoutesSchemaTable::add($data);
        

        if($result->isSuccess()){
            $data['ID']=$result->getId();
        }

        return $result;
    }
    


    public static function getRoutes($deal_id){

        $local_params = array(
            'select'=>self::defaultSelect()
        );
        $params = array_merge($local_params,$parameters);
        
        $params['filter']['DEAL_ID']=$deal_id;
        
        
        return RoutesSchemaTable::getList($params)->fetchAll();
    }

    public static  function getRoute($id){

    	if(!$id) return array();


        $local_params = array(
            'select'=>self::defaultSelect()
        );
        $params = array_merge($local_params,$parameters);
        
        
            
        $local_params['filter']['ID']=$id;

        return RoutesSchemaTable::getRow($params);
        

    }


    public static function  delete($route_id,$user_id){
    	if(empty($route_id) || empty($user_id)) return false;

    	$route = RoutesSchemaTable::getRow(array(
    		"select"=>array(
    			'ID','OWNER_ID'
    		),
    		'filter'=>array(
    			"ID"=>$route_id
    		)
    	));

    	if(is_array($route) && isset($route['OWNER_ID']) && $route['OWNER_ID'] == (int)$user_id){
    		$res = RoutesSchemaTable::delete($route_id);
    		return $res->isSuccess();
    	}

    	return false;

    }
    
}