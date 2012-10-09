#!/usr/bin/php -c .

<?php

function microtime_float()
{
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
}

include "lib/php_serial.class.php";
require("lib/phpMQTT.php");


date_default_timezone_set('Australia/NSW');

// Setup Serial
$serial = new phpSerial;
$serial->deviceSet("/dev/ttyAMA0");
$serial->confBaudRate(57600);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->confFlowControl("none");

// We may need to return if nothing happens for 10 seconds
//stream_set_timeout($serial->_dHandle, 10);



if(!$serial->deviceOpen()) {
	echo "Could not open serial port";
	exit(1);
}

echo (date("Y-m-d H:i:s")." Started..\n\n");

$mqtt = new phpMQTT("172.30.30.101", 1883, "HA basenode v1");

$ipaddr=getHostByName(getHostName());

// Configure JeeNode
//$serial->deviceOpen();
//$serial->sendMessage("212g");
//$serial->sendMessage("7i");
//$serial->sendMessage("8b");
	
while(1) {
	// Or to read from
	$read = '';
	$theResult = '';
	$start = microtime_float();
	
	//1 second limit to read
	while ( ($read == '') && (microtime_float() <= $start + 1)) {
	        $read = $serial->readPort();
	        if ($read != '') {
	                $theResult .= $read;
	                $read = '';
	        }
	}
	
	//$serial->deviceClose();
	if ($theResult !='') {
		$data=trim($theResult);
		echo(date("Y-m-d H:i:s")." Received: ".$data."\n");
		$line=explode(" ",$data);
		// 0:NodeType 1:NodeId 2:Serial 3:Move 4:Temp 5:Humi 6:Light 7:LowBat
		if ($mqtt->connect()) {
			echo (date("Y-m-d H:i:s")." Connected to MQTT Broker\n");
			$path="raw/".$ipaddr."/".$line[1];
			$mqtt->publish($path,$data,0);
			echo (date("Y-m-d H:i:s")." Message sent\n");
			$mqtt->close();
		} else {
			echo (date("Y-m-d H:i:s")." ERROR: could not connect to MQTT Broker\n");
			exit(1);
		}
		
	}
	
	//while
}
$mqtt->close();
$serial->deviceClose();
?>
