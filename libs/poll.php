<?php

    class PollOption extends Satori {
        protected $mId;
        protected $mText;
        protected $mPollId;

        public function PollOption( $construct = false ) {
            global $db;
            global $polloptions;

            $this->mDb      = $db;
            $this->mDbTable = $polloptions;

            if ( !is_array( $construct ) && ValidId( $construct ) ) {
                $sql = "SELECT
                            *
                        FROM
                            `" . $this->mDbTable . "`
                        WHERE
                            `polloption_id` = '$construct'
                        LIMIT 1;";
                
                $construct = $this->mDb->Query( $sql )->FetchArray();
            }

            $this->SetFields( array(
                'polloption_id'     => 'Id',
                'polloption_text'   => 'Text',
                'polloption_pollid' => 'PollId'
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

        public function GetOptions() {
            global $polloptions;

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
                    $this->mOptions[] = new Option( $row );
                }
            }

            return $this->mOptions;
        }
        public function SetTextOptions( $options ) {
            w_assert( is_array( $options ) );

            $this->mTextOptions = $options;
        }
        public function Save() {
            $isnew = !$this->Exists();

            parent::Save();

            if ( $isnew && $this->mTextOptions !== false ) {
                foreach ( $this->mTextOptions as $optiontext ) {
                    $option         = new Option();
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
            $this->ExpireDate   = gmdate("Y-m-d H:i:s", time() + 7 * 24 * 60 * 60 ); // expire in one week by default
        }
        public function Poll( $construct = false ) {
            global $db;
            global $polls;

            $this->mDb      = $db;
            $this->mDbTable = $polls;

            if ( !is_array( $construct ) && ValidId( $construct ) ) {
                // sql
                $sql = "SELECT
                            *
                        FROM
                            `" . $this->mDbTable . "`
                        WHERE
                            `poll_id` = '$construct'
                        ;";

                $res = $this->mDb->Query( $sql );
                $construct = $res->FetchArray();
            }

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
