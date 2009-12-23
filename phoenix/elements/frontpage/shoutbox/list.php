<?php
    class ElementFrontpageShoutboxList extends Element {
        public function Render( $shoutboxseq ) {
            global $user;


            ?>
            <h2 class="subheading" style="color:black"><a href="http://www.zino.gr/?p=journal&amp;id=11690">Zino Meeting στο Θυμωμένο Πορτρέτο στα Γιάννενα</a></h2>
            <h2 class="subheading">Συζήτηση <span class="small1">(<a href="?p=chat">μεγιστοποίηση</a>)</span></h2>
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
