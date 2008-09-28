<?php
	function UnitShoutEdit( tInteger $id, tString $shouttext, tCoalaPointer $callback ) {
		global $user;
		global $libs;
        
        $id = $id->Get();
        $shouttext = $shouttext->Get();
        
		$libs->Load( 'shoutbox' );
		if ( $user->IsModerator() ) {
			if( empty( $id ) && $id != 0 ) {
			$id = false;
			}
			$new = preg_replace('/\r+/', '', $shouttext);
			if( strlen( $new ) > 300 ) {
				?>alert( 'Too many characters in the shout::<?php
				echo strlen( $new );
				?>' );<?php
			}

			if ( $id ) {
				if ( !$user->CanModifyStories() ) {
					?>alert( 'What\'s wrong with you!???' );<?php
				}
				$shout = New Shout( $id );
				$shout->Update( $shouttext );
			}
			else {
				MakeShout( $shouttext );
			}

            echo $callback;
            ?>( <?php
            echo $id;
            ?>, <?php
            echo w_json_encode( $shouttext );
            ?>, <?php
            $temp = mformatcomments( array( $shouttext ) );
            $temp = nl2br( $temp[0] );
            echo w_json_encode( $temp );
            ?> );<?php

		}
    }
?>
