<?php
    function UnitChatVideoStart( tInteger $channelid, tCoalaPointer $f ) {
        global $libs;
        global $user;

        $channelid = $channelid->Get();
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

        $finder = New ChatVideoFinder();
        $streams = $finder->FindByChannelId( $channelid );
        $flag = false;
        foreach ( $streams as $video ) {
            if ( $video->Userid == $user->Id ) {
                // stream already exists, activate it
                $flag = true;
                break;
            }
        }

        if ( !$flag ) {
            $video = New ChatVideo();
            $video->Channelid = $channelid;
            $video->Userid = $user->Id;
        }

        $video->Active = 1;
        $video->Save();

        die( $video->Authtoken );

        echo $f;
        ?>( <?php
        echo $channelid;
        ?>, <?php
        echo $video->Authtoken;
        ?> );<?php
    }
?>
