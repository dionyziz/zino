<?php
	
	function UnitUserRelationsNew( tInteger $userid ) {
		global $libs;
		global $user;
		
		$libs->Load( 'relation/relation' );
		
		if ( $user->Exists() ) {
			$theuser = New User( $userid->Get() );
			if ( $theuser->Exists() ) {
				$relation = New FriendRelation();
				$relation->Userid = $user->Id;
				$relation->Friendid = $theuser->Id;
				$relation->Typeid = 3;
				$relation->Save();
			}
		}
	}
?>
