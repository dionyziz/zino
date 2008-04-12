<?php

	function ElementUserSettingsPersonalDob() {
		global $user;
		global $water;
		
		$water->Trace( 'dob day: ' . $user->Profile->BirthDay );
		$water->Trace( 'dob month: ' . $user->Profile->BirthMonth );
		$water->Trace( 'dob year: ' . $user->Profile->BirthYear );
		?><select name="day" class="small">
			<option value="-1"<?php
			if ( !$user->Age ) {
				?> selected="selected"<?php
			}
			?>>-</option><?php
			for ( $i = 1; $i <= 31; ++$i ) {
				?><option value="<?php
				if ( $i <= 9 ) {
					?>0<?php
				}
				echo $i;
				?>"<?php
				if ( $user->Profile->BirthDay == $i ) {
					?> selected="selected"<?php
				}
				?>><?php
				if ( $i <= 9 ) {
					?>0<?php
				}
				echo $i;
				?></option><?php
			}
		?></select>
		<select name="month" class="small">
			<option value="-1"<?php
			if ( !$user->Age ) {
				?> selected="selected"<?php
			}
			?>><?php
			Element( 'user/trivial/month' , '-' );
			?></option><?php
			for ( $i = 1; $i <= 12; ++$i ) {
				?><option value="<?php
				if ( $i <= 9 ) {
					$i = '0' . $i;
				}
				echo $i;
				?>"<?php
				if ( $user->Profile->BirthMonth == $i ) {
					?> selected="selected"<?php
				}
				?>><?php
				Element( 'user/trivial/month' , $i );
				?></option><?php
			}
		?></select>
		<select name="month" class="small">
			<option value="-"<?php
			if ( !$user->Age ) {
				?> selected="selected"<?php
			}
			?>>-</option><?php
			for ( $i = 2001; $i >= 1950; --$i ) {
				?><option value="<?php
				echo $i;
				?>"<?php
				if ( $user->Profile->BirthYear == $i ) {
					?> selected="selected"<?php
				}
				?>><?php
				echo $i;
				?></option><?php
			}
		?></select><?php
	}
?>
