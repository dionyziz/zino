<?php
	
	function ElementUserSettingsPersonalSlogan() {
		global $user;
		
		?><input type="text" value="<?php
		echo htmlspecialchars( $user->Profile->Slogan );
		?>" /><?php
	}
?>
