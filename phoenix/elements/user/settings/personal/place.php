<?php

	function ElementUserSettingsPersonalPlace() {
		global $user;
		
		?><select name="place">
			<option value="-1"<?php
			if ( $user->Profile->Location->Id == 0 ) {
				?> selected="selected"<?php
			}
			?>>-</option><?php
			foreach( $places as $place ) {
				?><option value="<?php
				echo $place->Id;
				?>"<?php
				if ( $user->Profile->Location->Id == $place->Id ) {
					?> selected="selected"<?php
				}
				?>><?php
				echo $place->Name;
				?></option><?php
			}
		?></select><?php
	}
?>
