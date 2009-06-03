<?php
	/*
		MASKED
		By: Rhapsody
		Reason: New class for displaying the latest polls on the frontpage
	*/
	
    class FrontpagePollFinder extends Finder {
        protected $mModel = 'FrontpagePoll';

        public function FindLatest( $offset = 0, $limit = 4, $totalcount = false ) {
            $prototype = New FrontpagePoll();
            
            $latest = $this->FindByPrototype( $prototype, $offset, $limit, array( 'Pollid', 'DESC' ), $totalcount );
            
            $pollids = array();
            $userids = array();
            
            foreach ( $latest as $frontpagepoll ) {
                $pollids[] = $frontpagepoll->Pollid;
                $userids[] = $frontpagepoll->Userid;
            }
            
            $userfinder = New UserFinder();
            $users = $userfinder->FindByIds( $userids );
            
            w_assert( is_array( $users ) );
            
            $pollfinder = New PollFinder();
            $polls = $pollfinder->FindByIds( $pollids );
            w_assert( is_array( $polls ) );
            
            $userbyid = array();
            $pollbyid = array();
            foreach ( $users as $user ) {
                w_assert( $user instanceof User, 'Expecting instance of User, ' . gettype( $user ) . ' variable given' );
                w_assert( $user->Id > 0 );
                $userbyid[ $user->Id ] = $user;
            }
            foreach ( $polls as $poll ) {
                w_assert( $poll instanceof Poll );
                w_assert( $poll->Id > 0 );
                $pollbyid[ $poll->Id ] = $poll;
            }
            
            $c = 0;
            foreach ( $latest as $i => $frontpagepoll ) {
                if ( isset( $userbyid[ $frontpagepoll->Userid ] ) ) {
                    $latest[ $i ]->CopyUserFrom( $userbyid[ $frontpagepoll->Userid ] );
                }
                if ( isset( $pollbyid[ $frontpagepoll->Pollid ] ) ) {
                    $latest[ $i ]->CopyPollFrom( $pollbyid[ $frontpagepoll->Pollid ] );
                    if ( isset( $userbyid[ $frontpagepoll->Userid ] ) ) {
                        $latest[ $i ]->Poll->CopyUserFrom( $userbyid[ $frontpagepoll->Userid ] );
                    }
                }
            }
            
            return $latest;
        }
    }
    
    class FrontpagePoll extends Satori {
        protected $mDbTableAlias = 'pollsfrontpage';
        
        public function CopyPollFrom( $value ) {
            $this->mRelations[ 'Poll' ]->CopyFrom( $value );
        }
        public function CopyUserFrom( $value ) {
            $this->mRelations[ 'User' ]->CopyFrom( $value );
        }
        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Poll = $this->HasOne( 'Poll', 'Pollid' );
        }
    }
?>
