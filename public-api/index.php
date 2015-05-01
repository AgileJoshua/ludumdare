<?php
// Default API //
$url = getenv('REDIRECT_URL');
$query = getenv('REDIRECT_QUERY_STRING');
if ( !$query ) {
	$query = "";	// getenv returns false on error //
}

$out = array(
	'response' => 'ok'
);
$out['url'] = $url;
$out['query'] = $query;

// By default, PHP will make '/' slashes in to '\/'. These flags fix that //
$out_format = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
// If 'pretty' mode (i.e. readable) //
if ( isset($_GET['pretty']) ) {
	$out_format |= JSON_PRETTY_PRINT;
} 

header('Content-Type: application/json');
echo str_replace('</', '<\/', json_encode($out,$out_format));

?>
