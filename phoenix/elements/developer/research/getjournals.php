<?php

    class ElementDeveloperResearchGetjournals extends Element {
        public function Render( tText $username ) {
            global $user;
            global $libs;

            $libs->Load( 'research/spot' );

            $username = $username->Get();
            if ( !empty( $username ) ) {
                $userfinder = New UserFinder();
                $theuser = $userfinder->FindByName( $username );
            }
            else {
                $theuser = $user;
            }

            ?>Recommended journals<br />
            <small>Powered by Spot</small><br /><?php

            $journals = Spot::GetJournals( $theuser );
            ?><div id="jlist">
            <ul><?php
            foreach ( $journals as $journal ) {
                if ( !is_int( $journal->Bulkid ) ) { // weird bug. hope it's sandbox issue
                    continue;
                }
                ?><li><?php
                Element( 'journal/small', $journal );
                ?></li><?php
            }
            ?></ul></div><?php
        }
    }

?>
