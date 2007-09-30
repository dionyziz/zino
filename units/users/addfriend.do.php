<?php
	function UnitUsersAddfriend( tInteger $friendid, tInteger $friendtype ) {
		global $user;
		global $libs;
		global $xc_settings;
		
		$libs->Load( 'relations' );
		
        $friendid = $friendid->Get();
        $friendtype = $friendtype->Get();
        $wasfriend = false;
        
        ?>alert( "Starting Checking.." );<?php
		if ( $friendid != $user->Id() ) {
			?>alert( "friendid != user->Id" );<?php
			$thisfriend = New User( $friendid );
			$rel = New Relation( $friendtype );
			if ( $thisfriend->Exists() && $rel->Exists() ) {
				?>alert( "thisfriend->exists && rel->exists" );<?php
				if ( $user->IsFriend( $friendid ) ) {
					?>alert( "user->IsFriend" );<?php
					$prev = $user->GetRelId( $friendid );
					$user->DeleteFriend( $friendid );
					$wasfriend = true;
		/*			?>g( 'frel_<?php
					echo $prev;
					?>' ).className = "frelation";<?php  */
				}
				$user->Addfriend( $friendid, $friendtype );
		/*		?>g( 'frel_<?php
				echo $friendtype;
				?>' ).className = "relselected";<?php	*/
				if( !$wasfriend ) {
					?>alert( "!wasfriend" );<?php
			//		?>g( 'frel_-1' ).className = "frelation";<?php
					ob_start();
					Element( 'user/display' , $user );
					$content = ob_get_clean();
				}
				?>g('friendadd').childNodes[1].firstChild.src = "<?php
				echo $xc_settings[ 'staticimagesurl' ];
                ?>icons/user_delete.png";
                g('friendadd').childNodes[1].onclick = function() {
                			Friends.AddFriend( <?php
                			echo $friendid;
                			?> , -1 );
                		};
				Friends.FriendAdded( <?php
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
