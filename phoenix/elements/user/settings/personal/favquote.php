<?php
	
	function ElementUserSettingsPersonalFavquote() {
		global $user;
		global $water;
		
		$water->Trace( "favquote is: " . $user->Profile->Favquote );
		?><input type="text"><?php
		echo htmlspecialchars( $user->Profile->Favquote );
		?></input><?php
	}
?>
