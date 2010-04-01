<?php
    class ElementUserProfileMainFriends extends Element {
        protected $mPersistent = array( 'userid' );

        public function Render( $friends , $friendsnum , $userid , $subdomain , $usernorel ) { 
            global $xc_settings;

            ?><div class="friends"><?php
                ?><h2 class="pheading">oi friends m <?php
                if ( $friendsnum > 5 ) {
                    ?> <span class="small1">(<a href="<?php
                    echo str_replace( '*', urlencode( $subdomain  ), $xc_settings[ 'usersubdomains' ] ) . 'friends';
                    ?>">oloiii</a>)</span><?php
                }
                ?></h2><?php
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
