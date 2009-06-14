<?php
    class ElementAboutInfoStatus extends Element {
        public function Render() {
            $journalfinder = New JournalFinder();
            $userfinder = New UserFinder();
            $user = $userfinder->FindByName( 'oniz' );
            $journals = $finder->FindByUser( $user, 0, 3 );
            
            ?><h2 class="sweet">Πρόσφατη κατάσταση του Zino</h2>
            <ul class="blog">
            <?php
            foreach ( $journals as $journal ) {
                ?><li><?php
                    Element( 'journal/small' , $journal );
                    ?><div class="barfade">
                        <div class="leftbar"></div>
                        <div class="rightbar"></div>
                    </div>
                </li><?php
            }
            ?></ul><?php
        }
    }
?>
