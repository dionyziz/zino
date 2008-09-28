<?php
	class ElementUserProfileSidebarSocialEducation extends Element {
		public function Render( $education ) {
			if ( $education!= '-' ) {
				?><li><strong>Μόρφωση</strong>
				<?php
				Element( 'user/trivial/education' , $education );
				?></li><?php
			}
		}
	}
?>
