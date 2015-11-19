<?php
require_once __DIR__."/../web.php";
require_once __DIR__."/../core/config.php";
require_once __DIR__."/../legacy-config.php";
require_once __DIR__."/../core/legacy_user.php";

const EVENT_NAME = "Ludum Dare 34";

define('HTML_TITLE',EVENT_NAME." - Theme Hub");
const HTML_CSS_INCLUDE = [ "/style/theme-hub.css.php" ];
const HTML_USE_CORE = true;
const HTML_SHOW_FOOTER = true;


// Extract Id from Cookie
if ( isset($_COOKIE['lusha']) ) {
	$cookie_id = legacy_GetUserFromCookie();
	//intval(explode('.',$_COOKIE['lusha'],2)[0]);
	
	if ( $cookie_id === 0 ) {
		?><script>DoLogout();</script><?php
	}
}
else {
	$cookie_id = 0;
}


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

function ShowHeader() {
	global $active_mode;
	if ( defined('EVENT_NAME') ) {
		echo "<div class='event bigger big-space'>Event: <strong class='caps inv' id='event-name'>".EVENT_NAME."</strong></div>";

		echo "<div class='mode small caps'>";
		$theme_mode_count = count(THEME_MODE_SHORTNAMES);
		for ( $idx = 1; $idx < $theme_mode_count-1; $idx++ ) {
			if ($idx !== 1)
				echo " | ";
			if ($idx === $active_mode)
				echo "<strong>".strtoupper(THEME_MODE_SHORTNAMES[$idx])."</strong>";
			else
				echo strtoupper(THEME_MODE_SHORTNAMES[$idx]);
		}
		echo "</div>";
		
		$EventDate = new DateTime("2015-12-12T02:00:00Z");

		//echo "<div class='date normal inv caps' id='event-date'>Starts at <strong id='ev-time'>9:00 PM</strong> on <span id='ev-day'>Friday</span> <strong id='ev-date'>December 11th, 2015</strong> (<span id='ev-zone'>EST</span>)</strong></div>";
		echo "<div class='date normal inv caps' id='event-date' title=\"".$EventDate->format("G:i")." on ".$EventDate->format("l F jS, Y ")."(UTC)\">Starts at ".
			"<strong id='ev-time' original='".$EventDate->format("G:i")."'></strong> on ".
			"<span id='ev-day' original='".$EventDate->format("l")."'></span> ".
			"<strong id='ev-date' original='".$EventDate->format("F jS, Y")."'></strong> ".
			"(<span id='ev-zone' original='UTC'></span>)</strong></div>";

?>
		<script>
			var EventDate = new Date("<?=$EventDate->format(DateTime::W3C)?>");

			/*
			var time_locale = navigator.language;
			
			// Since official time standards don't necessarily match common use, remap time locales //
			var LocaleRemapTable = {
				'en-GB':'en-US'
			};
			if ( LocaleRemapTable.hasOwnProperty(navigator.language) ) {
				time_locale = LocaleRemapTable[navigator.language];
			}
			
			// If English //
			if ( time_locale.indexOf("en-") >= 0 ) {
				var DateSuffix = [
					"th","st","nd","rd","th","th","th","th","th","th",
					"th","th","th","th","th","th","th","th","th","th"
				];
				var EvDateSuffix = DateSuffix[EventDate.getDate() % 20];
			}
			else {
				var EvDateSuffix = "";
			}
			
			// NOTE: Safari does not support toLocaleString //
//			if ( 'toLocaleString' in Date.prototype ) {
//				var EvTime = EventDate.toLocaleTimeString(time_locale,{"hour":"2-digit","minute":"2-digit"});
//				var EvDay = EventDate.toLocaleString(time_locale,{"weekday":"long"});
//				if ( EvDateSuffix ) {
//					var EvDate = EventDate.toLocaleString(time_locale,{"month":"long","day":"numeric"}) +
//						EvDateSuffix + ", " +
//						EventDate.toLocaleString(time_locale,{"year":"numeric"});
//				}
//				else {
//					var EvDate = EventDate.toLocaleString(time_locale,{"month":"long","day":"numeric","year":"numeric"});
//				}
//				var EvTimeZone = GetTZ(EventDate);
//			}

			{
				var DayOfTheWeek = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
				var MonthOfTheYear = [
					"January","February","March","April","May","June","July",
					"August","September","October","November","December"
				];

				// Check toLocaleTimeString for 12 hour clock, or if language English, assume 12 hour clock //				
				if ( ('toLocaleTimeString' in Date.prototype)
					&& (EventDate.toLocaleTimeString(time_locale).indexOf('M') > -1)
					|| (time_locale.indexOf("en-") >= 0) ) 
				{
					var EvHalfDay = (EventDate.getHours() - 12) >= 0;
					var EvTime = (EventDate.getHours() % 12) + ":" + 
						new String("00"+EventDate.getMinutes()).slice(-2) + 
						(EvHalfDay?" PM":" AM");
				}
				else {
					var EvTime = new String("00"+EventDate.getHours()).slice(-2) + ":" + new String("00"+EventDate.getMinutes()).slice(-2);
				}
				var EvDay = DayOfTheWeek[EventDate.getDay()];
				var EvDate = MonthOfTheYear[EventDate.getMonth()] + " " + 
					EventDate.getDate() + EvDateSuffix + ", " + 
					EventDate.getFullYear();
				var EvTimeZone = GetTZ(EventDate);
			}
			
			dom_SetText( 'ev-time', EvTime );
			dom_SetText( 'ev-day', EvDay );
			dom_SetText( 'ev-date', EvDate );
			dom_SetText( 'ev-zone', EvTimeZone );
			*/

			dom_SetText( 'ev-time', getLocaleTime(EventDate) );
			dom_SetText( 'ev-day', getLocaleDay(EventDate) );
			dom_SetText( 'ev-date', getLocaleDate(EventDate) );
			dom_SetText( 'ev-zone', getLocaleTimeZone(EventDate) );
		</script>
<?php
	}
}

function ShowHeadline() {
	global $active_mode;
	echo "<div class='headline'>";
	echo "<div class='title bigger caps space inv soft-shadow'><strong>".(THEME_MODE_NAMES[$active_mode])."</strong></div>";
	
	// Date Hack //
	$EventDate = strtotime("2015-12-12T02:00:00Z");
	$TargetDate = $EventDate - (2*7*24*60*60) + (18*60*60);
	$DateDiff = $TargetDate - time();
	
	$SEC = $DateDiff % 60;
	$MIN = ($DateDiff / 60) % 60;
	$HOUR = ($DateDiff / (60*60)) % 24;
	$DAY = ($DateDiff / (60*60*24)) % 7;
	$WEEK = floor($DateDiff / (60*60*24*7));
	
	$OutTime = "";
	if ( $WEEK > 0 ) {
		if ( $WEEK > 1 )
			$OutTime .= $WEEK." weeks";
		else if ( $WEEK == 1 )
			$OutTime .= $WEEK." week";
	}

	if ( $DAY > 0 ) {
		if ( !empty($OutTime) )
			$OutTime .= ", ";
		if ( $DAY > 1 )
			$OutTime .= $DAY." days";
		else if ( $DAY == 1 )
			$OutTime .= $DAY." day";
	}

	if ( $HOUR > 0 ) {
		if ( !empty($OutTime) )
			$OutTime .= ", ";
		if ( $HOUR > 1 )
			$OutTime .= $HOUR." hours";
		else if ( $HOUR == 1 )
			$OutTime .= $HOUR." hour";
	}
	
	$UTCDate = date(DATE_RFC850,$TargetDate);

	echo "<div class='clock' id='headline-clock'>Round ends in <span id='headline-time' title=\"".$UTCDate."\">".$OutTime."</span></div>";
	echo "</div>";
}

function ShowLogin() {
?>
	<div class="action" id="action-login">
		<a href="<?= LEGACY_LOGIN_URL ?>"><button type="button" class="login-button">Login</button></a>
	</div>	
<?php
}
function ShowLogout() {
?>
	<div class="action" id="action-logout">
		<button type="button" class="login-button" onclick="DoLogout()">Logout</button>
	</div>	
<?php
}
function ShowInactive() { ?>
	<div class='headline no-margin'>
		<div class="title bigger"><strong>BE RIGHT BACK!</strong></div>
		<div>We're just fixing things. Give us a moment. Fixy fixy!</div>
		<br />
		<div id="twitter-widget">
			<a class="twitter-timeline" data-dnt="true" href="https://twitter.com/ludumdare" data-widget-id="665760712757657600">Tweets by @ludumdare</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
      	</div>
	</div><?php
}


function ShowComingSoon() {
	
}
function ShowSubmitIdea() { ?>
	<div class="action" id="action-idea">
		<div class="title bigger">Suggest a Theme</div>
		<div class="form">
			<input type="text" class="single-input" id="input-idea" placeholder="Your suggestion" maxlength="64" />
			<button type="button" class="submit-button" onclick="SubmitIdeaForm();">Submit</button>
		</div>
		<div class="footnote small">You have <strong><span id="sg-count">?</span></strong> suggestion(s) left</div>
		<script>
			document.getElementById("input-idea").addEventListener("keydown", function(e) {
				if (!e) { var e = window.event; }
				if (e.keyCode == 13) { /*e.preventDefault();*/ SubmitIdeaForm(); }
			}, false);
		</script>
	</div>
<?php
}
function ShowExtra() { ?>
	<div class="sg" id="extra-sg">
		<div class="title big caps space">My Suggestions</div>
		<div id="sg"></div>
	</div>
<?php 
} ?>
<?php template_GetHeader(); ?>
<div class="invisible" id="dialog-back" onclick='dialog_Close();'>
	<div id="dialog" onclick="event.stopPropagation();">
		<div class="title big" id="dialog-title">Title</div>
		<div class="body">
			<div><img id="dialog-img" src="http://cdn.jsdelivr.net/emojione/assets/png/26A0.png?v=1.2.4" width=64 height=64"></div>
			<div id="dialog-text">Text</div>
		</div>
		<a href="#" id="dialog-focusfirst"></a>
		<div class="buttons hidden" id="dialog-yes_no">
			<button id="dialog-yes" class="normal focusable" onclick='dialog_DoAction();'>Yes</button>
			<button id="dialog-no" class="normal focusable" onclick='dialog_Close();'>No</button>
		</div>
		<div id="dialog-ok_only" class="buttons hidden">
			<button id="dialog-ok" class="normal focusable" onclick='dialog_Close();'>OK</button>
		</div>
		<a href="#" id="dialog-focuslast"></a>
	</div>
</div>
<div class="header"><?php
	ShowHeader();
?>
</div>
<?php
	if ( !empty($CONFIG['theme-alert']) ) {
		echo "<div class='alert'>",$CONFIG['theme-alert'],"</div>";
	}
?>
<script>
	function DoLogout() {
		xhr_PostJSON(
			"/api-legacy.php",
			serialize({"action":"LOGOUT"}),
			// On success //
			function(response,code) {
				console.log(response);
				location.reload();
			}
		);			
	}
	
	function sg_AddIdea(Id,Idea,accent) {
		Id = Number(Id);
		Idea = escapeString(Idea);
		IdeaAttr = escapeAttribute(Idea);
		
		var sg_root = document.getElementById('sg');
		
		var node = document.createElement('div');
		node.setAttribute("class",'sg-item'+((accent===true)?" effect-accent":""));
		node.setAttribute("id","sg-item-"+Id);
		node.innerHTML = 
			"<div class='sg-item-x' onclick='sg_RemoveIdea("+Id+",\""+(IdeaAttr)+"\")'>✕</div>" +
			"<div class='sg-item-text' title='"+(Idea)+"'>"+(Idea)+"</div>";
		
		sg_root.insertBefore( node, sg_root.childNodes[0] );
		//sg_root.appendChild( node );
		
//		document.getElementById('sg').innerHTML = 
//			"<div class='sg-item effect-accent' id='sg-item-"+Id+"'>" +
//				"<div class='sg-item-x' onclick='sg_RemoveIdea("+Id+",\""+(IdeaAttr)+"\")'>✕</div>" +
//				"<div class='sg-item-text' title='"+(Idea)+"'>"+(Idea)+"</div>" +
//			"</div>" +
//			document.getElementById('sg').innerHTML;
	}

	function sg_RemoveIdea(Id,Idea) {
		Id = Number(Id);
		dialog_ConfirmAlert(Idea,"Are you sure you want to delete this?",function(){
			xhr_PostJSON(
				"/api-theme.php",
				serialize({"action":"REMOVE","id":Id}),
				// On success //
				function(response,code) {
					console.log("REMOVE:",response);
					var el = document.getElementById('sg-item-'+response.id);
					if ( el ) {
						el.remove();
					}
					sg_UpdateCount(response.count,true);
				}
			);
		});
	}
	
	function sg_UpdateCount(count,effect) {
		var el = document.getElementById('sg-count');
		var Total = 3 - count;
		if ( Number(el.innerHTML) !== Total ) {
			el.innerHTML = Total;
			if ( effect === true ) {
				dom_RestartAnimation('sg-count','effect-accent');
			}
		}
	}
		
	function SubmitIdeaForm() {
		var elm = document.getElementById('input-idea');
		var Idea = elm.value.trim();
		
		if ( Idea === "" )
			return;

		elm.value = "";

		xhr_PostJSON(
			"/api-theme.php",
			serialize({"action":"ADD","idea":Idea}),
			// On success //
			function(response,code) {
				console.log("ADD:",response);
				
				sg_UpdateCount(response.count,true);
				
				// Success //
				if ( response.id > 0 ) {
					sg_AddIdea(response.id,response.idea,true);
				}
				// Failure //
				else if ( response.count === 3 ) {
					elm.value = Idea;	// Restore
					dialog_Alert("No Suggestions Left","");
				}
				else {
					dialog_Alert("Other Error",JSON.stringify(response));
				}
			}
		);

		elm.focus();
	}
	
	function dialog_ConfirmAlert(title,message,func /*,outside_close*/) {
		if ( dialog_IsActive() )
			return;
		
		dialog_SetAction(func);
		dom_SetText("dialog-title",title);
		dom_SetText("dialog-text",message);
		
		dom_ToggleClass("dialog-yes_no","hidden",false);
		dom_ToggleClass("dialog-ok_only","hidden",true);
		
		dom_SetClasses("dialog","red_dialog effect-zoomin");
		dom_SetClasses("dialog-back","effect-fadein");

		dom_SetFocus("dialog-no");
	}
	function dialog_Alert(title,message /*,outside_close*/) {
		if ( dialog_IsActive() )
			return;
		
		dom_SetText("dialog-title",title);
		dom_SetText("dialog-text",message);

		dom_ToggleClass("dialog-yes_no","hidden",true);
		dom_ToggleClass("dialog-ok_only","hidden",false);
		
		dom_SetClasses("dialog","blue_dialog effect-zoomin");
		dom_SetClasses("dialog-back","effect-fadein");
		
		dom_SetFocus("dialog-ok");
	}
	var _dialog_action;
	function dialog_SetAction(func) {
		_dialog_action = func;
	}
	function dialog_DoAction() {
		_dialog_action();		
		dialog_Close();
	}
	function dialog_IsActive() {
		return dom_HasClass("dialog-back","effect-fadein");
	}
	function dialog_Close() {
		dom_RemoveClass("dialog","effect-zoomin");
		dom_AddClass("dialog","effect-zoomout");
		dom_SetClasses("dialog-back","effect-fadeout");
	}
	
	window.onload = function() {
		// Dialog //
		var Focusable = document.getElementsByClassName("focusable");
		
		// NOTE: If you tab in from the title bar, the last element will be selected
		document.getElementById("dialog-focusfirst").addEventListener("focus",function(event){
			for ( var idx = Focusable.length-1; idx >= 0; idx-- ) {
				if ( Focusable[idx].offsetParent !== null ) {
					event.preventDefault();
					event.stopPropagation();
					Focusable[idx].focus()
					break;
				}
			}
		});
		document.getElementById("dialog-focuslast").addEventListener("focus",function(event){
			for ( var idx = 0; idx < Focusable.length; idx++ ) {
				if ( Focusable[idx].offsetParent !== null ) {
					event.preventDefault();
					event.stopPropagation();
					Focusable[idx].focus()
					break;
				}
			}
		});
		// For browsers that do otherwise //
		window.addEventListener("focus",function(event){
			for ( var idx = Focusable.length-1; idx >= 0; idx-- ) {
				if ( Focusable[idx].offsetParent !== null ) {
					event.preventDefault();
					event.stopPropagation();
					Focusable[idx].focus()
					break;
				}
			}
		});
		
		window.addEventListener("keydown",function(event){
			if ( dialog_IsActive() ) {
				if ( event.keyCode == 27 ) {
					// TODO: Confirm that we are in a mode where pushing ESC is allowed
					event.preventDefault();
					event.stopPropagation();
					dialog_Close();
				}
			}
		});

		
		
		<?php
		if ( $CONFIG['active'] && $cookie_id ) {
		?>
			xhr_PostJSON(
				"/api-theme.php",
				serialize({"action":"GET"}),
				// On success //
				function(response,code) {
					console.log("GET:",response);
					if ( response.hasOwnProperty('ideas') ) {
						response.ideas.forEach(function(response) {
							sg_AddIdea(response.id,response.theme);
						});
						
						sg_UpdateCount(response.count);
					}
					else {
						sg_UpdateCount("ERROR",true);
					}
				}
			);
		<?php
		}
	?>
	}
</script>
<div class="body">
	<div class="main">
		<?php
			if ( $CONFIG['active'] ) {
				ShowHeadline();
				if ( $cookie_id ) {
					ShowSubmitIdea();
					ShowLogout();
				}
				else {
					ShowLogin();
				}
			}
			else {
				ShowInactive();
			}
		?>
	</div>
	<?php
		if ( $CONFIG['active'] ) {
			if ( $cookie_id ) {
				echo "<div class='extra'>";
				ShowExtra();
				echo "</div>";
			}
		}
	?>
</div>
<?php template_GetFooter();