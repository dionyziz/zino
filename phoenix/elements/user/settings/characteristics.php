<?php

	function ElementUserSettingsCharacteristics() {
		global $user;
		global $water;
		
		$water->Trace( 'eyecolor:' . $user->Profile->Eyecolor );
		$water->Trace( 'haircolor :' . $user->Profile->Haircolor );
		$water->Trace( 'smoker :' . $user->Profile->Smoker );
		$water->Trace( 'drinker :' . $user->Profile->Drinker );
		$water->Trace( 'weight :' . $user->Profile->Weight );
		?><div>
			<label>Χρώμα μαλλιών</label>
			<div class="setting">
				<select name="haircolor"><?php
					$hairs = array( '-' , 'black' , 'brown' , 'red' , 'blond' , 'highlights' , 'grey' , 'skinhead' );
					foreach ( $hairs as $hair ) {
						?><option value="<?php
						echo $hair;
						?>"<?php
						if ( $user->Profile->Haircolor == $hair ) {
							?> selected="selected"<?php
						}
						?>><?php
						Element( 'user/trivial/haircolor' , $hair );
						?></option><?php
					}
				?></select>
			</div>
		</div>
		<div>
			<label>Χρώμα ματιών</label>
			<div class="setting">
				<select name="eyecolor"><?php
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
			</div>
		</div>
		<div>
			<label>Ύψος</label>
			<div class="setting">
				<select name="height">
					<option value="-1"<?php
					if ( $user->Profile->Height == -1 ) {
						?> selected="selected"<?php
					}
					?>><?php
					Element( 'user/trivial/height' , -1 );
					?></option>
					<option value="-2"<?php
					if ( $user->Profile->Height == -2 ) {
						?> selected="selected"<?php
					}
					?>><?php
					Element( 'user/trivial/height' , -2 );
					?></option><?php
					for ( $i = 120; $i <= 220; ++$i ) {
						?><option value="<?php
						echo $i;
						?>"<?php
						if ( $user->Profile->Height == $i ) {
							?> selected="selected"<?php
						}
						?>><?php
						Element( 'user/trivial/height' , $i );
						?></option><?php
					}
					?><option value="-3"<?php
					if ( $user->Profile->Height == -3 ) {
						?> selected="selected"<?php
					}
					?>><?php
					Element( 'user/trivial/height' , -3  );
					?></option>
				</select>
			</div>
		</div>
		<div>
			<label>Βάρος</label>
			<div class="setting">
				<select name="weight">
					<option value="-1"<?php					
					if ( $user->Profile->Weight == -1 ) {
						?> selected="selected"<?php
					}
					?>><?php
					Element( 'user/trivial/weight' , -1 );
					?></option>
					<option value="-2"<?php
					if ( $user->Profile->Weight == -2 ) {
						?> selected="selected"<?php
					}
					?>><?php
					Element( 'user/trivial/weight' , -2 );
					?></option><?php
					for ( $i = 30; $i <= 150; ++$i ) {
						?><option value="<?php
						echo $i;
						?>"<?php
						if ( $user->Profile->Weight == $i ) {
							?> selected="selected"<?php
						}
						?>><?php
						Element( 'user/trivial/weight' , $i );
						?></option><?php
					}
					?><option value="-3"<?php
					if ( $user->Profile->Weight == -3 ) {
						?> selected="selected"<?php
					}
					?>><?php
					Element( 'user/trivial/weight' , -3 );
					?></option><?php
				?></select>
			</div>
		</div>
		<div>
			<label>Καπνίζεις;</label>
			<div class="setting">
				<select name="smoker"><?php
					$yesno = array( '-' , 'yes' , 'no' , 'socially' );
					foreach ( $yesno as $answer ) {
						?><option value="<?php
						echo $answer;
						?>"<?php
						if ( $user->Profile->Smoker == $answer ) {
							?> selected="selected"<?php
						}
						?>><?php
						Element( 'user/trivial/yesno' , $answer );
						?></option><?php
					}
				?></select>
			</div>
		</div>
		<div>
			<label>Πίνεις;</label>
			<div class="setting">
				<select name="drinker"><?php
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
				?></select>
			</div>
		</div><?php	
		}
?>
