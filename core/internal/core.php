<?php
/** @file core.php 
	@brief Starship Helpers
*/

require_once __DIR__ . "/../../config.php";	// Configuration and Settings //

// Bump this whenever you want to force a refresh. //
const INTERNAL_VERSION = '0.1.1';

// Helper Functions used to create Paths and URLs. //
function STATIC_URL() {
	echo CMW_STATIC_URL;
}
// Creates a Query string containing a version number //
function VERSION_QUERY( $my_version = null ) {
	if ( is_string($my_version) )
		echo "?v=",$my_version,"-",INTERNAL_VERSION;
	else
		echo "?v=",INTERNAL_VERSION;
}	

// Pads 1-digit numbers with a 0 //
function PADNUM( $number ) {
	if ( $number >= 0 && $number < 10 ) {
		return "0".$number;
	}
	return "".$number;
}

// Shorthand that does a print_r on data, removes newlines, and returns the result //
function return_r( $data ) {
	return trim(preg_replace('/\s+/',' ',print_r( $data, true)));
}

// Parse the API Action URL (array of strings) //
function core_ParseActionURL() {
	// If PATH_INFO is set, then Apache figured out our parts for us //
	if ( isset($_SERVER['PATH_INFO']) ) {
		$ret = ltrim(rtrim($_SERVER['PATH_INFO'],'/'),'/');
		if ( empty($ret) )
			return [];
		else
			return array_values(array_filter(explode('/',$ret),function ($val) {
				return !(empty($val) || ($val[0] === '.'));
			}));
	}

	// If not, we have to extract them from the REQUEST_URI //
	// Logic borrowed from here: https://coderwall.com/p/gdam2w/get-request-path-in-php-for-routing
	$request_uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    $script_name = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));
    $parts = array_diff_assoc($request_uri, $script_name);
    if (empty($parts)) {
        return [];
    }
	$path = implode('/', $parts);
	if (($position = strpos($path, '?')) !== FALSE) {
	    $path = substr($path, 0, $position);
	}
	$ret = ltrim(rtrim($path,'/'),'/');
	if ( empty($ret) )
		return [];
	else
		return array_values(array_filter(explode('/',$ret),function ($val) {
			return !(empty($val) || ($val[0] === '.'));
		}));
}
// Convert response codes in to text //
function core_GetHTTPResponseText($code){
	static $HTTP_RESPONSE_TEXT = [
		// Success Responses
		200=>"OK",
		201=>"Created",					// Successfully created.
		202=>"Accepted",				// Request accepted, will be fullfilled later.
		// Redirect Respones
		301=>"Moved Permanently",		// Redirections.
		304=>"Not Modified",			// ** 
		// User Error Responeses
		400=>"Bad Request",				// Syntax Error.
		401=>"Unauthorized",			// Insufficent permission to do something
		403=>"Forbidden",				// ** Resource is protected.
		404=>"Not Found",				// Resource not found.
		409=>"Conflict",				// **
		412=>"Precondition Failed",		// **
		// Server Error Responses
		500=>"Internal Server Error",	// Something is wrong on our end.
		503=>"Service Unavailable",		// Maintenence.
	];
	
	return $HTTP_RESPONSE_TEXT[$code];
}

// - ----------------------------------------------------------------------------------------- - //
// http://programanddesign.com/php/base62-encode/
// MK: Removed 'cfituCFITU' to make it harder to generate F-bombs, S-bombs, and C-bombs //
// "0123456789abcdefghjklmnopqrstvwxyzABCDEFGHJKLMNOPQRSTVWXYZ" 58
// "0123456789abdeghjklmnopqrsvwxyzABDEGHJKLMNOPQRSVWXYZ" 52
// "0123456789abdeghjkmnopqrsvwxyzABDEHJKLMNPQRVWXYZ" 48 (lGSO - Similar-to numbers removed: 1650)
// - ----------------------------------------------------------------------------------------- - //
/**
 * Converts a base 10 number to any other base.
 * 
 * @param int $val   Decimal number
 * @param int $base  Base to convert to. If null, will use strlen($chars) as base.
 * @param string $chars Characters used in base, arranged lowest to highest. Must be at least $base characters long.
 * 
 * @return string    Number converted to specified base
 */
function base48_Encode($val, $base=48, $chars='0123456789abdeghjkmnopqrsvwxyzABDEHJKLMNPQRVWXYZ') {
    if(!isset($base)) $base = strlen($chars);
    $str = '';
    do {
        $m = bcmod($val, $base);
        $str = $chars[$m] . $str;
        $val = bcdiv(bcsub($val, $m), $base);
    } while(bccomp($val,0)>0);
    return $str;
}
// - ----------------------------------------------------------------------------------------- - //
/**
 * Convert a number from any base to base 10
 * 
 * @param string $str   Number
 * @param int $base  Base of number. If null, will use strlen($chars) as base.
 * @param string $chars Characters use in base, arranged lowest to highest. Must be at least $base characters long.
 * 
 * @return int    Number converted to base 10
 */
function base48_Decode($str, $base=48, $chars='0123456789abdeghjkmnopqrsvwxyzABDEHJKLMNPQRVWXYZ') {
    if(!isset($base)) $base = strlen($chars);
    $len = strlen($str);
    $val = 0;
    $arr = array_flip(str_split($chars));
    for($i = 0; $i < $len; ++$i) {
        $val = bcadd($val, bcmul($arr[$str[$i]], bcpow($base, $len-$i-1)));
    }
    return $val;
}
// - ----------------------------------------------------------------------------------------- - //
function base48_Fix( $str, $chars='0123456789abdeghjkmnopqrsvwxyzABDEHJKLMNPQRVWXYZ' ) {
	return preg_replace("/[^".$chars."]/", '', $str );
}
// - ----------------------------------------------------------------------------------------- - //
?>
