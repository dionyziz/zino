<?php 

	class ElementUserProfileSidebarLook extends Element {
		protected $mPersistent = array( 'height', 'weight', 'gender' );
		
		public function Render( $height, $weight, $gender ) {
			$showgender = $gender != '-';
			$showweight = $weight > -3;
			$showheight = $height > -3;
			?><ul><?php
				if ( $showgender ) {
					?><li><?php
					Element( 'user/trivial/gender', $gender );
					?></li><?php
				}
				if ( ( $showgender && $showweight ) || ( $showgender && !$showweight && $showheight ) ) {
					?><li class="dot">·</li><?php
				}
				if ( $showheight ) {
					?><li><?php
					Element( 'user/trivial/height' , $height );
					?></li><?php
				}
				if ( $showweight && $showheight ) {
					?><li class="dot">·</li><?php
				}
				if ( $showweight ) {
					?><li><?php
					Element( 'user/trivial/weight' , $weight );
					?></li><?php
				}
			?></ul><?php
		}
	}
?>
