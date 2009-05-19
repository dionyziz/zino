<?php 
	class ElementAlbumManager extends Element {
		public function Render () { 
			
			global $user;
			
			if ( !$user->Exists() ) {
				die( "Πρέπει να είσαι συνδεδεμένος για να χρησιμοποιήσεις αυτήν την λειτουργία" );
			}
			
			?>test<?php
			
		}
	}
?>