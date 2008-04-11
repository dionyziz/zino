<?php
	
	function ElementUserSettingsPersonalEducation() {
		global $user;
		
		?><select name="education" id="education"><?php
			$educations = array( '-' , 'elementary' , 'gymnasium' , 'TEE' , 'lyceum' , 'TEI' , 'university' );
			foreach ( $educations as $education ) {
				?><option value="<?php
				echo $education;
				?>"<?php
				if ( $user->Profile->Education == $education ) {
					?> selected="selected"<?php
				}
				?>><?php
				Element( 'user/trivial/education' , $education );
				?></option><?php
			}
		?></select><?php
	}
?>
