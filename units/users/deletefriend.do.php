<?php
	function UnitUsersDeletefriend( tInteger $friendid ) {
		global $user;
		
        $friendid = $friendid->Get();
        
		$user->DeleteFriend( $friendid );
		
		?>Friends.ProfileDeleteFriendCallback( <?php
		echo $friendid;
		?>,<?php
		echo $user->Id();
		?> );<?php
	}
?>
