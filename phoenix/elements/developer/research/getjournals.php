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
            ?><div style="margin-left: 10px;"><?php
                foreach ( $journals as $journal ) {
                    echo $journal->User->Name;
                    ?><div><?php
                        $domain = str_replace( '*', urlencode( $journal->User->Subdomain ), $xc_settings[ 'usersubdomains' ] );
                        $url = $domain . 'journals/' . $journal->Url;
                        ?><a href="?p=journal&id=<?php
                        echo $journal->Id;
                        ?>"><?php
                        echo htmlspecialchars( $journal->Title );
                        ?></a>
                    </div>
                    <div style="clear:both;" />
                    <br /><br /><?php
                }
                ?></div><?php
        }
    }

?>
