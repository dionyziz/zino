<?php
    class ElementChatPrivateWindows extends Element {
        public function Render() {
            global $user;
            global $libs;
            global $page;

            $libs->Load( 'chat/channel' );

            $page->AttachScript( 'js/puffin.js' );
            $page->AttachStylesheet( 'css/puffin.css' );

            $channels = ChannelFinder::FindByUserid( $user->Id );
            
            foreach ( $channels as $id => $channel ) {
                ?><div id="im_<?php
                echo $id;
                ?>" style="display:none"><?php
                $participants = $channel[ 'participants' ];
                foreach ( $participants as $participant ) {
                    ?><p><?php
                    echo $participant[ 'name' ];
                    ?></p><?php
                }
                ?></div><?php
                ob_start();
                ?>var chatWindow = Puffin.create();
                chatWindow.move( 100, 100 );
                chatWindow.resize( 200, 300 );
                chatWindow.setContent( $( '#im_<?php
                echo $id;
                ?>' )[ 0 ] );
                chatWindow.show();
                <?php
                $page->AttachInlineScript( ob_get_clean() );
            }
        }
    }
?>
