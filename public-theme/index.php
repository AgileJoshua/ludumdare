<?php
require_once __DIR__ . "/../web.php";
require_once __DIR__ . "/../core/config.php";

const EVENT_NAME = "Ludum Dare 34";

define('HTML_TITLE',EVENT_NAME." - Theme Hub");
const HTML_CSS_INCLUDE = [ "/style/theme-hub.css.php" ];
const HTML_USE_CORE = true;
const HTML_SHOW_FOOTER = true;

// ** Modes ** //
const THEME_MODE_NAMES = [
	"Inactive",						// no event scheduled
	"Theme Suggestion Round",		// weeks -5 to -2 (AKA: Ideas)
	"Theme Slaughter Round",		// weeks -2 to -1
	"Theme Voting Round",			// week -1 to day -2
	"Final Round Theme Voting",		// day -2 to start -30 minutes
	"Theme Announcement",			// start
	"Coming Soon"					// week +3 (next event scheduled)
];

const THEME_MODE_SHORTNAMES = [
	"Inactive",
	"Suggestion",
	"Slaughter",
	"Voting",
	"Final Voting",
	"Announcement",
	"Coming Soon"
];

config_Load();

$active_mode = 1;

function ShowHeadline() {
	global $active_mode;
	echo "<div class='headline'>";
	echo "<div class='title bigger'><strong>".strtoupper(THEME_MODE_NAMES[$active_mode])."</strong></div>";
	echo "<div class='clock' id='headline-clock'>Round ends in <span id='headline-time'>X days, 12 hours</span></div>";
	echo "</div>";
}

function ShowLogin() {
	
}
function ShowComingSoon() {
	
}
function ShowIdeas() { ?>
	<div class="action" id="action-idea">
		<div class="title bigger">Suggest a Theme</div>
		<div class="form">
			<input type="text" class="single-input" id="input-idea" placeholder="your suggestion" maxlength="64" />
			<button type="button" class="submit-button" onclick="SubmitIdeaForm();">Submit</button>
		</div>
		<div class="footnote small">You have <strong><span id="sg-count">??</span></strong> suggestion(s) left</div>
	</div>
<?php
}
function ShowExtra() { ?>
	<div class="sg" id="extra-sg">
		<div class="title big">My Suggestions</div>
		<div id="sg"></div>
	</div>
<?php 
} ?>
<?php template_GetHeader(); ?>
<div class="header"><?php 
	if ( defined('EVENT_NAME') ) {
		echo "<div class='event big inv'>Event: <strong class='caps' id='event-name'>".EVENT_NAME."</strong></div>";

		echo "<div class='mode small caps'>";
		$theme_mode_count = count(THEME_MODE_SHORTNAMES);
		for ( $idx = 1; $idx < $theme_mode_count-1; $idx++ ) {
			if ($idx !== 1)
				echo " | ";
			if ($idx === $active_mode)
				echo "<strong>".THEME_MODE_SHORTNAMES[$idx]."</strong>";
			else
				echo THEME_MODE_SHORTNAMES[$idx];
		}
		echo "</div>";

		echo "<div class='date normal inv caps' id='event-date'>Starts at <strong>9:00 PM</strong> on Friday <strong>December 11th, 2015</strong> (EST)</strong></div>";
	}
?>
</div>
<?php
	if ( !empty($CONFIG['theme-alert']) ) {
		echo "<div class='alert caps'>",$CONFIG['theme-alert'],"</div>";
	}
?>
<script>
	function sg_AddIdea(Id,Idea) {
		Id = Number(Id);
		Idea = escapeQuotes(Idea);
		document.getElementById('sg').innerHTML = 
			"<div class='sg-item' id='sg-item-"+Id+"'>" +
				"<span class='sg-item-x' onclick='sg_RemoveIdea("+Id+",\""+Idea+"\")'>✕</span>" +
				"<span class='sg-item-text' title='"+Idea+"'>"+Idea+"</span>" +
			"</div>" +
			document.getElementById('sg').innerHTML;
	}

	function sg_RemoveIdea(Id,Idea) {
		Id = Number(Id);
		if ( window.confirm(Idea+"\n\nAre you sure you want to delete this?") ) {
			xhr_PostJSON(
				"/api-theme.php",
				serialize({"action":"DELETE","id":Id}),
				// On success //
				function(response,code) {
					console.log(response);
					var el = document.getElementById('sg-item-'+response.id);
					if ( el ) {
						el.remove();
					}
					sg_UpdateCount(response.ideas_left);
				}
			);			
		}
	}
	
	function sg_UpdateCount(count) {
		document.getElementById('sg-count').innerHTML = count;			
	}
		
	function SubmitIdeaForm() {
		var elm = document.getElementById('input-idea');
		var Idea = elm.value.trim();
		elm.value = "";
		
		if ( Idea === "" )
			return;

		xhr_PostJSON(
			"/api-theme.php",
			serialize({"action":"SUBMIT","idea":Idea}),
			// On success //
			function(response,code) {
				console.log(code,response);
				
				var Id = Number(response.id);
				var Idea = response.idea;
				
				sg_UpdateCount(Number(response.ideas_left));
		
				sg_AddIdea(Id,Idea);
			}
		);

		elm.focus();
	}
	
	window.onload = function() {
		xhr_PostJSON(
			"/api-theme.php",
			serialize({"action":"GET"}),
			// On success //
			function(response,code) {
				response.ideas.forEach(function(response) {
					sg_AddIdea(response.id,response.theme);
				});
				
				sg_UpdateCount(response.ideas_left);
			}
		);
	}
</script>
<div class="body">
	<div class="main">
		<?php
			ShowHeadline();
			ShowIdeas();
		?>
	</div>
	<div class="extra">
		<?php
			ShowExtra();
		?>
	</div>
</div>
<?php template_GetFooter();