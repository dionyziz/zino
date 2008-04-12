<?php

	function ElementUserSettingsPersonalPlace() {
		global $user;
		
		?><select name="place">
			<option value="-1" >-</option><?php
			$finder = New PlaceFinder();
			$places = $finder->FindAll();
			foreach ( $places as $place ) {
				?><option value="<?php
				echo $place->Id;
				?>"><?php
				echo $place->Name;
				?></option><?php
			}
		?></select><?php
	}
?>
