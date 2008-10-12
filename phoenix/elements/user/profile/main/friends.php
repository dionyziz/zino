<?php
    class ElementUserProfileMainFriends extends Element {
        public function Render( $friends , $friendsnum , $userid , $subdomain , $usernorel ) { 
            global $xc_settings;

            ?><div class="friends"><?php
                ?><h3>Οι φίλοι μου<?php
                if ( $friendsnum > 5 ) {
                    ?> <span>(<a href="<?php
                    echo str_replace( '*', urlencode( $subdomain  ), $xc_settings[ 'usersubdomains' ] ) . 'friends';
                    ?>">προβολή όλων</a>)</span><?php
                }
                ?></h3><?php
                if ( $usernorel ) {
                    ?>Δεν έχεις προσθέσει κανέναν φίλο. Μπορείς να προσθέσεις φίλους από το προφίλ τους.<?php
                }
                else {
                    Element( 'user/list' , $friends );
                }
            ?></div><?php
        }
    }
?>
