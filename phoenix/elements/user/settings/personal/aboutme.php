<?php

	function ElementUserSettingsPersonalAboutme() {
		global $user;
		
		?><textarea><?php
		echo htmlspecialchars( $user->Profile->Aboutme );
		?></textarea><?php
	}
?>
