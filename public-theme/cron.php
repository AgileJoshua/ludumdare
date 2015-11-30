<?php

if ( PHP_SAPI !== "cli" ) {
	header("HTTP/1.1 500 Internal Server Error");
	die;
}

require_once __DIR__."/../db.php";
require_once __DIR__."/../core/theme.php";
require_once __DIR__."/../core/internal/sanitize.php";

$EVENT_NAME = "Ludum Dare 34";
$EVENT_MODE = 1;
$EVENT_NODE = 100;
$EVENT_DATE = new DateTime("2015-12-12T02:00:00Z");

echo "Fetching Theme List...\n";

$all_themes = theme_GetIdeas($EVENT_NODE);

echo count($all_themes) . " total.\n";

// Generate Slugs //
foreach ($all_themes as &$theme) {
	$theme['slug'] = sanitize_Slug($theme['theme']);
}

$new_themes = [];
foreach ($all_themes as $key => &$theme) {
	if ( isset($new_themes[$theme['slug']]) ) {
		$theme['parent'] = $new_themes[$theme['slug']];
	}
	else {
		$theme['parent'] = 0;							// Clear Parent //
		$new_themes[$theme['slug']] = $theme['id'];		// Store ID by slug //
	}
}

echo count($new_themes) . " with duplicates removed.\n";

echo "Updating Parents...\n";
foreach ($all_themes as &$theme) {
	theme_SetParent($theme['id'],$theme['parent']);
}
echo "Done.\n";

//$idx = 0;
//foreach ($new_themes as $key => $theme) {
//	$idx++;
//	if ( $idx > 10 )
//		break;
//	print($key." [".$theme."]\n");
//}
//
//var_dump($all_themes[0]);


// TODO: Set an event-node specific flag once the auto-associate process has completed
// (i.e. remember the process has been done)
