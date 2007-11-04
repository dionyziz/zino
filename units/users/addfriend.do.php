<?php
	function UnitUsersAddfriend( tInteger $friendid, tInteger $friendtype ) {
		global $user;
		global $libs;
		global $xc_settings;
		
		$libs->Load( 'relations' );
		
        $friendid = $friendid->Get();
        $friendtype = $friendtype->Get();
        $wasfriend = false;
        
		if ( $friendid == $user->Id() ) {
			return;
		}
		
		$thisfriend = New User( $friendid );
		$rel = New Relation( $friendtype );
		
		if ( !$thisfriend->Exists() || !$rel->Exists() ) {
			return;
		}
		
		if ( $user->IsFriend( $friendid ) ) {
			$prev = $user->GetRelId( $friendid );
			$user->DeleteFriend( $friendid );
			$wasfriend = true;
			?>g( 'frel_<?php
			echo $prev;
			?>' ).className = "frelation";<?php
		}
		$user->Addfriend( $friendid, $friendtype, $wasfriend );
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
		echo ($wasfriend)?"''":w_json_encode( $user->Hobbies() );
		?> , <?php
		echo $friendtype;
		?> );<?php
	}
