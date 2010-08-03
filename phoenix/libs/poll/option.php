<?php

    global $libs;
    $libs->Load( 'poll/vote' );

    class PollOptionFinder extends Finder {
        protected $mModel = 'PollOption';
        
        public function FindByPoll( $poll ) {
            $query = $this->mDb->Prepare( "
                SELECT
                    *
                FROM
                    :polloptions 
                WHERE 
                    `polloption_pollid` = :pollid 
                LIMIT 
                    :offset, :limit
                ");
                
            $query->BindTable( 'polloptions' );
            $query->Bind( 'pollid', $poll->Id );
            $query->Bind( 'offset', 0 );
            $query->Bind( 'limit', 25 );
            $res = $query->Execute();
            $ret = array();
            while ( $row = $res->FetchArray() ) {
                $option = New PollOption( $row );
                $option->CopyPollFrom( $poll );
                $ret[] = $option;
            }
            return $ret;
        }
    }

    class PollOption extends Satori {
        protected $mDbTableAlias = 'polloptions';
        
        public function __get( $key ) {
            if ( $key == 'Percentage' ) {
                if ( $this->Poll->Numvotes == 0 ) {
                    return 0;
                }   
                return $this->Numvotes / $this->Poll->Numvotes;
            }

            return parent::__get( $key );
        }
        public function CopyPollFrom( $value ) {
            $this->mRelations[ 'Poll' ]->CopyFrom( $value );
        }
        public function Vote( $user ) {
            if ( $user instanceof User ) {
                $user = $user->Id;
            }

            w_assert( ValidId( $user ), 'Invalid user id on PollOption::Vote!' );

            $vote = New PollVote();
            $vote->Userid = $user;
            $vote->Optionid = $this->Id;
            $vote->Pollid = $this->Pollid;
            $vote->Save();
        }
        public function OnVoteCreate() {
        }
        public function IsDeleted() {
            return $this->Delid > 0;
        }
        public function OnBeforeDelete() {
            $this->Delid = 1;
            $this->Save();

            return false;
        }
        public function UndoDelete() {
            $this->DelId = 0;
            $this->Save();
        }
        protected function Relations() {
            $this->Poll = $this->HasOne( 'Poll', 'Pollid' );
            $this->Votes = $this->HasMany( 'PollVoteFinder', 'FindByOption', $this );
        }
    }

?>
