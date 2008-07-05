<?php
	
	function ElementUserSettingsPersonalFavquote() {
		global $user;

		?><input type="text" value="<?php
		echo htmlspecialchars( $user->Profile->Favquote );
		?>" /><?php
	}
?>
