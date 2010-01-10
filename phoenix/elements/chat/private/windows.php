<?php
    class ElementChatPrivateWindows extends Element {
        public function Render() {
            global $user;
            global $libs;
            global $page;

            if ( !$user->Exists() ) {
                return;
            }

            $libs->Load( 'chat/channel' );

            $channels = ChannelFinder::FindByUserid( $user->Id );
            
            if ( empty( $channels ) ) {
                return;
            }

            $page->AttachScript( 'js/puffin.js' );
            $page->AttachScript( 'js/im.js' );
            $page->AttachStylesheet( 'css/puffin.css' );
            $page->AttachStylesheet( 'css/chat.css' );

            ob_start();
            ?>var User = '<?php
            echo $user->Name;
            ?>';<?php
            Element( 'comet/init' );

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
                        ?><li><strong><?php
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
                ?>
                IM.CreateWindow( <?php
                echo $id;
                ?>, <?php
                echo $channel[ 'x' ];
                ?>, <?php
                echo $channel[ 'y' ];
                ?>, <?php
                echo $channel[ 'w' ];
                ?>, <?php
                echo $channel[ 'h' ];
                ?>, $( '.imwindow' )[ <?php
                echo $i;
                ?> ] );<?php
                $page->AttachInlineScript( ob_get_clean() );
                ++$i;
            }
            ob_start();
            ?>
            Comet.Subscribe( 'FrontpageShoutboxNew<?php
            echo $user->Id;
            ?>x<?php
            echo substr( $user->Authtoken, 0, 10 );
            ?>' );
            Comet.Subscribe( 'FrontpageShoutboxTyping<?php
            echo $user->Id;
            ?>x<?php
            echo substr( $user->Authtoken, 0, 10 );
            ?>' );<?php
            $page->AttachInlineScript( ob_get_clean() );
        }
    }
?>
