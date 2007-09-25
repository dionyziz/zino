<?php
    
    function Poll_GetByUser( $user, $limit = 0 ) {
        global $polls;
        global $db;

        $sql = "SELECT
                    *
                FROM
                    `$polls`
                WHERE
                    `poll_userid` = '" . $user->Id() . "' AND
                    `poll_delid` = '0'
                ";
        
        if ( $limit > 0 ) {
            $sql .= "LIMIT $limit";
        }

        $res = $db->Query( $sql );
        
        $ret = array();
        while ( $row = $res->FetchArray() ) {
            $ret[] = new Poll( $row );
        }

        return $ret;
    }

    class PollOption extends Satori {
        protected $mId;
        protected $mText;
        protected $mPollId;
        protected $mPoll;
        protected $mNumVotes;
        protected $mPercentage;
        protected $mDelId;

        protected function SetPoll( $poll ) {
            $this->mPoll = $poll;
        }
        public function GetPoll() {
            if ( $this->mPoll === false ) {
                $this->mPoll = new Poll( $this->PollId );
            }

            return $this->mPoll;
        }
        public function SetPollId( $value ) {
            $this->mPollId = $value;
            $this->Poll = new Poll( $this->PollId );
        }
        protected function SetPercentage( $percentage ) {
            $this->mPercentage = $percentage;
        }
        public function GetPercentage() {
            return $this->mPercentage;
        }
        protected function LoadDefaults() {
            $this->NumVotes = 0;
        }
        public function Delete() {
            $this->mDelId = 1;
            $this->Save();
            
            // remove this option's votes from poll votes
            $this->Poll->NumVotes -= $this->NumVotes;
            $this->Poll->Save();
        }
        public function UndoDelete() {
            $this->mDelId = 0;
            $this->Save();

            // add this option's votes to poll votes
            $this->Poll->NumVotes += $this->NumVotes;
            $this->Poll->Save();
        }
        public function PollOption( $construct = false, $poll = false ) {
            global $db;
            global $polloptions;

            $this->mDb      = $db;
            $this->mDbTable = $polloptions;

            $this->SetFields( array(
                'polloption_id'         => 'Id',
                'polloption_text'       => 'Text',
                'polloption_pollid'     => 'PollId',
                'polloption_numvotes'   => 'NumVotes',
                'polloption_delid'      => 'DelId'
            ) );

            $this->Satori( $construct );

            $this->Poll         = $poll;
            $this->Percentage   = ( $this->Poll->NumVotes > 0 ) ? ( $this->NumVotes / $this->Poll->NumVotes * 100 ) : 0;
        }
    }

    class PollVote extends Satori {
        protected $mId;
        protected $mUserId;
        protected $mOptionId;
        protected $mDate;

        public function LoadDefaults() {
            $this->Date = NowDate();
        }
        public function Save() {
            $isnew = !$this->Exists();
            $change = parent::Save();

            if ( $change->Impact() && $isnew ) {
                $option = new PollOption( $this->OptionId );
                ++$option->NumVotes;
                $option->Save();

                $poll = $option->Poll;
                ++$poll->NumVotes;
                $poll->Save();
            }
            
            return $change->Impact();
        }
        public function PollVote( $construct = false ) {
            global $db;
            global $votes;

            w_assert( $construct === false || is_array( $construct ), $construct . " is not a valid PollVote constructor" );
        
            $this->mDb      = $db;
            $this->mDbTable = $votes;
            
            $this->SetFields( array(
                'vote_userid'   => 'UserId',
                'vote_optionid' => 'OptionId',
                'vote_date'     => 'Date'
            ) );

            $this->Satori( $construct );
        }
    }

    class Poll extends Satori {
        protected   $mId;
        protected   $mQuestion;
        protected   $mUserId;
        protected   $mExpireDate;
        protected   $mCreated;
        protected   $mDelId;
        protected   $mNumVotes;
        private     $mOptions;
        private     $mTextOptions;
        private     $mHasVoted;

        public function UserHasVoted( $user ) {
            global $polloptions;
            global $votes;

            if ( !isset( $this->mHasVoted[ $user->Id() ] ) ) {
                $sql = "SELECT
                            *
                        FROM
                            `$polloptions` RIGHT JOIN `$votes`
                                ON `polloption_id` = `vote_optionid`
                        WHERE
                            `polloption_pollid` = '" . $this->Id . "' AND
                            `polloption_delid`  = '0' AND
                            `vote_userid`       = '" . $user->Id() . "'
                        LIMIT 1;";
                
                $this->mHasVoted[ $user->Id() ] = $this->mDb->Query( $sql )->Results();
            }

            return $this->mHasVoted[ $user->Id() ];
        }
        public function HasExpired() {
            if ( $this->ExpireDate == '0000-00-00 00:00:00' ) {
                return false;
            }

            $sql = "SELECT
                        `poll_expire` > NOW()
                    FROM
                        `" . $this->mDbTable . "`
                    WHERE
                        `poll_id` = '" . $this->Id . "'
                    LIMIT 1;";

            $fetched = $db->Query( $sql )->FetchArray();

            return $fetced[ 0 ];
        }
        public function Stop() {
            $this->ExpireDate   = NowDate();
            return $this->Save();
        }
        public function Vote( $userid, $optionid ) {
            $vote           = new PollVote();
            $vote->PollId   = $this->Id;
            $vote->OptionId = $optionid;
            $vote->UserId   = $userid;
            $vote->Save();

            $this->mHasVoted[ $userid ] = true;
        }
        public function GetOptions() {
            global $polloptions;
            global $votes;

            if ( $this->mOptions === false ) {
                $sql = "SELECT
                            *
                        FROM
                            `$polloptions`
                        WHERE
                            `polloption_pollid` = '" . $this->Id . "' AND
                            `polloption_delid` = '0'
                        ;";

                $res = $this->mDb->Query( $sql );
                $this->mOptions = array();
                while ( $row = $res->FetchArray() ) {
                    $this->mOptions[]   = new PollOption( $row, $this );
                }
            }

            return $this->mOptions;
        }
        public function SetTextOptions( $options ) {
            w_assert( is_array( $options ) );

            $this->mTextOptions = $options;
        }
        public function Delete() {
            $this->DelId = 1;

            return $this->Save();
        }
        public function Save() {
            $isnew = !$this->Exists();

            parent::Save();

            if ( $isnew && $this->mTextOptions !== false ) {
                foreach ( $this->mTextOptions as $optiontext ) {
                    $option         = new PollOption();
                    $option->PollId = $this->Id;
                    $option->Text   = $optiontext;
                    $option->Save();

                    $this->mOptions[] = $option;
                }
            }
        }
        public function LoadDefaults() {
            $this->mTextOptions = false;
            $this->Created      = NowDate();
            $this->ExpireDate   = '0000-00-00 00:00:00'; // default is never expire
            $this->NumVotes     = 0;
        }
        public function Poll( $construct = false ) {
            global $db;
            global $polls;

            $this->mDb      = $db;
            $this->mDbTable = $polls;

            $this->SetFields( array(
                'poll_id'       => 'Id',
                'poll_question' => 'Question',
                'poll_userid'   => 'UserId',
                'poll_expire'   => 'ExpireDate',
                'poll_created'  => 'Created',
                'poll_delid'    => 'DelId',
                'poll_numvotes' => 'NumVotes'
            ) );

            $this->Satori( $construct );
            
            $this->mOptions     = false;
            $this->mHasVoted    = array();
        }
    }

?>
