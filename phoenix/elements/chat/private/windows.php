<?php
    class ElementChatPrivateWindows extends Element {
        public function Render() {
            global $user;
            global $libs;
            global $page;

            $libs->Load( 'chat/channel' );

            $channels = ChannelFinder::FindByUserid( $user->Id );
            
            if ( empty( $channels ) ) {
                return;
            }

            $page->AttachScript( 'js/puffin.js' );
            $page->AttachStylesheet( 'css/puffin.css' );
            $page->AttachStylesheet( 'css/chat.css' );

            $i = 0;
            foreach ( $channels as $id => $channel ) {
                ?><div class="imwindow" style="display:none"><?php
                if ( $channel[ 'w' ] == 0 ) {
                    $channel[ 'w' ] = 300;
                }
                if ( $channel[ 'h' ] == 0 ) {
                    $channel[ 'h' ] = 300;
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
                ?><h3><a href="" class="close">&times;</a><?php
                echo $title;
                ?></h3>
                <div class="typehere">
                    <textarea></textarea>
                </div>
                </div><?php
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
                Puffin.clickable( $( content ).find( 'textarea' )[ 0 ] );
                Puffin.clickable( $( content ).find( 'a' )[ 0 ] );
                $( content ).find( 'a' )[ 0 ].onclick = ( function ( me ) {
                    return function () {
                        me.hide();
                        Coala.Warm( 'chat/window/update', {
                            channelid: <?php
                            echo $id;
                            ?>,
                            deactivate: true
                        } );
                        return false;
                    };
                } )( chatWindow );
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
