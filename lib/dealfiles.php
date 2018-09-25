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
use Ali\Logistic\soap\Types\DealFiles as TypeDealFiles;
use Bitrix\Main\Type\DateTime;

use Bitrix\Main\Error;

class DealFiles{

    public static function saveFile(TypeDealFiles $Elementfile,$typeFile,$fPath,$endfix = null){

        $deal_id = $Elementfile->getDealId();
        $fNumber = $Elementfile->fileNumber;
        $fileBinary = $Elementfile->binaryFile;
        $sum = $Elementfile->sum;
        $sumPaid = $Elementfile->sumPaid;
        $paidDate = $Elementfile->paidDate ? DateTime::createFromTimestamp(strtotime($Elementfile->paidDate)) : null;

        $fileDate = $Elementfile->fileDate ? DateTime::createFromTimestamp(strtotime($Elementfile->fileDate)) : DateTime();
        


        $res = new Result();
        $row = DealFilesSchemaTable::getRow(array("select"=>['ID','FILE_NUMBER','FILE','DEAL_ID'],'filter'=>['FILE_NUMBER'=>[$fNumber],'DEAL_ID'=>$deal_id,'FILE_TYPE'=>$typeFile]));

        $file_id = 0;
        $fName = "id#".$deal_id."__num_".date("YmdHis",time())."_file_".$endfix.".pdf";
        $oldfName = null;
        if(isset($row['ID']) && $row['ID']){
            $file_id  = $row['ID'];
            $oldfName = isset($row['FILE']) && file_exists($fPath.$row['FILE']) ? $fPath.$row['FILE'] : null ;
        }
        

        $file = $fPath.$fName;
        $pdf = fopen($file, "w");
        fwrite($pdf, $fileBinary);
        fclose($pdf);

        if(file_exists($file)){
            $data = array();
            $data['DEAL_ID'] = $deal_id;
            $data['FILE_TYPE'] = $typeFile;
            $data['FILE_NUMBER'] = $fNumber;
            $data['FILE_DATE'] = $fileDate;
            if($paidDate){
                $data['PAID_AT'] = $paidDate;
            }
            $data['FILE']=$fName;
            $data['SUM']=$sum;
            $data['SUM_PAID']=$sumPaid;

            try {
                if($file_id){
                    $res = DealFilesSchemaTable::update($row['ID'],$data);
                }else{
                    $res = DealFilesSchemaTable::add($data);
                }

                if($res->isSuccess() && $oldfName && file_exists($oldfName)){
                    unlink($oldfName);
                }

            } catch (\Exception $e) {
                unlink($file);
                $res->addError(new Error($e->getMessage(),$e->getCode()));
            }
        }else{
            $res->addError(new Error('Не удалось загрузить файл на сервер сайта!',2));
        }

        return $res;

    }


    public static function sendFileBill(TypeDealFiles $Typefile){
        return self::saveFile($Typefile,DealFileType::FILE_BILL,ALI_FILE_BILLS_PATH,'bill');
    }



    public static function sendFileAct(TypeDealFiles $Typefile){
        return self::saveFile($Typefile,DealFileType::FILE_ACT,ALI_FILE_ACTS_PATH,'act2');
    }


    public static function sendFileInvoice(TypeDealFiles $Typefile){
        return self::saveFile($Typefile,DealFileType::FILE_INVOICE,ALI_FILE_INVOICES_PATH,'invoices');
    }


    public static function sendFileContract(TypeDealFiles $Typefile){
        return self::saveFile($Typefile,DealFileType::FILE_CONTRACT,ALI_FILE_CONTRACTS_PATH,'contract');
    }


    public static function sendFileDriverAttorney(TypeDealFiles $Typefile){
        return self::saveFile($Typefile,DealFileType::FILE_DRIVER_ATTORNEY,ALI_FILE_DRIVER_ATTORNEY_PATH,'driver');
    }


    public static function sendFilePrintForm(TypeDealFiles $Typefile){
        return self::saveFile($Typefile,DealFileType::FILE_PRINT_FORM,ALI_FILE_PRINT_FORM_PATH,'print_form');
    }


    public static function sendFileTTH(TypeDealFiles $Typefile){
        return self::saveFile($Typefile,DealFileType::FILE_TTH,ALI_FILE_TTH_PATH,'ttn');
    }

}