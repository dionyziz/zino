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
            
            $i = 0;
            foreach ( $channels as $id => $channel ) {
                ?><div class="imwindow" style="display:none"><?php
                if ( $channel[ 'w' ] == 0 ) {
                    $channel[ 'w' ] = 100;
                }
                if ( $channel[ 'h' ] == 0 ) {
                    $channel[ 'h' ] = 100;
                }
                $participants = $channel[ 'participants' ];
                if ( count( $participants ) == 1 ) {
                    $title = $participants[ 0 ][ 'name' ];
                }
                else {
                    $names = array();
                    foreach ( $participants as $participant ) {
                        $names[] = $participant[ 'name' ];
                    }
                    $title = implode( ', ', $names );
                }
                ?><h3><?php
                echo $title;
                ?></h3><?php
                ?></div><?php
                ob_start();
                ?>var chatWindow = Puffin.create();
                chatWindow.move( <?php
                echo $channel[ 'x' ];
                ?>, <?php
                echo $channel[ 'y' ];
                ?>);
                chatWindow.resize( <?php
                echo $channel[ 'w' ];
                ?>, <?php
                echo $channel[ 'h' ];
                ?> );
                var content = chatWindow.setContent( $( '.imwindow' )[ <?php
                echo $i;
                ?> ] );
                content.id = 'im_<?php
                echo $id;
                ?>';
                chatWindow.onmove = function ( x, y ) {
                    Coala.Warm( 'chat/window/update', {
                        channelid: <?php
                        echo $id;
                        ?>,
                        x: x,
                        y: y
                    } );
                };
                chatWindow.onresize = function ( w, h ) {
                    Coala.Warm( 'chat/window/update', {
                        channelid: <?php
                        echo $id;
                        ?>,
                        w: w,
                        h: h
                    } );
                };
                chatWindow.show();
                <?php
                $page->AttachInlineScript( ob_get_clean() );
                ++$i;
            }
        }
    }
?>
