<?php
    class ElementFrontpageJournalLatest extends Element {
        protected $mPersistent = array( 'journalseq' );

        public function Render( $journalseq ) {
            global $xc_settings;
			
			$finder = New FrontpageStickieJournalFinder();
			
			$stickies = $finder->FindLatest(); //Maximum 5 stickies
			$stickiesid = array();
			
			foreach( $stickies as $stickie ){
				$stickiesid[ $stickie->Journalid ] = true; //Array with stickies IDs as keys for quick checking later
			}
			
            $finder = New JournalFinder();
            $journals = $finder->FindFrontpageLatest();

            ?><div class="list">
                <h2>Ημερολόγια (<a href="journals">προβολή όλων</a>)</h2><?php
                foreach ( $journals as $journal ) {
                    if ( isset( $stickiesid[ $journal->Id ] ) ) { //Using indexed array keys for performance
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
                foreach( $stickies as $stickie ){
                    // Sticky article
					$journal = $stickie->Journal;
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
