<?php
/** @file web.php 
	@brief a General Include for making Web pages 
**/

$PHP_SCRIPT_TIMER = microtime(true);
function php_GetExecutionTime( $print = false ) {
	global $PHP_SCRIPT_TIMER;
	
	$timediff = microtime(true) - $PHP_SCRIPT_TIMER;
	
	if ( $timediff < 1.0 )
		$ret = number_format( $timediff * 1000.0, 2 ) . ' ms';
	else if ( $timediff === 1.0 )
		$ret = "1 second";
	else
		$ret = number_format( $timediff, 4 ) . ' seconds';

	if ( $print )
		echo $ret;
	return $ret;
}

require_once __DIR__."/core/internal/html.php";	
require_once __DIR__."/core/internal/core.php";

require_once __DIR__."/core/users.php";			// User and Sessions
require_once __DIR__."/core/template.php";		// Templates and Themes
//require_once __DIR__."/core/internal/device.php";	// What kind of device is this? (Mobile, Tablet, PC)

?>
