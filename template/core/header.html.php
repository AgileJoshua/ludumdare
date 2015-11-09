<?php header("Content-Type: text/html; charset=utf-8"); ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">	
<?php 
	global $PAGE_TITLE;
	if ( isset($page_title) ) {
		echo "<title>".$PAGE_TITLE."</title>";
	}

	const VERSION = '0.1.1';
	const VERSION_STRING = '?v='.VERSION;

	if ( defined('CMW_JS_DEBUG') ) {
		echo "	<!-- External JavaScript -->\n";
		if ( defined('HTML_USE_EMOJI') ) { echo "	<script src=".CMW_STATIC_URL."/custom/emojitwo/emojitwo.js".VERSION_STRING."></script>\n"; }
		if ( defined('HTML_USE_MARKUP') ) { echo "	<script src=".CMW_STATIC_URL."/external/marked/marked.js".VERSION_STRING."></script>\n"; }
		if ( defined('HTML_USE_HIGHLIGHT') ) { echo "	<script src=".CMW_STATIC_URL."/external/prism/prism.js".VERSION_STRING."></script>\n"; }
	
		echo "	<!-- Internal JavaScript -->\n";
		if ( defined('HTML_USE_CORE') ) {
			echo "	<script src=".CMW_STATIC_URL."/internal/src/xhr.js".VERSION_STRING."></script>\n";
		}
		if ( defined('HTML_USE_STARSHIP') ) {
			echo "	<script src=".CMW_STATIC_URL."/internal/src/cache.js".VERSION_STRING."></script>\n";
			echo "	<script src=".CMW_STATIC_URL."/internal/src/html.js".VERSION_STRING."></script>\n";
			echo "	<script src=".CMW_STATIC_URL."/internal/src/love.js".VERSION_STRING."></script>\n";
			echo "	<script src=".CMW_STATIC_URL."/internal/src/star.js".VERSION_STRING."></script>\n";
		}
	} 
	else { /* defined('CMW_JS_DEBUG') */
		if ( defined('HTML_USE_EMOJI') ) { echo "	<script src=".CMW_STATIC_URL."/custom/emojitwo/emojitwo.min.js".VERSION_STRING."></script>\n"; }
		if ( defined('HTML_USE_MARKUP') ) { echo "	<script src=".CMW_STATIC_URL."/external/marked/marked.min.js".VERSION_STRING."></script>\n"; }
		if ( defined('HTML_USE_HIGHLIGHT') ) { echo "	<script src=".CMW_STATIC_URL."/external/prism/prism.js".VERSION_STRING."></script>\n"; }
		if ( defined('HTML_USE_INTERNAL') || defined('HTML_USE_CORE') || defined('HTML_USE_STARSHIP') ) { 
			echo "	<script src=".CMW_STATIC_URL."/internal/core.min.js".VERSION_STRING."></script>\n"; 
		}
	} /* defined('CMW_JS_DEBUG') */

	echo "	<!-- External CSS -->\n";
	if ( defined('CMW_CSS_DEBUG') ) {
		if ( defined('HTML_USE_EMOJI') ) { echo "	<link rel='stylesheet' href='".CMW_STATIC_URL."/custom/emojitwo/emojitwo.css".VERSION_STRING."' />\n"; }
	} else { /* defined('CMW_CSS_DEBUG') */
		if ( defined('HTML_USE_EMOJI') ) { echo "	<link rel='stylesheet href='".CMW_STATIC_URL."/custom/emojitwo/emojitwo.min.css".VERSION_STRING."' />\n"; }
	} /* defined('CMW_CSS_DEBUG') */

	if ( defined('HTML_USE_HIGHLIGHT') ) { echo "	<link rel='stylesheet' href='".CMW_STATIC_URL."/external/prism/prism.css".VERSION_STRING."' />\n"; }

	// Stylesheet and Font //
	echo "	<link rel='stylesheet' href='".CMW_STATIC_URL."/style/core.css.php".VERSION_STRING."' />\n";
	echo "	<link href='//fonts.googleapis.com/css?family=Lato:300,300italic,700,700italic|Crimson+Text:400italic' rel='stylesheet' type='text/css'>";

	if ( defined('HTML_CSS_INCLUDE') ) {
		$css_includes = HTML_CSS_INCLUDE;
		if ( is_array($css_includes) ) {
			foreach ($css_includes as $include) {
				echo "	<link rel='stylesheet' href='".CMW_STATIC_URL.$include.VERSION_STRING."' />\n";
			}
		}
	}
	//<link href='https://fonts.googleapis.com/css?family=Lato:300,300italic,700,700italic|Crimson+Text:400italic|Inconsolata' rel='stylesheet' type='text/css'>
	//<link href='https://fonts.googleapis.com/css?family=Lato:300,300italic,700,700italic|Crimson+Text:400italic,400|Inconsolata:400,700' rel='stylesheet' type='text/css'>
?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
