<?php

namespace Ali\Logistic\Dictionary;

abstract class Dictionary{

	/**
	* array
	*/
	protected static $labels = array();



	public static function getLabels($code){
        if(in_array($code, static::$labels))
            return static::$labels[$code];


        return static::$labels;
    }
}