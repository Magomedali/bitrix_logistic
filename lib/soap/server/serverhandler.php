<?php
namespace Ali\Logistic\soap\Server;

use Ali\Logistic\soap\Types\Customer;
use Ali\Logistic\soap\Types\Deal;
use Ali\Logistic\soap\Types\DealFiles;
use Ali\Logistic\soap\Types\Route;
use Ali\Logistic\soap\Types\Costs;

use Bitrix\Main\Result;
use Bitrix\Main\Error;

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
		$output = fopen($log, "a+");

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
		$output = fopen($log, "a+");

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

        			foreach ($dealObject->routes as $key => $r) {
        				if($r instanceof Route){
        					$r->setDealId($deal_id);
        					$route = $r;
        					$route->order = ++$key;
        				}else{
        					$r['order'] = ++$key;
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








	public function integrateFile($data,$type){
		
		$dealFile = new DealFiles($data);
		
		$log_d['uuid']=$dealFile->dealUuid;
		$log_d['date']=$dealFile->fileDate;
		$log_d['number']=$dealFile->fileNumber;

		$log = $this->log_path."integrateFile.txt";
		$lf = fopen($log, "a+");
		fwrite($lf, "\n\n".date("H:i d.m.Y",time())."\n\n");
		fwrite($lf, "\n\nИнтеграция файла тип '{$type}'\n\n");
		fwrite($lf, "Входные параметры: \n");
		fwrite($lf, json_encode($log_d));
		fclose($lf);

		$res = $dealFile->checkDealExists();
		if($res->isSuccess()){
			switch ($type) {
				case 1:
					$res = Deal::sendFileBill($dealFile);
					break;
				case 2:
					$res = Deal::sendFileAct($dealFile);
					break;
				case 3:
					$res = Deal::sendFileInvoice($dealFile);
					break;
				case 4:
					$res = Deal::sendFileContract($dealFile);
					break;
				case 5:
					$res = Deal::sendFileDriverAttorney($dealFile);
					break;
				case 6:
					$res = Deal::sendFilePrintForm($dealFile);
					break;
				case 7:
					$res = Deal::sendFileTTH($dealFile);
					break;
				
				default:
					$res = new Result();
		            $res->addError(new Error("Неправильный тип файла",1));
					break;
			}
		}
		
		
		$response = new \stdClass();
		if(!$res->isSuccess()){
			$response->success = false;
			$response->error = json_encode($data);//"errorSendFile";
			$response->errorMessages = $res->getErrorMessages();
		}else{
			$response->success = true;
		}
        
		return $response;
	}


	public function sendFileBill($data){
		return $this->integrateFile($data,1);
	}

	public function sendFileAct($data){
		return $this->integrateFile($data,2);
	}

	public function sendFileInvoice($data){
		return $this->integrateFile($data,3);
	}

	public function sendFileContract($data){
		return $this->integrateFile($data,4);
	}

	public function sendFileDriverAttorney($data){
		return $this->integrateFile($data,5);
	}

	public function sendFilePrintForm($data){
		return $this->integrateFile($data,6);
	}

	public function sendFileTTH($data){
		return $this->integrateFile($data,7);
	}
}
?>