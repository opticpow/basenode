#!/usr/bin/php

<?php

require("lib/phpMQTT.php");

$mqtt = new phpMQTT("172.30.30.101", 1883, "HA logger v1");

if(!$mqtt->connect()){
	exit(1);
}

$topics['raw/#'] = array("qos"=>0, "function"=>"procmsg");
$mqtt->subscribe($topics,0);

while($mqtt->proc()){
		
}


$mqtt->close();

function procmsg($topic,$msg){
		echo date("Y-m-d H:i:s")." ".$msg."\n";
}
	


?>
