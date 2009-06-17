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
            ?><div style="margin: 10px 0 0 10px;"><?php
                foreach ( $journals as $journal ) {
                    ?><div style="border-bottom: 1px solid silver; padding-top: 5px;"><?php
                    echo $journal->User->Name;
                    ?><br /><?php
                    ?><a href="?p=journal&amp;id=<?php
                    echo $journal->Id;
                    ?>"><?php
                    echo htmlspecialchars( $journal->Title );
                    ?></a>
                    <br /><?php
                    if ( is_int( $journal->Bulkid ) ) {
                        echo $journal->GetText( 300 );
                    }
                    ?><br /><br /></div><?php
                }
                ?></div><?php
        }
    }

?>
