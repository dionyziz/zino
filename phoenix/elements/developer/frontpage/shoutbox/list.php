<?php
    class ElementDeveloperFrontpageShoutboxList extends Element {
        public function Render( $shoutboxseq ) {
            global $user;


            ?><h2 class="subheading">Συζήτηση <span class="small1">(<a href="?p=chat">μεγιστοποίηση</a>)</span></h2>
            <div class="comments"><?php
                if ( $user->Exists() && $user->HasPermission( PERMISSION_SHOUTBOX_CREATE ) ) {
                    Element( 'developer/shoutbox/reply' , $user->Id , $user->Avatarid , $user );
                }
                Element( 'developer/frontpage/shoutbox/recent' , $shoutboxseq );
                Element( 'developer/shoutbox/view', false , true );
            ?></div>
            <div class="eof"></div><?php
        }
    }
?>
