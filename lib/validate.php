<?php

// Validate Things. //


// Given a URL, returns a valid (escaped) URL, or false if it's bad. //
function validate_url($url) {
	// Step 0. Confirm that the input is UTF-8 encoded.
	if ( !mb_check_encoding($url, 'UTF-8') ) {
		// ERROR: Expected URL in UTF-8 encoding.
		return false;
	}
	
	// Step 1. Trim whitespace. This should be multibyte friendly: http://stackoverflow.com/a/10067670
	$url = trim($url);
	
	// Step 2. Confirm that it's a valid URL (i.e. has a scheme).  http://en.wikipedia.org/wiki/URI_scheme
	$protocols = [
		// Standard URLs (scheme://path/?query).
		'http', 'https',
		'ftp', 'sftp',
		//'file',					// **DO NOT ENABLE**. By ignoring file://, we help unsavvy users.

		// Non-file URLs.
		//'telnet',					// ... undecided if we need this.
		//'ssh',					// ... also undecided. Some version control softwares uses this as secure.
		'git', 'svn', 'cvs',		// Version Control. git://user@server:path/to/repo.git
		'irc', 'irc6', 'ircs',		// Text Chat.		irc://irc.afternet.org:6667/ludumdare
		'rtmp', 'rtmfp',			// Video.			rtmp://mycompany.com/vod/mp4:mycoolvideo.mov
		'ventrilo',	'mumble',		// Audio Chat.		ventrilo://www.myserver.com:3784/servername=MyServer
		'ts3server',				// Audio Chat.		ts3server://IPADDRESS/?port=YOUR_PORT&nickname=Web+Guest
		'steam',					// Steam Client.	steam://<action>/<id, addon, IP, hostname, etc.>
		
		// Other URLs.
		'mailto',					// mailto:me@somewebsite.com?subject=hey+dawg
		'magnet',					// magnet:?xt=urn:sha1:YNCKHTQCWBTRNJIV4WNAE52SJUQCZO5C
		//'bitcoin',				// ... undecided if we should allow this or not.
		'about', 'opera', 'chrome',	// Web-browser internal.
	];
	
	// NOTE: parse_url isn't multibyte aware, so you should only rely on scheme and the existence of other members.
	
	$parsed = parse_url($url);
	$protocol = false;
	// If a scheme is set //
	if ( isset($parsed['scheme']) ) {
		foreach ( $protocols as $item ) {
			if ( $item === strtolower($parsed['scheme']) ) {
				$protocol = $item;
				break;
			}
		}
	}
	else {
		// If no scheme is set, but there is a path, assume it's http.
		if ( isset($parsed['path']) ) {
			$url = 'http://' . $url;
			$protocol = 'http';
		}
	}
	if ( $protocol === false ) {
		// ERROR: Unknown URL scheme.
		return false;
	}
	// We now know the protocol. It will always be lower case.
	
	// Step 3. Escape URL.
	$url = htmlspecialchars($url,ENT_QUOTES,'UTF-8',false);
//	if ( $url === false ) {
//		// ERROR: Invalid URL.
//		return false;
//	}
	
	return $url;
}

// TODO: this
function validate_email($mail) {
	return $mail;
}

?>