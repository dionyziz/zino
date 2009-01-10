<?php
	function UnitAlbumPhotoTagGetstuff( tCoalaPointer $callmeback ) {
		global $user;
		global $libs;
		
		$libs->Load( 'relation/relation' );
		
		if ( !$user->Exists() || !$user->HasPermission( PERMISSION_TAG_CREATE ) ) {
			?>alert( "Trexo 1" );<?php
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
		$jsarr2 .= "'" . $user->Gender . "' ];Tag.virgin=false;";
			
		echo w_json_encode( $jsarr );
		echo w_json_encode( $jsarr2 );
		echo $callmeback;
		?>( false, '', true );<?php
	}
?>