<?php
	function ElementUserSettingsContact() {
		global $user;
		
		?><div>
			<label>E-mail:</label>
			<div class="setting">
				<input type="text" name="email" class="small" value="<?php
				echo htmlspecialchars( $user->Email );
				?>" />
			</div>
		</div>
		<div>
			<label>MSN:</label>
			<div class="setting">
				<input type="text" name="msn" class="small" value="<?php
				echo htmlspecialchars( $user->Profile->Msn );
				?>" />
			</div>
		</div>
		<div>
			<label>Gtalk:</label>
			<div class="setting">
				<input type="text" name="gtalk" class="small" value="<?php
				echo htmlspecialchars( $user->Profile->Gtalk );
				?>" />
			</div>
		</div>
		<div>
			<label>Skype:</label>
			<div class="setting">
				<input type="text" name="skype" class="small" value="<?php
				echo htmlspecialchars( $user->Profile->Skype );
				?>" />
			</div>
		</div>
		<div>
			<label>Yahoo:</label>
			<div class="setting">
				<input type="text" name="yahoo" class="small" value="<?php
				echo htmlspecialchars( $user->Profile->Yim );
				?>" />
			</div>
		</div>
		<div>
			<label>Ιστοσελίδα:</label>
			<div class="setting">
				<input type="text" name="yahoo" class="small" value="<?php
				echo htmlspecialchars( $user->Profile->Homepage );
				?>" />
			</div>
		</div><?php
	}
?>