<?php
namespace Ali\Logistic\soap\Server;

use Ali\Logistic\soap\Types\Customer;
use Ali\Logistic\soap\Types\Deal;
use Ali\Logistic\soap\Types\Route;
use Ali\Logistic\soap\Types\Costs;

class ServerHandler
{
	public $log_path = "output/";
	public $file_path = "output/files/";



	public function __construct(){

		$this->log_path = ALI_LOG_SOAP_SERVER_PATH;
		$this->file_path = $this->log_path."/files/";
	}


	public function sendContractors($data){
        
		$log = $this->log_path."sendContractors.txt";
		$output = fopen($log, "w");

		$output_line = "\n".date("H:i d.m.Y",time())." Контрагенты: "."\n\n";
		fwrite($output, $output_line);
		
		$contrs = json_decode(json_encode($data),true); //array
		$ln = json_encode($contrs);
		fwrite($output, $ln);
		
		fclose($output);
		$response = new \stdClass();
		$response->success = false;
		if(isset($contrs['customers']) && is_array($contrs['customers']) && count($contrs['customers'])){
			if(!isset($contrs['customers']['uuid'])){
				foreach ($contrs['customers'] as $c_data) {
					$c = new Customer($c_data);

					if(!empty($c->uuid) && $c->uuid != ''){
						$res = $c->save();
						if($res->isSuccess()){
							$response->success = true;
							$response->error = "";
							$response->errorMessages = "";
						}else{
							$response->success = false;
							$response->error = "saveError";
							$response->errorMessages = $res->getErrorMessages();
							break;
						}
					}else{
						$response->error = "emptyCustomer";
					}
				}
			}else{
				$c = new Customer($contrs['customers']);

				if(!empty($c->uuid) && $c->uuid != ''){
					$res = $c->save();
					if($res->isSuccess()){
						$response->success = true;
					}else{
						$response->error = "saveError";
						$response->errorMessages = $res->getErrorMessages();
					}
				}else{
					$response->error = "emptyCustomer";
				}
			}

			
		}else{
			$response->error = "emptyCustomer";
		}
        
        
        
        
		return $response;
	}










	public function sendDeal($deal){

		$log = $this->log_path."sendDeal.txt";
		$output = fopen($log, "w");

		$output_line = "\n".date("H:i d.m.Y",time())." Заявка: "."\n\n";
		fwrite($output, $output_line);
		
		$deal = json_decode(json_encode($deal),true); //array
		$ln = json_encode($deal);
		fwrite($output, $ln);
		
		fclose($output);
        $response = new \stdClass();
        $response->success = false;

        if(isset($deal['uuid']) && !empty($deal['uuid']) && $deal['uuid'] != ""){
        	$dealObject = new Deal($deal);

        	$res = $dealObject->save();
        	if(!$res->isSuccess() || !$res->getId()){
        		$response->error = "saveDealError";
				$response->errorMessages = $res->getErrorMessages();
        	}else{
        		$deal_id = $res->getId();
        		$response->success = true;

        		if($deal_id && is_array($dealObject->routes) && count($dealObject->routes)){
        			
        			Route::deleteDealRoutes($deal_id);

        			foreach ($dealObject->routes as $r) {
        				if($r instanceof Route){
        					$r->setDealId($deal_id);
        					$route = $r;
        				}else{
        					$route = new Route($r,$deal_id);
        				}

        				$res = $route->save();

        				if(!$res->isSuccess()){
        					$response->success = false;
							$response->error = "saveRouteError";
							$response->errorMessages = $res->getErrorMessages();
							break;
        				}
        			}
        		}


        		if($deal_id && is_array($dealObject->costs) && count($dealObject->costs)){
        			
        			Costs::deleteDealCosts($deal_id);

        			foreach ($dealObject->costs as $c) {
        				if($c instanceof Costs){
        					$c->setDealId($deal_id);
        					$cost = $c;
        				}else{
        					$cost = new Costs($c,$deal_id);
        				}

        				$res = $cost->save();

        				if(!$res->isSuccess()){
        					$response->success = false;
							$response->error = "saveCostError";
							$response->errorMessages = $res->getErrorMessages();
							break;
        				}
        			}
        		}
        		
        	}
        }else{
        	$response->error = "emptyDealUuid";
        }

		return $response;
	}










	public function sendFileBill($data){
		$log = $this->log_path."sendFileBill.txt";
		$output = fopen($log, "w");

		$uuid = $data->dealUuid;
		$fileNumber = $data->fileNumber;
		$file_name = $uuid."__".$fileNumber."_file_bill.pdf";

		$output_line = "\n".date("H:i d.m.Y",time())." Файл счет: ".$file_name;
		fwrite($output, $output_line);
		fclose($output);

		$path_file = $this->file_path.$file_name;
		$pdf = fopen($path_file, "w");
		
		fwrite($pdf, $data->binaryFile);

		fclose($pdf);
		//-----------------------//
		$uuid = $data->dealUuid;
		$fileNumber = $data->fileNumber;
		$response = new \stdClass();

		$res = Deal::saveFileBill($uuid,$fileNumber,$data->binaryFile);
		
		if(!$res->isSuccess()){
			$response->success = false;
			$response->error = "errorSendFileBill";
			$response->errorMessages = $res->getErrorMessages();
		}else{
			$response->success = true;
		}

        
		return $response;
	}
}
?>