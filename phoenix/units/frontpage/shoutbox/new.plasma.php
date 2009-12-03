<?php
    function UnitFrontpageShoutboxNew( Shout $shout ) {
		global $libs;
		
		$libs->Load( 'chat/channel' );
		
		$channel = $shout->Channelid;
		w_assert( is_int( $channel ) );
		
        ?>Frontpage.Shoutbox.OnMessageArrival( <?php
        echo $shout->Id;
        ?>, <?php
        echo w_json_encode( $shout->Text );
        ?>, <?php
        echo w_json_encode( array(
            'id' => $shout->Userid,
            'name' => $shout->User->Name,
            'avatar' => $shout->User->Avatarid,
            'subdomain' => $shout->User->Subdomain
        ) );
        ?>, <?php
		echo $channel;
		?> );<?php
		
		if ( $channel == 0 ) {
			return 0;
		}
		// private channel (non-main)
		// subchannels are in the form of $userid . "x" . $authtokenpart
		$userinfo = ChannelFinder::FindParticipantsByChannel( $channel );
		return $userinfo;
    }
?>
