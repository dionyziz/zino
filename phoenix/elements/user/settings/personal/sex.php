<?php

	function ElementUserSettingsPersonalSex( $gender ) {
		global $user;
		
		?><select id="sexualorientation"><?php
		$sexs = array( '-' , 'straight' , 'gay' , 'bi' );
		foreach ( $sexs as $sex ) {
			?><option value="<?php
			echo $sex;
			?>"<?php
			if ( $user->Profile->Sexualorientation == $sex ) {
				?> selected="selected"<?php
			}
			?>><?php
			Element( 'user/trivial/sex' , $sex , $gender );
			?></option><?php
		}
		?></select><?php
	}
?>
