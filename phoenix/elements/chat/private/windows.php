<?php
    class ElementChatPrivateWindows extends Element {
        public function Render() {
            global $user;
            global $libs;

            $libs->Load( 'chat/channel' );

            $channels = ChannelFinder::FindByUserid( $user->Id );
            
            foreach ( $channels as $channel ) {
                ?>(begin chat)<ul><?php
                $participants = $channel[ 'participants' ];
                foreach ( $participants as $participant ) {
                    ?><li><?php
                    echo $participant[ 'name' ];
                    ?></li><?php
                }
                ?></ul>(end chat)<?php
            }
        }
    }
?>
