<?php
	
	class ElementUserSettingsPersonalSlogan extends Element {
		public function Render() {
			global $user;
			
			?><input type="text" value="<?php
			echo htmlspecialchars( $user->Profile->Slogan );
			?>" /><?php
		}
	}
?>
