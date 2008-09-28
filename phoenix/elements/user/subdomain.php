<?php
	
	class ElementUserSubdomain extends Element {
		public function Render( $theuser ) {
			if ( !is_object( $theuser ) ) {
				return;
			}
			if ( !( $theuser instanceof User ) ) {
				return;
			}
			if ( $theuser->Subdomain != '' ) {
				echo htmlspecialchars( $theuser->Subdomain );
			}
		}
	}
?>
