<?php
    class ElementChatView extends Element {
        public function Render() {
            global $page;
            global $libs;
            global $user;
            
            $page->SetTitle( 'Συζήτηση' );
            
            $page->AttachStylesheet( 'css/chat.css' );
            $page->AttachStylesheet( 'css/emoticons.css' );
            
            $page->AttachScript( 'js/settings.js' );
            $page->AttachScript( 'js/kamibu.js' );
            $page->AttachScript( 'js/jquery.js' );
            $page->AttachScript( 'js/coala.js' );
            $page->AttachScript( 'js/meteor.js' );
            $page->AttachScript( 'js/comet.js' );
            $page->AttachScript( 'js/chat.js' );
            
            $libs->Load( 'shoutbox' );
            
            $finder = New ShoutboxFinder();
            $chats = $finder->FindLatest( 0, 100 );
            
            $chats = array_reverse( $chats );
            
            ob_start();
            ?>Comet.Init(<?php
            echo w_json_encode( session_id() );
            ?>);
            Comet.Subscribe( 'FrontpageShoutboxNew' );
            Comet.Subscribe( 'FrontpageShoutboxTyping' );
            User = '<?php
            echo $user->Name;
            ?>';
            <?php
            $page->AttachInlineScript( ob_get_clean() );
            
            ?>
            <div>
                <ol><?php
                    $prevuser = '';
                    foreach ( $chats as $chat ) {
                        ?><li id="s_<?php
                        echo $chat->Id;
                        ?>"><span class="time"><?php
                        Element( 'date/diff', $chat->Created );
                        ?></span> <strong><?php
                        if ( $prevuser != $chat->User->Name ) {
                            $prevuser = $chat->User->Name;
                            Element( 'user/name', $chat->User->Id, $chat->User->Name, $chat->User->Subdomain, false );
                        }
                        else {
                            ?>&#160;<?php
                        }
                        ?></strong> <div class="text"><?php
                        echo nl2br( $chat->Text );
                        ?></div></li><?php
                    }
                ?>
                </ol><?php
                if ( $user->Exists() ) {
                    ?><div class="typehere">
                        <textarea>Πρόσθεσε ένα σχόλιο στη συζήτηση</textarea>
                    </div><?php
                }
            ?></div><?php
            
            return array( 'tiny' => true );
        }
    }
?>
