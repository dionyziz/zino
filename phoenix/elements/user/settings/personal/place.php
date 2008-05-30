<?php

	function ElementUserSettingsPersonalPlace() {
		global $user;
		
		$finder = New PlaceFinder();
		$places = $finder->FindAll();
		?><select name="place">
			<option value="-1"<?php
			if ( $user->Profile->Placeid == 0 ) {
				?> selected="selected"<?php
			}
			?>>-</option><?php
			foreach( $places as $place ) {
				?><option value="<?php
				echo $place->Id;
				?>"<?php
				if ( $user->Profile->Placeid == $place->Id ) {
					?> selected="selected"<?php
				}
				?>><?php
				echo htmlspecialchars( $place->Name );
				?></option><?php
			}
		?></select><?php
	}
?>
