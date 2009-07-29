<?php
    class ElementUserProfileMainLastjournal extends Element {
        protected $mPersistent = array( 'journalid' , 'numcomments' , 'numjournals' , 'sameuser' );
        public function Render( $journal , $theuser , $journalid , $numcomments , $numjournals , $sameuser ) {
            ?><div class="lastjournal">
                <h3>Ημερολόγιο <?php
                if ( $numjournals > 1 ) {
                    ?><span>(<a href="<?php
                    Element( 'user/url' , $theuser->Id , $theuser->Subdomain );
                    ?>journals">προβολή όλων</a>)</span><?php
                }
                ?></h3><?php
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
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div><?php
        }
    }
?>
