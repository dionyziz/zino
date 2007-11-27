<?php
    function ElementChatCallisto() {
        global $user;
        global $libs;
        global $page;
        
        if ( !$user->Exists() ) {
            return;
        }
        
        $libs->Load( 'callisto/callisto' );
        
        $page->AttachScript( 'http://orion.kamibu.com:8000/_/orbited.js', 'javascript', true );
        $page->AttachScript( 'js/chat.js', 'javascript', true );
        
        $channel = New Callisto_Channel( '/chat/channels/kamibu' );
        $subscription = $channel->Subscribe();
        
        $page->SetTitle( 'Chat' );
        
        ?>
        <script type="text/javascript">
        Chat.StartPolling( <?php
        echo w_json_encode( $subscription->Id );
        ?>, <?php
        echo w_json_encode( $subscription->Token );
        ?> );
        </script>
        <?php
    }
?>
