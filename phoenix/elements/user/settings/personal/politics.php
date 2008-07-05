<?php

	function ElementUserSettingsPersonalPolitics( $selected , $gender ) {
		?><select><?php
			$politics = array( '-' , 'right' , 'left' , 'center' , 'radical right' , 'radical left' , 'center left', 'center right', 'nothing' );
			foreach ( $politics as $politic ) {
				?><option value="<?php
				echo $politic;
				?>"<?php
				if ( $selected == $politic ) {
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
