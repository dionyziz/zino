<?php
	
	function UnitAlbumDelete( tInteger $albumid ) {
		global $user;
		global $rabbit_settings;
		
		$albumid = $albumid->Get();
		$album = new Album( $albumid );
		if ( $album->User->Id == $user->Id ) {
			if ( $album->Id != $user->Egoalbumid ) {
				$useralbum = $album->User->Name;
				$album->Delete();
				?>window.location.href = '<?php
				echo $rabbit_settings[ 'webaddress' ];
				?>?p=albums&username=<?php
				echo $album->User->Name;
				?>';<?php
			}
		}
	}
?>
