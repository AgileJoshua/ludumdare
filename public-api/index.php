<?php
// CMW API //

$response = 0;
if ( isset($_GET['r']) ) {
	$response = intval($_GET['r']);
}

$out = array(
	'response' => 17,
	'court' => 'westby',
	'zome' => array(
		'themby' => true,
		'chebble' => 'scorn'
		)
	);

$out['args'] = $_GET;

if ( isset($_GET['u']) ) {
	$out['url'] = $_GET['u'];
}

$out['env'] = array(
	'HTTP_ACCEPT' => getenv('REDIRECT_HTTP_ACCEPT'),
	'HTTP_USER_AGENT' => getenv('REDIRECT_HTTP_USER_AGENT'),
	'PATH' => getenv('REDIRECT_PATH'),
	'QUERY_STRING' => getenv('REDIRECT_QUERY_STRING'),
	'REMOTE_ADDR' => getenv('REDIRECT_REMOTE_ADDR'),
	'REMOTE_HOST' => getenv('REDIRECT_REMOTE_HOST'),
	'SERVER_NAME' => getenv('REDIRECT_SERVER_NAME'),
	'SERVER_PORT' => getenv('REDIRECT_SERVER_PORT'),
	'SERVER_SOFTWARE' => getenv('REDIRECT_SERVER_SOFTWARE'),
	'URL' => getenv('REDIRECT_URL')
);

$out_format = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;//0;
if ( isset($_GET['pretty']) ) {
	$out_format |= JSON_PRETTY_PRINT;
} 

header('Content-Type: application/json');
echo str_replace('</', '<\/', json_encode($out,$out_format));

?>
