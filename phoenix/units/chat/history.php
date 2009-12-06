<?php
    function UnitChatHistory( tInteger $channelid, tInteger $offset, tCoalaPointer $f ) {
        global $libs;
        global $user;

        $libs->Load( 'chat/message' );
        $libs->Load( 'chat/channel' );

        $channelid = $channelid->Get();
        $offset = $offset->Get();

        $finder = New ShoutboxFinder();
        $messages = $finder->FindByChannel( $channelid, $offset, 50 );

        if ( !ChannelFinder::Auth( $channelid, $user->Id ) ) {
            return;
        }

        $data = array();
        foreach ( $messages as $message ) {
            $item = array(
                'who' => array(
                    'name' => $message->User->Name
                ),
                'text' => $message->Text,
                'created' => $message->Created,
                'id' => $message->Id
            );
            if ( $user->Id == $message->Userid ) {
                $item[ 'who' ][ 'self' ] = true;
            }
            $data[] = $item;
        }

        echo $f;
        ?>(<?php
        echo w_json_encode( $data );
        ?>);<?php
    }
?>
