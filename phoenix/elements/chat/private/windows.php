<?php
    class ElementChatPrivateWindows extends Element {
        public function Render() {
            global $user;
            global $libs;
            global $page;

            $libs->Load( 'chat/channel' );

            $page->AttachScript( 'js/puffin.js' );

            $channels = ChannelFinder::FindByUserid( $user->Id );
            
            foreach ( $channels as $channel ) {
                ?><div id="im_<?php
                echo $channel[ 'id' ];
                ?>" style="display:none"><?php
                $participants = $channel[ 'participants' ];
                foreach ( $participants as $participant ) {
                    ?><p><?php
                    echo $participant[ 'name' ];
                    ?></p><?php
                }
                ?></div><?php
            }
        }
    }
?>
