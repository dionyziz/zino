<?php

	function ElementUserSettingsPersonalReligion( $gender) {
		global $user;
		
		?><select><?php
			$religions = array( '-' , 'christian' , 'muslim' , 'atheist' , 'agnostic' , 'nothing' );
			foreach ( $religions as $religion ) {
				?><option value="<?php
				echo $religion;
				?>"<?php
				if ( $user->Profile->Religion == $religion ) {
					?> selected="selected"<?php
				}
				?>><?php
				Element( 'user/trivial/religion' , $religion , $gender );
				?></option><?php
			}
			?>
		</select><?php
	}
?>
