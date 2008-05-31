<?php
	
	function ElementUserSettingsPersonalFavquote() {
		global $user;

		?><input type="text" name="favquote" value="<?php
		echo htmlspecialchars( $user->Profile->Favquote );
		?>" /><?php
	}
?>
