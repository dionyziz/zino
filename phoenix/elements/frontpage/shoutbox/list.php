<?php
    class ElementFrontpageShoutboxList extends Element {
        public function Render( $shoutboxseq ) {
            global $user;

            ?>
            <h2 class="subheading">Kous-kous <span class="small1">(<a href="?p=chat">se megalo</a>)</span></h2>
            <div class="comments"><?php
                if ( $user->Exists() && $user->HasPermission( PERMISSION_SHOUTBOX_CREATE ) ) {
                    Element( 'shoutbox/reply' , $user->Id , $user->Avatarid , $user );
                }
                Element( 'frontpage/shoutbox/recent' , $shoutboxseq );
                Element( 'shoutbox/view', false , true );
            ?></div>
            <div class="eof"></div><?php
        }
    }
?>
