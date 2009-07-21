<?php
    class ElementFrontpageJournalList extends Element {
        protected $mPersistent = array( 'journalseq' );

        public function Render( $journalseq ) {
            global $xc_settings;
			global $libs;

            // $sticky = 9659;

            $libs->Load( 'journal/journal' );
			$libs->Load( 'journal/frontpage' );
			
            $finder = New JournalFinder();
            $journals = $finder->FindFrontpageLatest( 0, 4 );
            ?><div class="list">
                <h2>Ημερολόγια (<a href="journals">προβολή όλων</a>)</h2><?php
                foreach ( $journals as $journal ) {
                    if ( isset( $sticky ) && $journal->Id == $sticky ) {
                        continue;
                    }
                    ?><div class="event">
                        <div class="who"><?php
                            Element( 'user/display', $journal->Userid, $journal->User->Avatarid, $journal->User, true );
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
                                Element( 'user/display' , $journal->Userid , $journal->User->Avatarid , $journal->User, true );
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
