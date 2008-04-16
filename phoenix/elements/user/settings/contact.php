<?php
	function ElementUserSettingsContact() {
		global $user;
		global $rabbit_settings;
		
		?><div>
			<label>E-mail:</label>
			<div class="setting" id="email">
				<input type="text" name="email" class="small" value="<?php
				echo htmlspecialchars( $user->Email );
				?>" />
				<span>
					<img src="<?php
					echo $rabbit_settings[ "imagesurl" ];
					?>exclamation.png" /> Το email δεν είναι έγκυρο
				</span>
			</div>
		</div>
		<div>
			<label>MSN:</label>
			<div class="setting" id="msn">
				<input type="text" name="msn" class="small" value="<?php
				echo htmlspecialchars( $user->Profile->Msn );
				?>" />
				<span>
					<img src="<?php
					echo $rabbit_settings[ "imagesurl" ];
					?>exclamation.png" /> Το MSN δεν είναι έγκυρο
				</span>
			</div>
		</div>
		<div>
			<label>Gtalk:</label>
			<div class="setting" id="gtalk">
				<input type="text" name="gtalk" class="small" value="<?php
				echo htmlspecialchars( $user->Profile->Gtalk );
				?>" />
			</div>
		</div>
		<div>
			<label>Skype:</label>
			<div class="setting" id="skype">
				<input type="text" name="skype" class="small" value="<?php
				echo htmlspecialchars( $user->Profile->Skype );
				?>" />
			</div>
		</div>
		<div>
			<label>Yahoo:</label>
			<div class="setting" id="yahoo">
				<input type="text" name="yahoo" class="small" value="<?php
				echo htmlspecialchars( $user->Profile->Yim );
				?>" />
			</div>
		</div>
		<div>
			<label>Ιστοσελίδα:</label>
			<div class="setting" id="web">
				<input type="text" name="yahoo" class="small" value="<?php
				echo htmlspecialchars( $user->Profile->Homepage );
				?>" />
			</div>
		</div><?php
	}
?>