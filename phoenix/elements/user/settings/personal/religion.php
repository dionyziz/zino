<?php

	function ElementUserSettingsPersonalReligion() {
		global $user;
		
		?><select id="religion"><?php
			$religions = array( '-' , 'christian' , 'muslim' , 'atheist' , 'agnostic' , 'nothing' );
			foreach ( $religions as $religion ) {
				?><option value="<?php
				echo $religion;
				?>"<?php
				if ( $user->Profile->Religion == $religion ) {
					?> selected="selected"<?php
				}
				?>><?php
				Element( 'user/trivial/religion' , $religion , $user->Gender );
				?></option><?php
			}
			?>
		</select><?php
	}
?>
