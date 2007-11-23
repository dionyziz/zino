<?php

	function ElementUserOptionsContact() {
		global $xc_settings;
		global $rabbit_settings;
		global $user;
		
		?><span class="headings" onclick="SetCat.activate_category( '3' );"><img id="setimg3" src="<?php
		echo $xc_settings[ 'staticimagesurl' ];
		?>icons/settings-collapsed.png" /> Επικοινωνία
		<img src="<?php
		echo $xc_settings[ 'staticimagesurl' ];
		?>icons/settings-contacts.jpg"  /></span><br /><br />
		<div id="cat3" class="contacts">
			<span style="padding-right:102px">Ε-mail: </span><input type="text" value="<?php 
				echo $user->Email(); 
			?>" name="email" /><br />
			
			<span class="minitip">(το e-mail σου είναι χρήσιμο σε περίπτωση που ξεχάσεις τον κωδικό σου)</span><br /><br />
		</div><?php
	}

?>