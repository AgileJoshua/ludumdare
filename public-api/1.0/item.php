<?php
require "../../lib/api.php";

// CMW API //

$out = array(
		'item' => 1
		);

//$out['args'] = $_GET;
//$out['server'] = $_SERVER;
$out['parsed'] = api_parseActionURL();

api_emitJSON( $out );

?>
