<?php
    class ElementChatView extends Element {
        public function Render() {
            global $page;
            global $libs;
            
            $page->SetTitle( 'Συζήτηση' );
            $page->AttachStylesheet( 'css/chat.css' );
            $libs->Load( 'shoutbox' );
            
            $finder = New ShoutboxFinder();
            $chats = $finder->FindLatest( 0, 100 );
            
            $chats = array_reverse( $chats );
            
            ?>
            <div>
                <ol><?php
                    $prevuser = '';
                    foreach ( $chats as $chat ) {
                        ?><li><span class="time"><?php
                        Element( 'date/diff', $chat->Created );
                        ?></span> <strong><?php
                        if ( $prevuser != $chat->User->Name ) {
                            $prevuser = $chat->User->Name;
                            Element( 'user/name', $chat->User );
                        }
                        ?></strong> <?php
                        echo nl2br( $chat->Text );
                        ?></li><?php
                    }
                ?>
                </ol>
            </div>
            <?php
            
            return array( 'tiny' => true );
        }
    }
?>
