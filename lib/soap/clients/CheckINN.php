<?php

namespace Ali\Logistic\soap\clients;


/**
* 
*/
class CheckINN 
{
	
	protected static $wsdl_url = "http://npchk.nalog.ru/FNSNDSCAWS_2?wsdl";


	protected static $states = array(
			0=>[
				'success'=>true,
				'msg'=>'Налогоплательщик зарегистрирован в ЕГРН и имел статус действующего в указанную дату'
			],
			1=>[
				'success'=>false,
				'msg'=>'Налогоплательщик зарегистрирован в ЕГРН, но не имел статус действующего в указанную дату'
			],
			2=>[
				'success'=>true,
				'msg'=>'Налогоплательщик зарегистрирован в ЕГРН'
			],
			3=>[
				'success'=>true,
				'msg'=>'Налогоплательщик с указанным ИНН зарегистрирован в ЕГРН, КПП не соответствует ИНН или не указан*'
			],
			4=>[
				'success'=>false,
				'msg'=>'Налогоплательщик с указанным ИНН не зарегистрирован в ЕГРН'
			],
			5=>[
				'success'=>false,
				'msg'=>'Некорректный ИНН'
			],
			6=>[
				'success'=>false,
				'msg'=>'Недопустимое количество символов ИНН'
			],
			7=>[
				'success'=>false,
				'msg'=>'Недопустимое количество символов КПП'
			],
			8=>[
				'success'=>false,
				'msg'=>'Недопустимые символы в ИНН'
			],
			9=>[
				'success'=>false,
				'msg'=>'Недопустимые символы в КПП'
			],
			11=>[
				'success'=>false,
				'msg'=>'КПП не должен использоваться при проверке ИП'
			],
			11=>[
				'success'=>false,
				'msg'=>'некорректный формат даты'
			],
			12=>[
				'success'=>false,
				'msg'=>'некорректная дата (ранее 01.01.1991 или позднее текущей даты)'
			]
	);




	public static function check($inn){
		
		$option = [];
		
		//$inn = "7725826870"; //зетапро
		$data['NP']['INN'] = $inn;
		$data['NP']['DT'] = date('d.m.Y',time());

		try {
			$client = new \SoapClient(self::$wsdl_url,$option);
			$output = $client->NdsRequest2($data);

			
			if(isset($output->NP) && isset($output->NP->State) && array_key_exists($output->NP->State, self::$states)){
				
				$state = self::$states[$output->NP->State];
			
			}else{
				$state = array(
					'success'=>false,
					'msg'=>'Ошибка при проверке ИНН. Ошибка не распознана!'
				);
			}
			 
		} catch (\Exception $e) {
			$state = array(
					'success'=>false,
					'msg'=>'Ошибка при проверке ИНН. Ошибка не распознана!'
			);
		}
		

		return $state;
	}
}