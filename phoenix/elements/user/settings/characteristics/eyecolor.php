<?php

	function ElementUserSettingsCharacteristicsEyecolor() {
		global $user;
		
		?><select name="eyecolor"><?php
			$eyes = array( '-' , 'black' , 'brown' , 'green' , 'blue' , 'gray' );
			foreach ( $eyes as $eye ) {
				?><option value="<?php
				echo $eye;
				?>"<?php
				if ( $user->Profile->Eyecolor == $eye ) {
					?> selected="selected"<?php
				}
				?>><?php
				Element( 'user/trivial/eyecolor' , $eye );
				?></option><?php
			}
		?></select>
		<?php
	}
?>
