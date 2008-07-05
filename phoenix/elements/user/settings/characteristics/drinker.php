<?php
	
	function ElementUserSettingsCharacteristicsDrinker() {
		global $user;
		
		?><select><?php
			$yesno = array( '-' , 'yes' , 'no' , 'socially' );
			foreach ( $yesno as $answer ) {
				?><option value="<?php
				echo $answer;
				?>"<?php
				if ( $user->Profile->Drinker == $answer ) {
					?> selected="selected"<?php
				}
				?>><?php
				Element( 'user/trivial/yesno' , $answer );
				?></option><?php
			}
		?></select><?php
	}
?>
