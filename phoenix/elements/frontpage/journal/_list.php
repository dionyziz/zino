<?php
    /*
    Masked by: abresas
    Reason: spot
    */
    class ElementFrontpageJournalList extends Element {
        // protected $mPersistent = array( 'journalseq' );

        public function Render( $journalseq ) {
            global $xc_settings;
			global $libs;
            global $user;
            global $water;

            $sticky = 9105;
            
            if ( $user->Exists() ) {
                $libs->Load( 'research/spot' );
                $journals = Spot::GetJournals( $user, 4 );
            }
			else {
                $libs->Load( 'journal/journal' );
                $libs->Load( 'journal/frontpage' );
                $finder = New JournalFinder();
                $journals = $finder->FindFrontpageLatest( 0, 4 );
            }

            ?><div class="list">
                <h2>Ημερολόγια (<a href="journals">προβολή όλων</a>)</h2><?php
                foreach ( $journals as $journal ) {
                    $water->Trace( 'Journal ID ' . $journal->Id . ' UserID ' . $journal->Userid );
                    if ( isset( $sticky ) && $journal->Id == $sticky ) {
                        continue;
                    }
                    ?><div class="event">
                        <div class="who"><?php
                            Element( 'user/display', $journal->User->Id, $journal->User->Avatarid, $journal->User, true );
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
                        </div>
                    </div><?php
                }
                if ( isset( $sticky ) ) {
                    // Sticky article
                    $journal = New Journal( $sticky );
                    if ( $journal->Exists() ) {
                        ?><div class="event">
                            <div style="background: #fff8d2 url('http://static.zino.gr/phoenix/highlight.png') no-repeat 0;" class="who"><?php
                                Element( 'user/display' , $journal->User->Id , $journal->User->Avatarid , $journal->User, true );
                            ?> καταχώρησε
                            </div>
                            <div class="subject">
                                <a href="?p=journal&amp;id=<?php
                                echo $journal->Id;
                                ?>"><?php
                                echo htmlspecialchars( $journal->Title );
                                ?></a>
                            </div>
                        </div><?php
                    }
                }
				?></div><?php
        }
    }
?>
