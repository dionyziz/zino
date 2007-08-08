<?php
	function UnitUsersDeletefriend( tInteger $friendid ) {
		global $user;
		
        $friendid = $friendid->Get();
        
		$user->DeleteFriend( $friendid );
		
		?>Friends.FriendDeleted( <?php
		echo $user->Id();
		?> );<?php
	}
?>
