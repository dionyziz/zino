<?php
    class ElementDashboardView extends Element {
        public function Render() {
            global $page, $libs, $user;

            $page->AttachStylesheet( 'css/default.css' );
            $page->AttachStylesheet( 'css/banner.css' );
            $page->AttachStylesheet( 'css/footer.css' );
            $page->AttachStylesheet( 'css/links.css' );
            $page->AttachStylesheet( 'css/emoticons.css' );
            $page->AttachStylesheet( 'css/spriting/sprite1.css' );
            $page->AttachStylesheet( 'css/spriting/sprite2.css' );
            $page->AttachStylesheet( 'css/spriting/spritex.css' );
            $page->AttachStylesheet( 'css/dashboard.css' );

            $page->AttachScript( 'http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js' );
            $page->AttachScript( 'js/kamibu.js' );
            $page->AttachScript( 'js/coala.js' );
            $page->AttachScript( 'js/meteor.js' );
            $page->AttachScript( 'js/comet.js' );
            $page->AttachScript( 'js/dashboard.js' ); 

            ob_start();
            ?>Dashboard.OnLoad();<?php
            if ( $user->Exists() ) {
                ?>
                var User = "<?php
                echo $user->Name;
                ?>";
                <?php
            }
            ?>Comet.Init(<?php
            echo w_json_encode( uniqid() );
            ?>);
            Comet.Subscribe( 'FrontpageShoutboxNew0' );
            Comet.Subscribe( 'FrontpageShoutboxTyping0' );<?php
            $page->AttachInlineScript( ob_get_clean() );

            $libs->Load( 'chat/message' );

            $finder = New ShoutboxFinder();
            $chats = $finder->FindByChannel( 0, 0, 20 );

            $messages = array();
            foreach ( $chats as $chat ) {
                array_unshift( $messages, array(
                    'id' => $chat->Id,
                    'username' => $chat->User->Name,
                    'html' => $chat->Text
                ) );
            }

            $libs->Load( 'content' );

            $stream = Content_GetContent();
            $res = array();
            foreach ( $stream as $fish ) {
                $item = $fish[ 'item' ];
                $type = get_class( $item );
                $userid = $item->Userid;
                $id = $item->Id;
                $key = $type . ':' . $userid;
                if ( $type != 'Image' ) {
                    // only allow collation of images
                    $key .= ':' . $id;
                }
                if ( isset( $res[ $key ] ) ) {
                    // image by same user already exists in stream; add it to the fish
                    if ( !is_array( $res[ $key ][ 'item' ] ) ) {
                        $res[ $key ][ 'item' ] = array( $res[ $key ][ 'item' ] );
                    }
                    $res[ $key ][ 'item' ][] = $item;
                }
                else {
                    // no images by this user exist in the stream, create a new fish
                    $res[ $key ] = array(
                        'type' => $type,
                        'item' => $fish[ 'item' ],
                        'comments' => $fish[ 'comments' ]
                    );
                }
            }

			?>
            <div id="nowbar">
                <div class="border">
                    <?php
                        Element( 'dashboard/friends' );
                        Element( 'dashboard/chat', $messages );
                    ?>
                </div>
            </div>
            <div id="frontpage">
                <div id="upstrip">
                    <?php
                        Element( 'banner' );
                    ?>
                </div>
                <div id="midstrip">
                    <div id="strip1">
                        <div id="strip1left" class="s1_0013"></div>
                        <div id="strip1right" class="s1_0014"></div>
                    </div>

                    <div id="strip2" class="sx_0010">
                        <div id="content">
                            <?php
                                Element( 'dashboard/notifications' );
                                Element( 'dashboard/stream', $stream );
                            ?>
                        </div>
                    </div>
                </div>
            
                <div id="strip3">
                    <div id="strip3left" class="s1_0015"></div>
                    <div id="strip3right" class="s1_0016"></div>
                    <div id="strip3middle" class="sx_0003"></div>
                </div>
                
                <div id="downstrip" class="sx_0002" style="position:relative">
                    <?php
                        Element( 'footer' );
                    ?>
                </div>
            </div>
			<?php
            return array( 'tiny' => true, 'selfmanaged' => true );
        }
    }
?>
