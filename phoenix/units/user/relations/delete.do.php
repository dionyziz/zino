<?php
	
	function UnitUserRelationDelete( tInteger $relationid , tInteger $theuserid ) {
		global $user;
		
		$relation = New FriendRelation( $relationid->Get() );
		
		if ( $relation->Exists() ) {
			if ( $relation->Userid == $user->Id ) {
				$relation->Delete();
				?>$( 'div.sidebar div.basicinfo div.addfriend a' ).click( function() {
					Profile.AddFriend( '<?php
					echo $theuserid->Get();
					?>' );
					return false;
				} );<?php
			}
		}
	}
?>
