<?php
	
	function UnitUserRelationsDelete( tInteger $relationid ) {
		global $user;
		
		$relation = New FriendRelation( $relationid->Get() );
		
		if ( $relation->Exists() ) {
			if ( $relation->Userid == $user->Id ) {
				$relation->Delete();
			}
		}
	}
?>
