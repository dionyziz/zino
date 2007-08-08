<?php
	function UnitUsersAddfriend( tInteger $friendid, tInteger $friendtype ) {
		global $user;
		global $libs;
		
		$libs->Load( 'relations' );
		
        $friendid = $friendid->Get();
        $friendtype = $friendtype->Get();
        $wasfriend = false;
        
		if ( $friendid != $user->Id() ) {
			$thisfriend = New User( $friendid );
			$rel = New Relation( $friendtype );
			if ( $thisfriend->Exists() && $rel->Exists() ) {
				if ( $user->IsFriend( $friendid ) ) {
					$user->DeleteFriend( $friendid );
					$wasfriend = true;
				}
				$user->Addfriend( $friendid, $friendtype );
				if( !$wasfriend ) {
					ob_start();
					Element( 'user/display' , $user );
					$content = ob_get_clean();
				}
				?>Friends.FriendAdded( <?php
				echo $user->Id();
				?> , <?php
				echo $friendid;
				?> , <?php
				echo ($wasfriend)?"''":w_json_encode( $content );
				?> , <?php
				echo ($wasfriend)?"''":w_json_encode( $user->Rank() );
				?> , <?php
				echo ($wasfriend)?"''":w_json_encode( $user->Hobbies() );
				?> , <?php
				echo $friendtype;
				?> );<?php
			}
		}
	}
