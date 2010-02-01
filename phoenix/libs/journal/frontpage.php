<?php
    class FrontpageJournalFinder extends Finder {
        protected $mModel = 'FrontpageJournal';

        public function FindLatest( $offset = 0, $limit = 4, $totalcount = false ) {
            $prototype = New FrontpageJournal();
            
            $latest = $this->FindByPrototype( $prototype, $offset, $limit, array( 'Journalid', 'DESC' ), $totalcount );
            
            $journalids = array();
            $userids = array();
            
            foreach ( $latest as $frontpagejournal ) {
                $journalids[] = $frontpagejournal->Journalid;
                $userids[] = $frontpagejournal->Userid;
            }
            
            $userfinder = New UserFinder();
            $users = $userfinder->FindByIds( $userids );
            
            w_assert( $users instanceof Collection );
            
            $journalfinder = New JournalFinder();
            $journals = $journalfinder->FindByIds( $journalids );
            w_assert( $journals instanceof Collection );
            
            $userbyid = array();
            $journalbyid = array();
            foreach ( $users as $user ) {
                w_assert( $user instanceof User, 'Expecting instance of User, ' . gettype( $user ) . ' variable given' );
                w_assert( $user->Id > 0 );
                $userbyid[ $user->Id ] = $user;
            }
            foreach ( $journals as $journal ) {
                w_assert( $journal instanceof Journal );
                w_assert( $journal->Id > 0 );
                $journalbyid[ $journal->Id ] = $journal;
            }
            
            $c = 0;
            foreach ( $latest as $i => $frontpagejournal ) {
                if ( isset( $userbyid[ $frontpagejournal->Userid ] ) ) {
                    $latest[ $i ]->CopyUserFrom( $userbyid[ $frontpagejournal->Userid ] );
                }
                if ( isset( $journalbyid[ $frontpagejournal->Journalid ] ) ) {
                    $latest[ $i ]->CopyJournalFrom( $journalbyid[ $frontpagejournal->Journalid ] );
                    if ( isset( $userbyid[ $frontpagejournal->Userid ] ) ) {
                        $latest[ $i ]->Journal->CopyUserFrom( $userbyid[ $frontpagejournal->Userid ] );
                    }
                }
            }
            
            return $latest;
        }
    }
    
	class FrontpageStickieJournalFinder extends Finder {
	        protected $mModel = 'FrontpageStickieJournal';
	}
	
	class FrontpageStickieJournal extends Satori {
		protected $mDbTableAlias = 'journalstickies';
		
		protected function Relations() {
            $this->Journal = $this->HasOne( 'Journal', 'Journalid' );
        }
	}
	
    class FrontpageJournal extends Satori {
        protected $mDbTableAlias = 'journalsfrontpage';
        
        public function CopyJournalFrom( $value ) {
            $this->mRelations[ 'Journal' ]->CopyFrom( $value );
        }
        public function CopyUserFrom( $value ) {
            $this->mRelations[ 'User' ]->CopyFrom( $value );
        }
        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Journal = $this->HasOne( 'Journal', 'Journalid' );
        }
    }
?>
