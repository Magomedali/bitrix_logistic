<?php

namespace Ali\Logistic\Dictionary;

abstract class Dictionary{

	/**
	* array
	*/
	protected static $labels = array();



	public static function getLabels($code){
        if(array_key_exists($code, static::$labels))
            return static::$labels[$code];


        return static::$labels;
    }
}