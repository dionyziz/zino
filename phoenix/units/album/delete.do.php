<?php
	
	function UnitAlbumDelete( tInteger $albumid ) {
		global  $user;
		global $rabbit_settings;
		
		$albumid = $albumid->Get();
		$album = new Album( $albumid );
		if ( $album->User->Id == $user->Id ) {
			?>alert( 'Deleting album <?php echo $album->Id; ?>' );<?php
			//$album->Delete();
			?>document.location.href='<?php
			echo $rabbit_settings[ "webaddress" ] . "/?p=albums&username=" . $album->User->Name;
			?>';<?php
		}
	}
?>
