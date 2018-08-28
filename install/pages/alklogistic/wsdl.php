<?php
header("Content-type: text/xml; charset='utf-8'");

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";

require_once("../bitrix/modules/ali.logistic/lib/soap/Server/wsdl.xml");

echo $wsdl;
?>
