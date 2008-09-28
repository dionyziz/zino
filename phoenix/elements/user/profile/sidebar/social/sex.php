<?php
	class ElementUserProfileSidebarSocialSex extends Element {
		protected $mPersistent = array( 'sexualorientation', 'gender' );

		public function Render( $sexualorientation, $gender ) {
			if ( $sexualorientation != '-' ) {
				?><li><strong>Σεξουαλικές προτιμήσεις</strong>
				<?php
				Element( 'user/trivial/sex' , $sexualorientation , $gender );
				?></li><?php
			}
		}
	}
?>
