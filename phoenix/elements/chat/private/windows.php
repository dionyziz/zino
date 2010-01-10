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
                <ul>
                    <?php
                    foreach ( $channel[ 'history' ] as $message ) {
                        ?><li id="s_<?php
                        echo $message[ 'id' ];
                        ?>"><strong><?php
                        echo $message[ 'name' ];
                        ?></strong> <div class="text"><?php
                        echo $message[ 'text' ]; // XHTML sane
                        ?></div></li><?php
                    }
                    ?>
                </ul>
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
                Puffin.clickable( $( content ).find( 'ul' )[ 0 ] );
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
                $( content ).find( 'textarea' ).keyup( function( e ) {
                    var code;

                    if ( !e ) {
                        var e = window.event;
                    }
                    if ( e.keyCode ) {
                        code = e.keyCode; 
                    }
                    else if ( e.which ) {
                        code = e.which;
                    }
                    else {
                        return;
                    }
                    if ( code == 13 ) { // enter
                        var li = document.createElement( 'li' );
                        var text = document.createElement( 'div' );
                        var strong = document.createElement( 'strong' );
                        strong.appendChild( document.createTextNode( '<?php
                        echo $user->Name;
                        ?>' ) );
                        // ---
                        text.className = 'text';
                        text.appendChild( document.createTextNode( this.value ) );
                        li.appendChild( strong );
                        li.appendChild( document.createTextNode( ' ' ) );
                        li.appendChild( text );
                        // ---
                        $( this.parentNode.parentNode ).find( 'ul' )[ 0 ].appendChild( li );
                        Coala.Warm( 'shoutbox/new', {
                            text: this.value,
                            channel: <?php
                            echo $id;
                            ?>,
                            node: li
                        } );
                        this.value = '';
                    }
                } );
                chatWindow.minSize( 139, 25 );
                chatWindow.show();
                <?php
                $page->AttachInlineScript( ob_get_clean() );
                ++$i;
            }
        }
    }
?>
