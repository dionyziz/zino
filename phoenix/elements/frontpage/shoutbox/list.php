<?php
    class ElementFrontpageShoutboxList extends Element {
        public function Render( $shoutboxseq ) {
            global $user;

            ?><h2>Συζήτηση <span>(<a href="shouts">προβολή όλων</a>)</span></h2>
            <div class="comments"><?php
                if ( $user->Exists() && $user->HasPermission( PERMISSION_SHOUTBOX_CREATE ) ) {
                    Element( 'shoutbox/reply' , $user->Id , $user->Avatar->Id , $user );
                    switch ( strtolower( $user->Name ) ) {
                        case 'dionyziz':
                        case 'pagio91':
                        case 'izual':
                        case 'petrosagg18':
                        case 'gatoni':
                        case 'ted':
                        case 'kostis90gr':
                            Element( 'shoutbox/comet' );
                    }
                }
                Element( 'frontpage/shoutbox/recent' , $shoutboxseq );
                Element( 'shoutbox/view', false , true );
            ?></div>
            <div class="eof"></div><?php
        }
    }
?>
