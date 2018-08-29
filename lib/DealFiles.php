<?php

namespace Ali\Logistic;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Entity\Result;
use \Bitrix\Main\Type;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Application;
use \Ali\Logistic\Schemas\DealFilesSchemaTable;
use Ali\Logistic\Dictionary\DealFileType;
use Ali\Logistic\helpers\ArrayHelper;
use Bitrix\Main\Error;

class DealFiles{


    public static function saveFileBill($deal_id,$fNumber,$fileBinary){

        $res = new Result();

        $fPath = ALI_FILE_BILLS_PATH;

        $row = DealFilesSchemaTable::getRow(array("select"=>['ID','FILE_NUMBER','FILE','DEAL_ID'],'filter'=>['FILE_NUMBER'=>$fNumber,'DEAL_ID'=>$deal_id,'FILE_TYPE'=>DealFileType::FILE_BILL]));

        $file_id = 0;
        $fName = "id#".$deal_id."__num".$fNumber."_file_bill.pdf";
        if(isset($row['ID']) && $row['ID']){
            $file_id  = $row['ID'];
            $fName = isset($row['FILE']) && file_exists($fPath.$row['FILE']) ? $row['FILE'] : $fName ;
        }
        

        $file = $fPath.$fName;
        $pdf = fopen($file, "w");
        fwrite($pdf, $fileBinary);
        fclose($pdf);

        if(file_exists($file)){
            $data = array();
            $data['DEAL_ID'] = $deal_id;
            $data['FILE_TYPE'] = DealFileType::FILE_BILL;
            $data['FILE_NUMBER'] = $fNumber;
            $data['FILE']=$fName;

            try {
                if($file_id){
                    $res = DealFilesSchemaTable::update($row['ID'],$data);
                }else{
                    $res = DealFilesSchemaTable::add($data);
                }
            } catch (\Exception $e) {
                unlink($file);
                $res->addError(new Error($e->getMessage(),$e->getCode()));
            }
        }else{
            $res->addError(new Error('Не удалось загрузить сайт на сервер сайта!',2));
        }

        return $res;
    }

}