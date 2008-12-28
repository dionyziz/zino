<?php
    class ElementFrontpageJournalList extends Element {
        protected $mPersistent = array( 'journalseq' );
        public function Render( $journalseq ) {
            global $xc_settings;

            $finder = New JournalFinder();
            $journals = $finder->FindAll( 0, 4 );
            ?><div class="list">
                <h2>Ημερολόγια (<a href="journals">προβολή όλων</a>)</h2><?php
                foreach ( $journals as $journal ) {
                    ?><div class="event">
                        <div class="who"><?php
                            Element( 'user/display', $journal->User->Id, $journal->User->Avatar->Id, $journal->User );
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
				// Sticky article
                /* 
				$journal = New Journal( 6358 );
					?><div class="event">
						<div style="background: #fff8d2;" class="who"><?php
							Element( 'user/display' , $journal->User->Id , $journal->User->Avatar->Id , $journal->User );
						?> καταχώρησε
						</div>
						<div class="subject">
							<a href="?p=journal&amp;id=<?php
							echo $journal->Id;
							?>"><?php
							echo htmlspecialchars( $journal->Title );
							?></a>
						</div>
					</div><?php */
				?></div><?php
        }
    }
?>
