<?php

	function ElementUserSettingsCharacteristicsHaircolor() {
		global $user;
		
		?><select><?php
			$hairs = array( '-' , 'black' , 'brown' , 'red' , 'blond' , 'highlights' , 'grey' , 'skinhead' );
			foreach ( $hairs as $hair ) {
				?><option value="<?php
				echo $hair;
				?>"<?php
				if ( $user->Profile->Haircolor == $hair ) {
					?> selected="selected"<?php
				}
				?>><?php
				Element( 'user/trivial/haircolor' , $hair );
				?></option><?php
			}
		?></select><?php
	}
?>
