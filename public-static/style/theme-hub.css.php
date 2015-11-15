<?php require_once __DIR__."/../../style.php"; ?>

<?php
const PAL_RED = [
	"#622",		// Deep Color //
	"#C44",		// Base Color //
	"#F88",		// Light Color //
	"#FFF",		// Brightest Color //
	"#D88",		// Mute Base //
	"#FDD",		// Mute Bright //
];


const BEZIER_NORMAL = 			"cubic-bezier(0.5,-1.0,0.1,2.0)";
const BEZIER_NO_DIP = 			"cubic-bezier(0.5, 0.0,0.1,2.0)";
const BEZIER_NO_POP = 			"cubic-bezier(0.5,-1.0,0.1,1.0)";
const BEZIER_NO_DIP_NO_POP =	"cubic-bezier(0.5, 0.0,0.1,1.0)";

const BEZIER_BIG_DIP = 			"cubic-bezier(0.5,-1.0,0.1,3.0)";
const BEZIER_NO_DIP_BIG_POP = 	"cubic-bezier(0.5, 0.0,0.1,3.0)";

?>


body {
	color:#444;
	background:#BBB;
}

/* Header */
.header { 
	text-align:center;
	padding:0.5em 0;
}

/* Invented Color */
.header .inv {
	color:#FFF;
}

.header .event {
}
.header .mode { 
	margin:0.3em 0;
}
.header .date {
	margin:0.3em 0;
}


/* Body */
body > .body {
	background:#DDD;
}

body > .body .main {
	padding:1.0em 2.0em;
	text-align:center;
}

body > .body .headline {
	margin-bottom:2.0em;
}
body > .body .action {
	margin-top:2.0em;
}

.extra {
	padding:1.0em;
	background:#EEE;
	box-shadow: 0 1px 4px rgba(0,0,0,0.3);
}
.extra .title {
	margin-bottom:0.5em;
}
.extra > div {
	margin:0 auto;
}


.action .title {
	margin:0.5em 0;
}
.action .form {
}
.action .form > div, .action .form > input, .action .form > button {
	display:inline;
	vertical-align:middle;
}
.action .footnote {
	margin-top:1.0em;
}

.single-input {
	border:0;
	padding:12px 20px;
	margin:0 0.5em;
	border-radius:40px;
	font-size:1em;
	box-shadow: 0 1px 4px rgba(0,0,0,0.2);
	background:#EEE;
	
	outline: none;
}
.single-input:focus {
	background:#FFF;
	color:#000;
}

.submit-button {
	background:#AAA;
	color:#DDD;
}

button {
	background:#AAA;
	color:#DDD;

	border:0;
	padding:10px 26px;
	cursor:pointer;

	margin:0.5em;
	border-radius:2.0em;
	font-weight:700;
    outline: none;

    transition: transform 0.2s <?=BEZIER_NO_DIP_BIG_POP?>;
}
button:hover {
	transform: scale(1.25);
}
button:focus {
	background:#666;
	color:#FFF;
}
button:active {
	background:#FFF;
	color:#666;
}

.submit-button:focus {
	background:#E44;
	color:#FFB;
}
.submit-button:active {
	background:#FFB;
	color:#E44;
}

body > .footer {
	padding:1.0em 0;
}

.sg-item {
}
.sg-item-text {
	text-overflow: ellipsis;
	overflow:hidden;
	white-space: nowrap;
}
.sg-item-x {
	position:relative;
	float:right;
	z-index:1;
	
	line-height:1.0em;
	margin-left:1.0em;
	padding:0 0.25em;
	cursor:pointer;
}
.sg-item-x:hover {
	color:#F00;
	text-shadow: 0 0px 6px rgba(255,192,0,0.5);
}
.sg-item-x:active {
	color:#FF0;
}


.alert {
	background:#E87;
	color:#FFD;
	text-align:center;
	padding:0.5em;
}

.alert a {
	font-weight:bold;
	color:#FF8;
	text-decoration:none;
}
.alert a:hover {
	color:#FFF;
	text-decoration:underline;
}

/* Can't Select This */
button, .sg-item-x {
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

#dialog-back {
	background:rgba(64,48,48,0.7);
	
	position:fixed;
	left:0;
	right:0;
	top:0;
	bottom:0;
	
	z-index:999;
	
	display:-webkit-flex;
	display:flex;
	justify-content:center;
	align-items:center;
}


.effect-fadein {
    -webkit-animation: fadein 0.3s;
       -moz-animation: fadein 0.3s;
        -ms-animation: fadein 0.3s;
            animation: fadein 0.3s;
}
@keyframes fadein {
    from { opacity: 0; }
    to   { opacity: 1; }
}
@-ms-keyframes fadein {
    from { opacity: 0; }
    to   { opacity: 1; }
}
@-moz-keyframes fadein {
    from { opacity: 0; }
    to   { opacity: 1; }
}
@-webkit-keyframes fadein {
    from { opacity: 0; }
    to   { opacity: 1; }
}

.effect-fadeout {
    -webkit-animation: fadeout 0.3s;
       -moz-animation: fadeout 0.3s;
        -ms-animation: fadeout 0.3s;
            animation: fadeout 0.3s;
	visibility:hidden;
}
@keyframes fadeout {
    from { opacity: 1; visibility:visible; }
    to   { opacity: 0; visibility:hidden; }
}
@-ms-keyframes fadeout {
    from { opacity: 1; visibility:visible; }
    to   { opacity: 0; visibility:hidden; }
}
@-moz-keyframes fadeout {
    from { opacity: 1; visibility:visible; }
    to   { opacity: 0; visibility:hidden; }
}
@-webkit-keyframes fadeout {
    from { opacity: 1; visibility:visible; }
    to   { opacity: 0; visibility:hidden; }
}


.effect-zoomin {
    -webkit-animation: zoomin 0.3s;
       -moz-animation: zoomin 0.3s;
        -ms-animation: zoomin 0.3s;
    		animation: zoomin 0.3s;

	-webkit-animation-timing-function: <?=BEZIER_NO_DIP?>;
	   -moz-animation-timing-function: <?=BEZIER_NO_DIP?>;
	    -ms-animation-timing-function: <?=BEZIER_NO_DIP?>;
			animation-timing-function: <?=BEZIER_NO_DIP?>;
}
@keyframes zoomin {
    from { transform: scale(0); }
    to   { transform: scale(1); }
}
@-ms-keyframes zoomin {
    from { transform: scale(0); }
    to   { transform: scale(1); }
}
@-moz-keyframes zoomin {
    from { transform: scale(0); }
    to   { transform: scale(1); }
}
@-webkit-keyframes zoomin {
    from { transform: scale(0); }
    to   { transform: scale(1); }
}

.effect-zoomout {
    -webkit-animation: zoomout 0.3s;
       -moz-animation: zoomout 0.3s;
        -ms-animation: zoomout 0.3s;
    		animation: zoomout 0.3s;

	-webkit-animation-timing-function: <?=BEZIER_NO_DIP?>;
	   -moz-animation-timing-function: <?=BEZIER_NO_DIP?>;
	    -ms-animation-timing-function: <?=BEZIER_NO_DIP?>;
			animation-timing-function: <?=BEZIER_NO_DIP?>;
}
@keyframes zoomout {
    from { transform: scale(1); }
    to   { transform: scale(0); visibility:hidden; }
}
@-ms-keyframes zoomout {
    from { transform: scale(1); }
    to   { transform: scale(0); visibility:hidden; }
}
@-moz-keyframes zoomout {
    from { transform: scale(1); }
    to   { transform: scale(0); visibility:hidden; }
}
@-webkit-keyframes zoomout {
    from { transform: scale(1); }
    to   { transform: scale(0); visibility:hidden; }
}


#dialog {
	text-align:center;
	/*pointer-events:none;*/
	
	overflow:hidden;
	max-width:20em;
	
	z-index:1000;
	
	border-radius:1.0em;
	background:<?=PAL_RED[1]?>;
	box-shadow:0 2px 6px rgba(0,0,0,0.3);
}

#dialog img {
	margin:0.5em;
}

#dialog .title {
	color:<?=PAL_RED[3]?>;
	font-weight:700;
	
	padding:0.5em 1.0em;
	text-overflow:ellipsis;
	overflow:hidden;
}

#dialog .body {
	color:<?=PAL_RED[0]?>;
	background:<?=PAL_RED[2]?>;
	padding:0.5em 2.0em;
}
#dialog .buttons {
	color:<?=PAL_RED[0]?>;
	padding:0.5em 2.0em;
}

#dialog button {
	background:<?=PAL_RED[4]?>;
	color:<?=PAL_RED[5]?>;
}
#dialog button:focus {
	background:<?=PAL_RED[2]?>;
	color:<?=PAL_RED[3]?>;
}
#dialog button:active {
	background:<?=PAL_RED[3]?>;
	color:<?=PAL_RED[2]?>;
}



/* Normal and HiRes */
body > .body {
	position:relative;
	
	overflow:hidden;
	text-align:center;
	white-space:nowrap;
	
	box-shadow:0 0 4px rgba(0,0,0,0.3)
}
body > .body > div {
	display:inline-block;
	vertical-align:top;
}
body > .body .main {
}
body > .body .extra {
	min-width:12em;
	max-width:30%;
	text-align:left;
	margin:1em 0;
}

/* Tablet and Mobile */
@media <?= MOBILE_AND_TABLET_QUERY ?> {
	body > .body > div {
		display:block;
	}
	body > .body .main {
	}
	body > .body .extra {
		max-width:none;
		font-size:1.4em;
		margin:0;
	}
}