<?php

	function ElementUserSettingsPersonalAboutme() {
		global $user;
		
		?><textarea id="aboutme"><?php
		echo htmlspecialchars( $user->Profile->Aboutme );
		?></textarea><?php
	}
?>
