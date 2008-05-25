<?php

	function ElementUserSettingsPersonalAvatar() {
		global $user;
		
		Element( 'user/avatar' , $user , 150 , '' , '' );
		?><a href="" onclick="return false">Αλλαγή εικόνας</a><?php
	}
?>
