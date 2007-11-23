<?php

	function ElementUserOptionsSlogan() {
		global $xc_settings;
		global $user;
		
		?><span class="headings" onclick="SetCat.activate_category( '4' );"><img id="setimg4" src="<?php
		echo $xc_settings[ 'staticimagesurl' ];
		?>icons/settings-collapsed.png" /> Υπογραφή &amp; Slogan
		
		<img src="<?php
		echo $xc_settings[ 'staticimagesurl' ];
		?>icons/settings-signatureslogan.jpg" /></span><br /><br />
		<div id="cat4" class="signatureslogan">
			<span class="sigslog" name="signature">Υπογραφή</span><br />
			<textarea rows="8" cols="60" style="padding-top:5px" name="signature"><?php 
				echo nl2br( htmlspecialchars( $user->Signature() ) );
			?></textarea><br />
			<span class="minitip">(αυτό το κείμενο θα εμφανίζεται κάτω από τα σχόλιά σου)</span><br /><br />
			<span class="sigslog">Slogan</span><br />
			<input type="text" size="50" name="slogan" value="<?php 
				echo htmlspecialchars( $user->Subtitle() ); 
			?>" style="padding-top:5px" /><br />
			<span class="minitip">(αυτό το κείμενο θα εμφανίζεται κάτω από το όνομά σου)</span>
			<br /><br />
		</div><?php
	}

?>