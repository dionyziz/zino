<?php
	
    function Question_List() {
        global $db;
        global $questions;

        $sql = "SELECT * FROM `$questions` WHERE `question_delid` = '0';";

        return $db->Query( $sql )->ToObjectArray( 'Question' );
    }

    class Question extends Satori {
        protected $mId;
        protected $mText;
        protected $mUserId;
        protected $mUserIp;
        protected $mDate;
        protected $mPermissions;
        protected $mDelId;
        protected $mAnswersTable;

        private function DeleteAnswers() {
			$sql = "UPDATE `" . $this->mAnswersTable . "` SET `profile_delid` = '1' WHERE `profile_questionid` = '" . $this->Id . "';";
			$this->mDb->Query( $sql );
        }
        public function Delete( User $admin ) {  
            global $water;
            global $user;

            if ( is_null( $admin ) ) {
                $admin = user;
            }
            if ( !$admin->CanModifyCategories() ) {
                return false;
            }
        
            $this->DelId = 1;
            if ( !$this->Save() ) {
                $water->Warning( "Failed deleting question" );
                return false;
            }

            return $this->DeleteAnswers();
        }
        protected function LoadDefaults() {
            $this->Date     = NowDate();
            $this->UserIp   = UserIp();
        }
		public function Question( $construct = false ) {
            global $db;
            global $questions;
            global $profileanswers;
    
            $this->mDb              = $db;
            $this->mDbTable         = $questions;
            $this->mAnswersTable    = $profileanswers;

            $this->SetFields( array(
                'profileq_id' => 'Id',
                'profileq_question' => 'Text',
                'profileq_adminid'  => 'UserId',
                'profileq_userip'   => 'UserIp',
                'profileq_created'  => 'Date',
                'profileq_delid'    => 'DelId'
            ) );

            $this->Satori( $construct );
		}
    }

?>
