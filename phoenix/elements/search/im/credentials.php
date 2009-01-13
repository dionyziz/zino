<?php    
	class ElementSearchImCredentials extends Element {
        public function Render( tString $im ) {
			global $rabbit_settings;
			global $user;
			
			$im = $im->Get();
			?><div id="im">
				<h2>Βρες τους φίλους σου στο <?php
				echo $rabbit_settings[ 'applicationname' ];
				?></h2>
				<div class="">
					<input type="text" />
					<select><?php
					if ( $im == "msn" ) {
						?><option value="hotmail.com" selected="selected">hotmail.com</option>
						<option value="windowslive.com">windowslive.com</option>
						<option value="msn.com">msn.com</option>
						<option value="msn.com">live.gr</option>
						<option value="msn.com">live.com</option><?php
					}
					else if ( $im == "gmail" ) {
						?><option value="gmail.com">gmail.com</option>
						<option value="googlemail.com">googlemail.com</option><?php
					}
					else if ( $im == "yahoo" ) {
						?><option value="yahoo.gr">yahoo.gr</option>
						<option value="yahoo.com">yahoo.com</option><?php
					}
				?></select>
				</div>
			</div><?php
		}
	}
?>