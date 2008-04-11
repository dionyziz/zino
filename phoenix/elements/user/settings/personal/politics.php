<?php

	function ElementUserSettingsPersonalPolitics( $gender ) {
		global $user;
		
		?><select id="politics"><?php
			$politics = array( '-' , 'right' , 'left' , 'center' , 'radical right' , 'radical left' , 'nothing' );
			foreach ( $politics as $politic ) {
				?><option value="<?php
				echo $politic;
				?>"<?php
				if ( $user->Profile->Politics == $politic ) {
					?> selected="selected"<?php
				}
				?>><?php
				Element( 'user/trivial/politics' , $politic , $gender );
				?></option><?php
			}
			?>
		</select><?php
	}
?>
