<?php	
	class ElementUserProfileSidebarSocialReligion extends Element {
		protected $mPersistent = array( 'religion', 'gender' );

		public function Render( $religion, $gender ) {
			if ( $religion != '-' ) {
				?><li><strong>Θρήσκευμα</strong> <?php
				Element( 'user/trivial/religion' , $religion , $gender );
				?></li><?php
			}
		}
	}
?>
