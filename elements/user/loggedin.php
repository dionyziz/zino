<?php
function ElementUserLoggedin() {
	global $user;
	
	Element( "user/display" , $user );
	?><br /><small><?php
		echo htmlspecialchars( $user->Subtitle() );
	?></small><?php
}
	