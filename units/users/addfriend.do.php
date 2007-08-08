<?php
	function UnitUsersAddfriend( tInteger $friendid ) {
		global $user;
		global $libs;
		
        $friendid = $friendid->Get();
        
		if ( $friendid != $user->Id() ) {
			$thisfriend = New User( $friendid );
			if ( $thisfriend->Exists() ) {
				if ( !$user->IsFriend( $friendid ) ) {
					$user->Addfriend( $friendid ); 
					ob_start();
					Element( 'user/display' , $user );
					$content = ob_get_clean();
					?>Friends.FriendAdded( <?php
					echo $user->Id();
					?> , <?php
					echo $friendid;
					?> , <?php
					echo w_json_encode( $content );
					?> , <?php
					echo w_json_encode( $user->Rank() );
					?> , <?php
					echo w_json_encode( $user->Hobbies() );
					?> );<?php
				}
			}
		}
	}
