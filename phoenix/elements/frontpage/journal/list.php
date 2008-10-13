<?php
    class ElementFrontpageJournalList extends Element {
        protected $mPersistent = array( 'journalseq' );
        public function Render( $journalseq ) {
            $finder = New JournalFinder();
            $journals = $finder->FindAll( 0 , 4 );
            ?><div class="list">
                <h2>Ημερολόγια (<a href="journals">προβολή όλων</a>)</h2><?php
                foreach ( $journals as $journal ) {
                    ?><div class="event">
                        <div class="who"><?php
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
                    </div><?php
                }
				// Sticky Journal
				$journal = new Journal( 2191 );
				?><div style="background:#feff77;" class="event">
					<div class="who"><?php
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
				</div>
			</div><?php
        }
    }
?>
