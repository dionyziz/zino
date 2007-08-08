<?php

	function ElementUserOptionsPersonal( ) {
		global $xc_settings;
		global $user;
		global $libs;
		
		$libs->Load( 'place' );
		
		$dayofbirthday = $user->DateOfBirthDay();
		$monthofbirthday = $user->DateOfBirthMonth();
		$yearofbirthday = $user->DateOfBirthYear();
		
		$months = array( 
                1 => "Ιανουάριος",
                2 => "Φεβρουάριος",
                3 => "Μάρτιος",
                4 => "Απρίλιος",
                5 => "Μάιος",
                6 => "Ιούνιος",
                7 => "Ιούλιος",
                8 => "Αύγουστος",
                9 => "Σεπτέμβριος",
                10 => "Οκτώβριος",
                11 => "Νοέμβριος",
                12 => "Δεκέμβριος"
            );
		
		?><span class="headings" onclick="SetCat.activate_category( '0' );"><img id="setimg0" src="<?php
		echo $xc_settings[ 'staticimagesurl' ];
		?>icons/settings-collapsed.png" /> Προσωπικές πληροφορίες
		
		<img src="<?php
		echo $xc_settings[ 'staticimagesurl' ];
		?>icons/settings-personalinfo.jpg" /></span><br /><br />
		<div id="cat0" class="userinfo">
			Φύλο: 
			<select id="settings_gender" name="gender"><?php
				$genders = array (
					'-' => '-',
					'male' => 'άνδρας',
					'female' => 'γυναίκα'
				);
				
				foreach( $genders as $key => $gender ) {
					?><option value="<?php 
					echo $key; 
					?>"<?php 
					if ( $user->Gender() == $key ) { 
						?> selected="selected"<?php 
					} 
					?>><?php 
					echo $gender;
					?></option><?php 
				}
				
			?></select><br /><br />
			Τοποθεσία: 
			<select id="settings_area" name="place"><?php
				$plc = $user->Place();
				$places = AllPlaces();	
				?><option value="0" <?php
					if ( $plc == 0 ) { 
						?>selected="selected"<?php
					} 
					?>>(δεν έχεις επιλέξει)
					
				</option><?php
				foreach( $places as $place ) {
					?><option value="<?php
						echo $place->Id; ?>
						" <?php
						if ( $place->Id == $plc ) { ?>
							selected="selected"<?php
						} ?>
						> <?php
						echo $place->Name;
						?>
					</option><?php
				} 
			?></select>
			<br /><br />
			Ημερομηνία γέννησης:<br /><br />
			Ημέρα: <select id="dob_day" name="dob_day"><?php
				for ( $i = 1; $i<=31; $i++ ) { ?>
					<option value="<?php
						echo $i; ?>" <?php
						if ( $dayofbirthday == $i ) { ?>
							selected="selected"<?php
						} ?>><?php
						echo $i; ?>
					</option><?php
				} ?>
			</select>
			Μήνας: <select id="dob_month" name="dob_month"><?php				
					for( $i = 1; $i < 13; ++$i ) { ?>
						<option value="<?php 
						if ( $i < 10 ) {
							echo "0" . $i;
						}
						else {
							echo $i;
						} 
						?>" <?php 
						if ( $monthofbirthday == $i ) { ?> 
							selected="selected"<?php	
						} 
						?>> <?php 
						echo $months[ $i ]; ?>
						</option><?php
					}
					?></select>
			Έτος: <select id="dob_year" name="dob_year"><?php
				$thisyear = date( 'Y' );
				for ( $i = $thisyear - 5; $i>=1940; --$i ) { ?>
					<option value="<?php 
						echo $i; ?>" <?php 
						if( $yearofbirthday == $i ) { ?> 
							selected="selected" <?php 
						} ?>
						><?php 
						echo $i; 
						?>
					</option>
					<?php
				} 
				?>
			</select><br /><br />
			Ενδιαφέροντα:<br />
			<textarea rows="10" cols="60" name="hobbies"><?php 
			echo htmlspecialchars( trim( $user->Hobbies() ) ); 
			?></textarea>
			<br /><br />
		</div><?php
	}

?>