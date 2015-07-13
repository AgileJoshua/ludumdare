<?php 
require_once __DIR__ . "/../web.php";
require_once __DIR__ . "/../core/constants.php";
require_once __DIR__ . "/../core/node.php";
require_once __DIR__ . "/../core/internal/sanitize.php";
require_once __DIR__ . "/../core/internal/emoji.php";
require_once __DIR__ . "/../core/post.php";

user_StartEnd();

//template_SetTheme("embed");

// Modes //
const M_DEFAULT = 1;	// Default State //
const M_ERROR = -255;	// Other Error //

$mode = M_DEFAULT;


$args = core_ParseActionURL();
$args_count = count($args);

// Sanitize Input
foreach( $args as $arg ) {
	$arg = sanitize_Slug($arg);
	if ( empty($arg) ) {
		$mode = M_ERROR;
		break;
	}
}

if ( $mode > 0 ) {
	$args_merged = implode('/',$args);
	
	
	$paths = [ CMW_NODE_ROOT ];
	foreach( $args as $key => $slug ) {
		$id = node_GetNodeIdByParentIdAndSlug($paths[$key],$slug);
		if ( !empty($id) ) {
			$paths[] = $id;
		}
		else {
			$mode = M_ERROR;
			break;			
		}
	}
	if ( $mode > 0 ) {
		$paths_count = count($paths);
		$this_node_id = &$paths[$paths_count-1];
		
		$this_node = node_GetNodeById( $this_node_id );
		$author_node = [];
		if ( !empty($this_node['author']) && ($this_node['author'] > 0) ) {
			$author_node = node_GetNodeById( $this_node['author'] );
		}
		if ( !empty($this_node['parent']) && ($this_node['parent'] > 0) ) {
			$parent_node = node_GetNodeById( $this_node['parent'] );
		}
		
		$nodes = node_GetNodesByParentId( $this_node_id );
		$nodes_count = count($nodes);
	}
}

?>
<?php template_GetHeader(); ?>
<body>
	<script>
		// Cookie Functions //
		function getCookie(name) {
			var value = "; " + document.cookie;
			var parts = value.split("; " + name + "=");
			if (parts.length == 2) 
				return parts.pop().split(";").shift();
			return parts;
		}
		function setCookie(name,value) {
			document.cookie = name + '=' + value;
		}
		function removeCookie( name ) {
			document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
		}
	</script>
	
	<style>
		/* Top Navigation Bar */
		#ldbar-outer {			
			width:100%;
			height:48px;

			/*position:fixed;*/
			left:0;
			top:0;
			z-index:1000;
			
			/* shadow */
			border-bottom:1px solid #000;/*rgba(96,96,96,0.2);*/
		}
		#ldbar-inner {
			color:#fff;
			background:/*#765*/ rgba(96,96,96,1.0);
			
			line-height:47px;
			
			position:relative;
			width:100%;
		}
		/* Padding in the main document */
		#ldbar-pad {			
			width:100%;
			height:49px; /* plus shadow */
		}

		.noselect {
		    -webkit-touch-callout: none;
		    -webkit-user-select: none;
		    -khtml-user-select: none;
		    -moz-user-select: none;
		    -ms-user-select: none;
		    user-select: none;
		}
		#ldbar img {
			/*vertical-align:middle;*/
		}
		
		#ldbar-toggle {
			font-family:monospace;
			margin-left:32px;
			cursor:pointer;
		}
		
		#ldbar-notifications {
			font-size:20px;
			margin-left:24px;
			font-weight:bold;
			border-radius:9px;
			cursor:pointer;
			display:inline-block !important;
			width:32px;
			height:32px;
			line-height:32px;
			text-align:center;
			font-family:monospace;
			position:relative;
			text-shadow:0 2px rgba(0,0,0,0.5);
			
			background:rgba(255,32,48,0.5);
			border:3px solid #000;
			top:0px;

   			-webkit-transition:all 0.125s;
    		transition:all 0.125s;
		}
		#ldbar-notifications:hover {
			background:rgba(255,32,48,0.8);
			border-top:1px solid #000;
			border-bottom:5px solid #000;
			top:-2px;
		}

		#ldbar-logo {
			position:relative;
			cursor:pointer;
			
			top:9px;
			opacity:0.7;
 
   			-webkit-transition:all 0.125s;
    		transition:all 0.125s;
		}
		#ldbar-logo:hover {
			top:6px;
			opacity:1.0;
		}


		#ldbar .leftnav {
			display:inline;
			padding-left:64px;
			
		}
		#ldbar .rightnav {
			float:right;
			display:inline;
			/*padding-right:64px;*/
		}
		
		
		#ldbar .cell {
			display:inline;
		}
		
		#ldbar .size32 {
			position:relative;
			width:32px;
			height:32px;
			top:8px;
		}

	</style>
	
	<div id='ldbar' class='noselect'>
		<div id='ldbar-outer' style='position:fixed;'>
			<div id='ldbar-inner'>
				<div class='leftnav'>
					<div id='ldbar-logo' class='cell'><a href="/"><img src="<?php STATIC_URL(); ?>/logo/ludumdare/2009/LudumDareLogo80W.png" height="30" /></a></div>
				</div>
				<div class='rightnav'>
					<div class='cell size32' onclick='SendLogin();'><img src="<?php STATIC_URL(); ?>/logo/mike/Chicken64W.png" width="32" height="32" style="mix-blend-mode:screen" /></div>
					<div id='ldbar-notifications' class='cell' onclick='SendLogout();'>0</div>
					<div id='ldbar-toggle' class='cell' onclick='toggleLDBar();' title='Toggle Sticky Bar'>[x]</div>
				</div>
			</div>
		</div>
		<div id='ldbar-pad'></div>
	</div>
	
	<script>
		function SendLogin() {
			xhr_PostJSON( "/a/user/login/", "l=pov&p=blahblah",
				function( response, code ) {
					if ( code == 200 ) {
						document.getElementById("ldbar-notifications").innerHTML = response['id'];
					}
					else if ( code == 401 ) {
						document.getElementById("ldbar-notifications").innerHTML = ":(";
					}
					else {
						document.getElementById("ldbar-notifications").innerHTML = "x";
					}
				}
			);
		}

		function SendLogout() {
			xhr_PostJSON( "/a/user/logout/", "",
				function( response, code ) {
					if ( code == 200 ) {
						document.getElementById("ldbar-notifications").innerHTML = 0;
					}
					else {
						document.getElementById("ldbar-notifications").innerHTML = "x";
					}
				}
			);
		}
		
		function updateLDBar() {
			if ( getCookie('ldbar') === '1' ) {
				document.getElementById('ldbar-outer').style.position = '';
				document.getElementById('ldbar-pad').style.display = 'none';
				document.getElementById('ldbar-toggle').innerHTML = '[ ]';
			}
			else {
				document.getElementById('ldbar-outer').style.position = 'fixed';
				document.getElementById('ldbar-pad').style.display = '';
				document.getElementById('ldbar-toggle').innerHTML = '[x]';
			}
		}
		function toggleLDBar() {
			var val = !(getCookie('ldbar') === '1');
			if ( val ) {
				setCookie('ldbar',1);
			}
			else {
				removeCookie('ldbar');
			}
			updateLDBar();
		}
		updateLDBar();
		
		function post_ShowEdit( id ) {
			document.getElementById('flextext-'+id).style.display = '';
			document.getElementById('preview-'+id).style.display = 'none';
		}
		function post_ShowPreview( id ) {
			document.getElementById('flextext-'+id).style.display = 'none';
			document.getElementById('preview-'+id).style.display = '';
		}
		
		function post_UpdateEdit( o ) {
			var rows = o.value.split("\n").length;
			o.rows = (rows > 4) ? rows : 4;
		}
	</script>
	
	<!-- *************** -->
	
	<style>
		body {
			background: #EEE;
		}
		#nav .row {
			margin-left:16px;
			padding:2px;
		}
		#nav .row:hover {
			background:#DDF;
		}
		#nav .slug {
			display:inline;
			font-family:monospace;
		}
		#nav .id, #nav .name, #nav .type {
			display:inline;
			position:absolute;
		}
		#nav .id {
			left:256px;
		}
		#nav .name {
			left:360px;
		}
		#nav .type {
			left:960px;
		}
		
		#nav .proxy {
			background:#DFD;
		}
		#nav .link {
			background:#FDD;
		}
		#nav .authored {
			background:#FCF;
		}
		
		#content .post {
			margin:16px;
			border:2px solid #000;
			background:#FFF;
		}
		
		#content .post h1, .post h3 {
			margin:0;
		}
		
		#content .post .header {
			margin:8px;		
		}
		#content .post .body {
			margin:8px;
		}
		#content .post .footer {
			background:#CCC;
			padding:8px;			
		}
		
		#metas .node {
			padding-left:24px;
			font-family:monospace;
		}
		#metas .key {
			display:inline;
			font-weight:bold;
		}
		
		#content .post span code {
			background:#FDC;
			padding:3px;
			margin:0 3px;
		}
		
		
		/* FlexText */
		.flextext {
		    position: relative;
		    *zoom: 1;
		}

		textarea,
		.flextext {
		    outline: 0;
		    margin: 0;
		    border: none;
		    padding: 0;
		
		    *padding-bottom: 0 !important;
		}

		.flextext textarea,
		.flextext pre {
		    white-space: pre-wrap;
		    width: 100%;
		    -webkit-box-sizing: border-box;
		    -moz-box-sizing: border-box;
		    box-sizing: border-box;
		
		    *white-space: pre;
		    *word-wrap: break-word;
		}

		.flextext textarea {
		    overflow: hidden;
		    position: absolute;
		    top: 0;
		    left: 0;
		    height: 100%;
		    width: 100%;
		    resize: none;
		
		    /* IE7 box-sizing fudge factor */
		    *height: 94%;
		    *width: 94%;
		}

		.flextext pre {
		    display: block;
		    visibility: hidden;
		}

		textarea,
		.flextext pre {
			/*margin: 0;*/
		    border: 1px solid #000;
		    padding: 4px;
		    background:#EED;
		    /*
		     * Add custom styling here
		     * Ensure that typography, padding, border-width (and optionally min-height) are identical across textarea & pre
		     */
		}
	</style>

	<?php if ( $mode === M_DEFAULT ) { ?>
	
	<?php if ( $this_node['type'] === 'root' ) { ?>
	<div id="content">
		<?php
			$posts = node_GetPosts(10);
			$author_ids = [];
			foreach($posts as $post) {
				$author_ids[] = $post['author'];
			}
			$author_ids = array_unique($author_ids);
			$authors = node_GetNodeArrayByIds($author_ids);
			
			foreach($posts as $post) {
				echo '<div class="post" id="post-"'.$post['id'].'>';
				echo '<div class="header">';
					echo '<h1>' . $post['name'] .'</h1>';
					echo '<small>[Local time goes here] (' . date('l, d F Y H:i:s T',$post['time_published']) . ')</small>';
					if ( !empty($post['author']) ) { 
						echo "<h3>by <a href='/user/".$authors[$post['author']]['slug']."'>" . $authors[$post['author']]['name'] . "</a></h3>"; 
					}
				echo "</div>\n";
				echo '<div class="body">';
					// TODO: replace all the textarea related code with a function call for generating one. JS and PHP.
					// TODO: Add TAB support (and ESC to de-focus the textbox, since otherwise keyboard can't de-focus)
					// TODO: Emoji Autocompletion
					// TODO: Image Autocompletion (once uploaded)
					// TODO: Jumbomoji support (i.e. my *bigger* custom emoji. :key-wasd: etc)
					// TODO: Jumbomoji Autocompletion
					// TODO: Fix smileys surrounded by \n's (not working if an \n is on either side)
					if ( !empty($post) ) {
						// http://alistapart.com/article/expanding-text-areas-made-elegant
						// https://github.com/alexdunphy/flexText
						echo '<div class="flextext" id="flextext-'.$post['id'].'" style="display:none;">';
							echo '<pre><span id="flextext-span-'.$post['id'].'"></span><br /><br /></pre>';
							echo '<textarea class="edit" id="edit-'.$post['id'].'" oninput="flextext_Update('.$post['id'].');" onpropertychange="flextext_Update('.$post['id'].');" onkeyup="flextext_Update('.$post['id'].');" onchange="flextext_Update('.$post['id'].');">' . $post['body'] . '</textarea>';
						echo '</div>';
						echo '<div class="preview" id="preview-'.$post['id'].'"></div>';
					}
				echo '</div>';
				echo '<div class="footer">';
					echo '<span onclick="post_ShowEdit('.$post['id'].');">PATCH</span>';
					echo ' | ';
					echo '<span onclick="post_ShowPreview('.$post['id'].');post_Parse('.$post['id'].');">PREVIEW</span>';
				echo '</div>';
				echo '</div>';
			}
		?>
		<!-- <?php print_r($posts); ?> -->
		<!-- <?php print_r($author_ids); ?> -->
		<!-- <?php print_r($authors); ?> -->
	</div>
	<?php } else { ?>
	<div id="content">
		<div class="post">
			<div class="title"><h1><?php echo $this_node['name']; ?></h1><?php if ( $author_node ) { echo "<h3>by <a href='/user/".$author_node['slug']."'>" . $author_node['name'] . "</a></h3>"; } ?></div>
			<?php 
				if ( !empty($this_node['body']) ) { 
					?><textarea class="body-edit" rows="24" cols="120" style="display:none;"><?php echo $this_node['body']; ?></textarea>
					<div class="body"></div><?php 
				} ?>
			<?php
				if ( $this_node['type'] === 'user' ) {
					echo "<br />";
					echo '<div id="games"><h3>Games:</h3>';
					$games = node_GetNodesByAuthorIdAndType($this_node['id'],'game');
					foreach( $games as $game ) {
						echo "<a href=\"/user/" . $this_node['slug'] . "/" . $game['slug'] . "\">" . $game['name'] . "</a><br/>"; 
					}
					echo '</div>';
					echo "<br />";
					echo '<div id="metas"><h3>Metadata ('.$this_node['id'].'):</h3>' ;
					$metas = node_GetMetasById( $this_node['id'] );
					function process_meta( $meta ) {
						echo "<div class='node'>";
						foreach( $meta as $key => $value ) {
							if ( is_array($value) ) {
								if ( is_string($key) )
									$key = "\"".$key."\"";
	
								echo "<div class='key'>".$key . ":</div><br/>";
								process_meta($value);
							}
							else {
								if ( is_string($key) )
									$key = "\"".$key."\"";
								
								if ( is_string($value) )
									echo "<div class='key'>".$key . ":</div> \"" . $value . "\"<br />";
								else
									echo "<div class='key'>".$key . ":</div> " . $value . "<br />";
							}
						}
						echo "</div>";
					}
					process_meta($metas);
					
//					print_r($metas);
					echo "</div>";
				}
			?>	
		</div>
	</div>
	<?php } /* type */ ?>
	
	<div id='nav' style="padding:16px;">
		<?php
			echo "/" . $args_merged . " [".$this_node_id."]<br />\n";
			if ( $args_count >= 1 ) {
				echo "<div class='row'>\n";
				echo "<div class='slug'><a href='../'>../</a></div><br />\n";
				echo "</div>\n";	
			}
			foreach( $nodes as $node ) {
				echo "<div class='row ".$node['type'].($node['author']>0?" authored":"")."'>\n";
				if ( $node['type'] === 'link' ) {
					echo "<div class='slug'><a href='".$node['name']."/'>" . $node['slug'] . "/</a></div> <div class='id'>*".$node['id']."*</div> <div class='type'>(".$node['type'].")</div><br />\n";
				}
				else {
					echo "<div class='slug'><a href='".$node['slug']."/'>" . $node['slug'] . "/</a></div> <div class='id'>[".$node['id']."]</div> <div class='name'>".$node['name']."</div> <div class='type'>(".$node['type'].")</div><br />\n";
				}
				echo "</div>\n";	
			}
		?>
	</div>
	<div>
		<img src="//192.168.48.48:8080/img/logo/mike/Chicken16.png?crop&w=256&h=128">
	</div>
<?php /*
	<div id="debug">
		<h2>Debug S:</h2>
		<?php
			if ( isset($_SESSION) ) {
				print_r($_SESSION);
				echo "<br />";
			}
			echo "Login token is " . (_user_IsLoginTokenValid() ? "valid" : "invalid") . ".";
		?>
		<h2>Debug C:</h2>
		<script>
			document.getElementById('debug').innerHTML += document.cookie;
		</script>
	</div>
*/ ?>
	<?php } else if ( $mode === M_ERROR ) { ?>	
		<div id="content">
			<div class="title"><h1><a href=".">Nope</a></h1></div>
		</div>

	<?php } /* $mode */ ?>	

	<script>
		emojione.ascii = true;
	
		// Process Emoji on all the following sections //
		var Section = [
			'nav','games'
		];
		for(var Key in Section) {
			var el = document.getElementById(Section[Key]);
			if ( el ) {
				el.innerHTML = emojione.toImage(el.innerHTML);
			}
		}
		
		var el = document.getElementById('content');
		var original = el.getElementsByClassName('body-edit');
		var preview = el.getElementsByClassName('body');
		for(var idx = 0, len = original.length; idx < len; ++idx) {
			preview[idx].innerHTML = html_Parse(original[idx].innerHTML);
		}
		
		var el = document.getElementById('content');
		var original = el.getElementsByClassName('edit');
		var preview = el.getElementsByClassName('preview');
		for(var idx = 0, len = original.length; idx < len; ++idx) {
			preview[idx].innerHTML = html_Parse(original[idx].innerHTML);
		}
		
		function post_Parse(id) {
			var src = document.getElementById('edit-'+id);
			var dest = document.getElementById('preview-'+id);
			dest.innerHTML = html_Parse(src.value);
		}
		
		function flextext_Update(id) {
			console.log(id);
			var src = document.getElementById('edit-'+id);
			var dest = document.getElementById('flextext-span-'+id);
			dest.innerHTML = src.value;
		}
		
		var AllFlexTexts = document.getElementsByClassName("flextext");
		for ( var idx = 0, len = AllFlexTexts.length; idx < len; ++idx ) {
			var AllSpans = AllFlexTexts[idx].getElementsByTagName("span");
			var AllTextAreas = AllFlexTexts[idx].getElementsByTagName("textarea");
			for ( var idx2 = 0, len2 = AllTextAreas.length; idx2 < len2; ++idx2 ) {
				AllSpans[idx2].innerHTML = AllTextAreas[idx2].value;
			}
		}
	</script>
</body>
<?php template_GetFooter(); ?>
