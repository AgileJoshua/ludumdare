<?php
//include_once __DIR__ . "/../../config.php";

$db = null;				// ** Global Database Variable **** //

// Logging function specific to database operations //
function db_Log( $msg ) {
	error_log( "CMW DB ERROR: " . $msg );
	echo "<strong>CMW DB ERROR:</strong> " . $msg . "<br />";
}

// Check database config //
if ( !defined('CMW_DB_HOST') ) {
	db_Log( "No database host name set." );
}
if ( !defined('CMW_DB_NAME') ) {
	db_Log( "No database name set." );
}
if ( !defined('CMW_DB_LOGIN') ) {
	db_Log( "No database login set." );
}
if ( !defined('CMW_DB_PASSWORD') ) {
	db_Log( "No database password set." );
}

// Are we connected and ready to use the Database? //
function db_IsConnected() {
	global $db;
	return isset($db);
}
// Connect to the Database - Pass true if you don't want to log an error if already connected //
function db_Connect(/*$no_log=false*/) {
	if ( !db_IsConnected() ) {
		global $db;

		// Connect to the database //
		$db = new mysqli(CMW_DB_HOST,CMW_DB_LOGIN,CMW_DB_PASSWORD,CMW_DB_NAME);
		
		// http://php.net/manual/en/mysqli.quickstart.connections.php
		if ($db->connect_errno) {
    		db_Log( "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error );
    	}
    	
    	// Set character set to utf8mb4 mode (default is utf8mb3 (utf8). mb4 is required for Emoji)
    	$db->set_charset('utf8mb4');
    	// More info: http://stackoverflow.com/questions/279170/utf-8-all-the-way-through
	}
//	else {
//		if ( !$no_log ) {
//			db_Log( "Database already connected" );
//		}
//	}
}

// Unsafe "run any query" function. Queries don't return results. Use db_fetch instead. //
function db_Query($query,$ignore_errors=false) {
	global $db;
	return $db->query($query) or $ignore_errors or die(mysqli_error($db)."\n");
}

// Unsafe "run any fetch query" function. Returns an Associative Array. //
function db_Fetch($query) {
	global $db;
	$result = $db->query($query);
	$rows = [];
	while ( $row = $result->mysql_fetch_assoc() ) {
		$rows[] = $row;
	};
	return $rows;
	//return $result->fetch_array(MYSQLI_ASSOC);
}

// Unsafe "run any fetch query" function. Returns a Numeric Array. //
function db_FetchArray($query) {
	global $db;
	$result = $db->query($query);
	$rows = [];
	while ( $row = $result->fetch_row() ) {
		$rows[] = $row;
	}
	return $rows;
	//return $result->fetch_array(MYSQLI_NUM);
}

// Unsafe "run any fetch query" function. Returns a Numeric Array. //
function db_FetchSingle($query) {
	global $db;

	$result = $db->query($query);
	$rows = [];
	while ( $row = $result->fetch_row()[0] ) {
		$rows[] = $row;
	}

	return $rows;
}


function db_AffectedRows() {
	global $db;
	return $db->affected_rows;
}

function db_NumRows() {
	global $db;
	return $db->num_rows;
}

?>
