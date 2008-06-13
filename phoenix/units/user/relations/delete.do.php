<?php
	
	function UnitUserRelationsDelete( tInteger $relationid , tInteger $theuserid ) {
		global $user;
		global $libs;
		
		$libs->Load( 'relation/relation' );
		$relation = New FriendRelation( $relationid->Get() );
		
		if ( $relation->Exists() ) {
			if ( $relation->Userid == $user->Id ) {
				$relation->Delete();
				?>$( 'div.sidebar div.basicinfo div.addfriend a' )
				.fadeIn( 400 )
				.click( function() {
					Profile.AddFriend( '<?php
					echo $theuserid->Get();
					?>' );
					return false;
				} );<?php
			}
		}
	}
?>
