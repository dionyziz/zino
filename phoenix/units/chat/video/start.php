<?php
    function UnitChatVideoStart( tInteger $channelid, tCoalaPointer $f ) {
        global $libs;
        global $user;

        $channelid = $channelid->Get();

        $libs->Load( 'chat/video' );
        $libs->Load( 'chat/channel' );

        if ( !$user->Exists() ) { // we must be logged in
            return;
        }
        if ( !ChannelFinder::Auth( $channelid, $user->Id ) ) { // we must be in the channel
            return;
        }

        $participants = ChannelFinder::FindParticipantsByChannel( $channelid );
        if ( count( $participants ) != 2 ) {
            return; // only one-to-one video for now
        }

        $video = New ChatVideo();
        $video->Channelid = $channelid;
        $video->Userid = $user->Id;
        $video->Active = true;
        $video->Save();

        echo $f;
        ?>( <?php
        echo $channelid;
        ?>, <?php
        echo $video->Authtoken;
        ?> );<?php
    }
?>
