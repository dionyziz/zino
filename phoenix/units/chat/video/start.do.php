<?php
    function UnitChatVideoStart( tInteger $channelid, tCoalaPointer $f ) {
        global $libs;
        global $user;

        $channelid = $channelid->Get();
        die( ',' . $channelid );

        $libs->Load( 'chat/video' );
        $libs->Load( 'chat/channel' );

        if ( !$user->Exists() ) { // we must be logged in
            ?>alert( 'Please login' );<?php
            return;
        }
        if ( !ChannelFinder::Auth( $channelid, $user->Id ) ) { // we must be in the channel
            ?>alert( 'Please try a different channel' );<?php
            return;
        }

        $participants = ChannelFinder::FindParticipantsByChannel( $channelid );
        if ( count( $participants ) != 2 ) {
            ?>alert( 'Only one-to-one channels are allowed for now' );<?php
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
