<?php
	function UnitAlbumPhotoTagGetstuff( tCoalaPointer $callmeback ) {
		global $user;
		global $libs;
		
		$libs->Load( 'relation/relation' );
		
		if ( !$user->Exists() || !$user->HasPermission( PERMISSION_TAG_CREATE ) ) {
			return;
		}
		
		$relfinder = New FriendRelationFinder();
		$mutual = $relfinder->FindMutualByUser( $user );
		$jsarr = "Tag.friends = [ ";
		$jsarr2 = "Tag.genders = [ ";
		foreach( $mutual as $mutual_friend ) {
			$jsarr .= "'" . $mutual_friend[ 'user_name' ] . "', ";
			$jsarr2 .= "'" . $mutual_friend[ 'user_gender'] . "', ";
		}
		$jsarr .= "'" . $user->Name . "' ];";
		$jsarr2 .= "'" . $user->Gender . "' ];";
			
		echo $jsarr;
		echo $jsarr2;
		?>Tag.virgin=false;<?php
		echo $callmeback;
		?>( false, '', true );<?php
	}
?>