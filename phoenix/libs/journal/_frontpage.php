<?php
	/*
		MASKED
		By: Rhapsody
		Reason: New class for displaying the latest journals on the frontpage
	*/
    class FrontpageJournalFinder extends Finder {
        protected $mModel = 'FrontpageJournal';

        public function FindLatest( $offset = 0, $limit = 15, $totalcount = false ) {
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
            
            w_assert( is_array( $users ) );
            
            $journalfinder = New ImageFinder();
            $journals = $journalfinder->FindByIds( $journalids );
            w_assert( is_array( $journals ) );
            
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
                $imagebyid[ $journal->Id ] = $journal;
            }
            
            $c = 0;
            foreach ( $latest as $i => $frontpagejournal ) {
                if ( isset( $userbyid[ $frontpagejournal->Userid ] ) ) {
                    $latest[ $i ]->CopyUserFrom( $userbyid[ $frontpagejournal->Userid ] );
                }
                if ( isset( $imagebyid[ $frontpagejournal->Imageid ] ) ) {
                    $latest[ $i ]->CopyJournalFrom( $imagebyid[ $frontpagejournal->Imageid ] );
                    if ( isset( $userbyid[ $frontpageimage->Userid ] ) ) {
                        $latest[ $i ]->Image->CopyUserFrom( $userbyid[ $frontpagejournal->Userid ] );
                    }
                }
            }
            
            return $latest;
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
