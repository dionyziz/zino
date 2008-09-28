<?php
    $loggedin = true;
	include "banner.php";
	include "home.php";
	include "debug.php";
?>
<script type="text/javascript"><![CDATA[
var k = 1;

function animateflag() {
	k += 0.1;
	m = 0.5 + 0.5 * Math.abs(Math.sin(k));
	document.getElementById("water_flag").style.opacity = m;
	if (k < 9) {
		setTimeout("animateflag()", 30);
	}
}
animateflag();

]]></script>
