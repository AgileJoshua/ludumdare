<?php
/**
 * Template
 *
 * @file
 */

/** @name Internal
 *  @subpage
 */
/**@{*/

/**
 * **INTERNAL**: Name of the current theme.
 */
$_cmw_theme = "default";

/**
 * **INTERNAL**: Includes template files (.php) and sets up the environment.
 *
 * @param	string	$path_to_file Fully qualified path to a file.
 * @param	bool	$include_once Should we use include_once? (default: false)
 */
function _template_Include( $path_to_file, $include_once = false ) {
	// Make certain global variables implicitly available to the template.
	//global $db;
	
	// Include the template file.
	if ( $include_once ) {
		include_once $path_to_file;
	}
	else {
		include $path_to_file;
	}
}

/**@}*/

/**
 * Instance a template.
 *
 * @param	string	$file File to load.
 * @param	string	$theme Theme to use (default: "default")
 * @param	bool	$include_once Should we use include_once? (default: false)
 */
function template_Get( $file, $theme = null, $include_once = false ) {
	_template_Include( __DIR__."/../public-static".CMW_THEME_BASE."/".template_GetTheme($theme)."/template".$file, $include_once );
}


/**
 * dummy
 *
 * @param	string	$theme Theme to use (default: "default")
 */
function template_SetTheme( $theme = null ) {
	global $_cmw_theme;
	if ( is_null($theme) ) {
		$_cmw_theme = "default";
	}
	$_cmw_theme = $theme;
}

/**
 * dummy
 *
 * @param	string	$theme Theme to use (default: "default")
 */
function template_GetTheme( $theme = null ) {
	global $_cmw_theme;
	if ( is_null($theme) ) {
		if ( !is_null($_cmw_theme) ) {
			return $_cmw_theme;
		}
		return "default";
	}
	return $theme;
}


/**
 * dummy
 *
 * @param	string	$theme Theme to use (default: "default")
 * @param	bool	$include_once Should we use include_once? (default: true)
 */
function template_GetHeader( $theme = null, $include_once = true ) {
	template_Get( "/header.html.php", $theme, $include_once );
}
/**
 * dummy
 *
 * @param	string	$theme Theme to use (default: "default")
 * @param	bool	$include_once Should we use include_once? (default: true)
 */
function template_GetFooter( $theme = null, $include_once = true ) {
	template_Get( "/footer.html.php", $theme, $include_once );	
}

?>
