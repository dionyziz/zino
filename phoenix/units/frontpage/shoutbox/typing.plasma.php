<?php
    function UnitFrontpageShoutboxTyping( User $user, $typing, $channel ) {
		global $libs;
		
		$libs->Load( 'chat/channel' );
		
        w_assert( is_bool( $typing ) );
		w_assert( is_int( $channel ) );
        if ( $typing ) {
            ?>Frontpage.Shoutbox.OnStartTyping(<?php
            echo w_json_encode( array(
                'gender' => $user->Gender,
                'name' => $user->Name
            ) );
			?>, <?php
			echo $channel;
			?> );<?php
        }
        else {
            ?>Frontpage.Shoutbox.OnStopTyping(<?php
            echo w_json_encode( array(
                'gender' => $user->Gender,
                'name' => $user->Name
            ) );
			?>, <?php
			echo $channel;
			?> );<?php
        }
		if ( $channel == 0 ) {
			return 0;
		}
		// private channel (non-main)
		// subchannels are in the form of $userid . "x" . $authtokenpart
		$userinfo = ChannelFinder::FindParticipantsByChannel( $channel );
		return $userinfo;
    }
?>
