<?php
    class ElementAboutInfoStatus extends Element {
        public function Render() {
            global $page;
            global $libs;

            $libs->Load( 'journal/journal' );
            
            $page->SetTitle( 'Κατάσταση' );
            
            $journalfinder = New JournalFinder();
            $userfinder = New UserFinder();
            $user = $userfinder->FindByName( 'oniz' );
            $journals = $journalfinder->FindByUser( $user, 0, 3 );
            
            ?><h2 class="sweet">Πρόσφατη κατάσταση του Zino</h2>
            <ul class="blog">
            <?php
            foreach ( $journals as $journal ) {
                ?><li><?php
                    Element( 'journal/small' , $journal );
                    ?><div class="barfade">
                        <div class="s1_0070 leftbar"></div>
                        <div class="s1_0071 rightbar"></div>
                    </div>
                </li><?php
            }
            ?></ul><?php
        }
    }
?>
