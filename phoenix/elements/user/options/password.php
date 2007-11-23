<?php

	function ElementUserOptionsPassword( $match, $invalid, $newpassword ) {
		global $xc_settings;
	
		?><span class="headings" onclick="SetCat.activate_category( '1' );"><img id="setimg1" src="<?php
		echo $xc_settings[ 'staticimagesurl' ];
		?>icons/settings-collapsed.png" /> Αλλαγή κωδικού πρόσβασης
		
		<img src="<?php
		echo $xc_settings[ 'staticimagesurl' ];
		?>icons/settings-password.jpg" /></span><br /><br />
		
		<div id="cat1" class="password"><?php
			if ( $match ) {
				?><b>Οι δύο κωδικοί που πληκτρολόγισες δεν ταιριάζουν!</b><?php
			}
			else if ( $invalid ) {
				?><b>Ο παλιός σου κωδικός δεν είναι σωστός!</b><?php
			}
			
			?><br />
			<span style="padding-right:30px">Παλιός Κωδικός: </span>
			<input type="password" name="oldpassword" /><br />
			<span class="minitip">(πληκτρολόγησε τον παλιό σου κωδικό)</span>
			<br /><br /><?php
			
			if ( $newpassord ) {
				?><b>Δεν έχεις πληκτρολογίσει νεό κωδικό!</b><?php
			}
			
			?><br />
			<span style="padding-right:43px">Νέος Κωδικός: </span>
			<input type="password" name="newpassword" /><br />
			<span class="minitip">(πληκτρολόγησε τον νέο σου κωδικό)</span>
			<br /><br />
			<span>Νέος Κωδικός (ξανά): </span>
			<input type="password" name="newpassword2" /><br />
			<span class="minitip">(πληκτρολόγησε τον νέο σου κωδικό ξανά, για να σιγουρευτείς ότι είναι σωστός)</span>
			<br /><br />
		</div><?php
	}

?>