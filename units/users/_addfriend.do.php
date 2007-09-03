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
					$prev = $user->GetRelId( $friendid );
					$user->DeleteFriend( $friendid );
					$wasfriend = true;
					?>g( 'frel_<?php
					echo $prev;
					?>' ).className = "frelation";<?php
				}
				$user->Addfriend( $friendid, $friendtype );
				?>g( 'frel_<?php
				echo $friendtype;
				?>' ).className = "relselected";<?php
				if( !$wasfriend ) {
					?>g( 'frel_-1' ).className = "frelation";<?php
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
