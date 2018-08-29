<?php
namespace Ali\Logistic\soap\Server;

use Ali\Logistic\soap\Types\Customer;

class ServerHandler
{
	public $log_path = "output/";
	public $file_path = "output/files/";



	public function __construct(){

		$this->log_path = ALI_LOG_PATH;
		$this->file_path = $this->log_path."/files/";
	}



	// public function sendContractors($data){
        
	// 	$log = $this->log_path."sendContractors.txt";
	// 	$output = fopen($log, "w");

	// 	$output_line = "\n".date("H:i d.m.Y",time())." Контрагенты: "."\n\n";
	// 	fwrite($output, $output_line);
		
	// 	$ln = json_encode(json_decode(json_encode($data),true));
	// 	fwrite($output, $ln);
		
	// 	fwrite($output, ALI_LOG_PATH);
		
		
	// 	fclose($output);

 //        $response = new \stdClass();
        
 //        $response->success = true;
 //        $response->error = count($data->customers->Struct);
	// 	return $response;
	// }


	public function sendContractors($data){
        
		$log = $this->log_path."sendContractors.txt";
		$output = fopen($log, "w");

		$output_line = "\n".date("H:i d.m.Y",time())." Контрагенты: "."\n\n";
		fwrite($output, $output_line);
		
		$contrs = json_decode(json_encode($data),true); //array
		$ln = json_encode($contrs);
		fwrite($output, $ln);
		
		fwrite($output, ALI_LOG_PATH);
		
		fclose($output);
		$response = new \stdClass();
		$response->success = false;
		if(isset($contrs['customers']) && is_array($contrs['customers']) && count($contrs['customers'])){
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
		
		$ln = json_encode(json_decode(json_encode($deal),true));
		fwrite($output, $ln);
		
		fclose($output);

        $response = new \stdClass();
        
        $response->success = true;
        $response->request = $deal;
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
		
		$success = file_exists($path_file);

        $response = new \stdClass();
        $response->success = $success;
        $response->error = $success ? "Файл загружен" : "Файл не загружен";
		return $response;
	}
}
?>