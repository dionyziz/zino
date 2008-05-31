<?php
	
	function ElementUserSettingsPersonalFavquote( $theuser ) {
		global $user;
		
		?><input type="text"><?php
		echo htmlspecialchars( $user->Profile->Favquote );
		?></input><?php
	}
?>
