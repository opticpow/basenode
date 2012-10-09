#!/usr/bin/php -c .

<?php

function microtime_float()
{
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
}

include "lib/php_serial.class.php";

date_default_timezone_set('Australia/NSW');

// Let's start the class
$serial = new phpSerial;
$serial->deviceSet("/dev/ttyAMA0");
$serial->confBaudRate(57600);
//$serial->confParity("none");
//$serial->confCharacterLength(8);
//$serial->confStopBits(1);
//$serial->confFlowControl("none");
// We may need to return if nothing happens for 10 seconds
//stream_set_timeout($serial->_dHandle, 10);

// Then we need to open it
//$serial->deviceOpen();
//$serial->sendMessage("210g");
//$serial->sendMessage("7i");
//$serial->sendMessage("8b");



echo (date("Y-m-d H:i:s")." Started..\n");

while(1) {

if(!$serial->deviceOpen()) {
	echo "Could not open serial port";
	exit(1);
}


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

$serial->deviceClose();
if ($theResult !='') {
	echo(date("Y-m-d H:i:s")." ".trim($theResult)."\n");
}

//while
}
$serial->deviceClose();
?>
