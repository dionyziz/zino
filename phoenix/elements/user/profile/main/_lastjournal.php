<?php
    class ElementUserProfileMainLastjournal extends Element {
        protected $mPersistent = array( 'journalid' , 'numcomments' , 'numjournals' , 'sameuser' );
        public function Render( $journal , $theuser , $journalid , $numcomments , $numjournals , $sameuser ) {
            ?><div class="lastjournal">
                <h2 class="pheading">Ημερολόγιο <?php
                if ( $numjournals > 1 ) {
                    ?><span class="small1">(<a href="<?php
                    Element( 'user/url' , $theuser->Id , $theuser->Subdomain );
                    ?>journals">προβολή όλων</a>)</span><?php
                }
                ?></h2><?php
                if ( $sameuser ) {
                    ?><div class="nojournals">
                    Δεν έχεις καμία καταχώρηση.<br />
                    Κανε click στο παρακάτω link για να δημιουργήσεις μια.<br />
                    <a href="?p=addjournal">Καταχώρηση Ημερολογίου</a>
                    <div></div>
                    </div><?php
                }
                else {
                    Element( 'journal/small' , $journal );
                }    
            ?></div>
            <div class="barfade">
                <div class="s1_0070 leftbar"></div>
                <div class="s1_0071 rightbar"></div>
            </div><?php
        }
    }
?>
