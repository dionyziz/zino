<?php
    
    function Poll_GetByUser( $user ) {
        global $polls;
        global $db;

        $sql = "SELECT
                    *
                FROM
                    `$polls`
                WHERE
                    `poll_userid` = '" . $user->Id() . "' AND
                    `poll_delid` = '0'
                LIMIT 1;";

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

        public function PollOption( $construct = false ) {
            global $db;
            global $polloptions;

            $this->mDb      = $db;
            $this->mDbTable = $polloptions;

            $this->SetFields( array(
                'polloption_id'         => 'Id',
                'polloption_text'       => 'Text',
                'polloption_pollid'     => 'PollId',
                'polloption_numvotes'   => 'NumVotes'
            ) );

            $this->Satori( $construct );
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
            }
            
            return $change->Impact();
        }
        public function PollVote( $construct = false ) {
            global $db;
            global $votes;

            $this->mDb      = $db;
            $this->mDbTable = $votes;
            
            $this->SetFields( array(
                'vote_id'       => 'Id',
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
        private     $mOptions;
        private     $mTextOptions;

        public function UserHasVoted( $user ) {
            global $polloptions;
            global $votes;

            $sql = "SELECT
                        COUNT( * )
                    FROM
                        `$polloptions` LEFT JOIN `$votes`
                            ON `polloption_id` = `vote_optionid`
                    WHERE
                        `polloption_pollid` = '" . $this->Id . "' AND
                        `vote_userid`       = '" . $user->Id() . "'
                    LIMIT 1;";
            
            return $this->mDb->Query( $sql )->Results();
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
        public function GetOptions() {
            global $polloptions;
            global $votes;

            if ( $this->mOptions === false ) {
                $sql = "SELECT
                            *
                        FROM
                            `$polloptions`
                        WHERE
                            `polloption_pollid` = '" . $this->Id . "'
                        ;";

                $res = $db->Query( $sql );
                $this->mOptions = array();
                while ( $row = $res->FetchArray() ) {
                    $this->mOptions[] = new PollOption( $row );
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
                'poll_delid'    => 'DelId'
            ) );

            $this->Satori( $construct );
            
            $this->mOptions     = false;
        }
    }

?>
