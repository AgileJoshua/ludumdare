<?php
require_once __DIR__ . "/../db.php";

$__love_table = "cmw_love";

// Returns true if the love was added (false if it already existed) //
function love_Add( &$node, &$user = 0, &$ip = '0.0.0.0' ) {
	global $__love_table;
	
	db_Connect();

	db_Query(
		"INSERT IGNORE `" . $__love_table . "` (".
			"`node`,".
			"`user`,".
			"`ip`".
		") ".
		"VALUES (" .
			$node . "," .
			$user . "," .
			"INET_ATON('" . $ip . "')" .
		");");
		
	// TODO: do something on db_query error

	return !empty(db_AffectedRows());
}


// Returns true if the love was removed (false if there was no row to remove) //
function love_Remove( &$node, &$user = 0, &$ip = '0.0.0.0' ) {
	global $__love_table;
	
	db_Connect();

	db_Query( 
		"DELETE FROM `" . $__love_table . "` WHERE ".
			"`node`=" . $node . " AND " .
			"`user`=" . $user . " AND " .
			"`ip`=INET_ATON('" . $ip . "')" .
		";");
		
	// TODO: do something on db_query error

	return !empty(db_AffectedRows());
}


// Returns an array of NodeIDs that are Loved by UserID or IP.
function love_Fetch( &$user = 0, &$ip = '0.0.0.0', $offset = null, $limit = null ) {
	global $__love_table;

	db_Connect();

	return db_FetchSingle( 
		"SELECT `node` FROM `" . $__love_table . "` WHERE ".
			"`user`=" . $user . " AND " .
			"`ip`=INET_ATON('" . $ip . "')" .
		(is_null($limit) ? "" : (" LIMIT " . $limit)) . 
		(is_null($offset) ? "" : (" OFFSET " . $offset)) . 
		";");
}

?>
