<?php 
	class ElementAlbumManager extends Element {
		public function Render () { 
			
			global $user;
			
			if ( !$user->Exists() ) {
				return;
			}
			
			?>test<?php
			
		}
	}
?>