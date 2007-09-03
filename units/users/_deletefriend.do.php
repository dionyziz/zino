<?php
	function UnitUsersDeletefriend( tInteger $friendid ) {
		global $user;
		
        $friendid = $friendid->Get();
        
        $type = $user->GetRelId( $friendid );
		$user->DeleteFriend( $friendid );
		
		?>g( 'frel_<?php
		echo $type;
		?>' ).className = "frelation";
		g( 'frel_-1' ).className = "relselected";
		Friends.FriendDeleted( <?php
		echo $user->Id();
		?> );<?php
	}
?>
