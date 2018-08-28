<?php
header("Content-type: text/xml; charset='utf-8'");

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";

require_once("wsdl.xml");

echo $wsdl;
?>