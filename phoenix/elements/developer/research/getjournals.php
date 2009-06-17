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
            ?><div style="width: 50%; margin-left: 10px;";><?php
                foreach ( $journals as $journal ) {
                    if ( isset( $sticky ) && $journal->Id == $sticky ) {
                        continue;
                    }
                    ?><div class="who"><?php
                            Element( 'user/display', $journal->User->Id, $journal->User->Avatar->Id, $journal->User, true );
                        ?> καταχώρησε
                    </div>
                    <div class="subject"><?php
                        $domain = str_replace( '*', urlencode( $journal->User->Subdomain ), $xc_settings[ 'usersubdomains' ] );
                        $url = $domain . 'journals/' . $journal->Url;
                        ?><a href="<?php
                        echo $url;
                        ?>"><?php
                        echo htmlspecialchars( $journal->Title );
                        ?></a>
                    </div><?php
                }
                ?></div><?php
        }
    }

?>
