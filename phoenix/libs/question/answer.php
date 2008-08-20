<?php

    global $libs;
    $libs->Load( 'question/question' );

    class AnswerFinder extends Finder {
        protected $mModel = 'Answer';
                
        public function Count() {
            $query = $this->mDb->Prepare(
            'SELECT
                COUNT(*) AS answerscount
            FROM
                :answers
            ');
            $query->BindTable( 'answers' );
            $res = $query->Execute();
            $row = $res->FetchArray();
            return ( int )$row[ 'answerscount' ];
        }
        
        public function FindAll( $offset = 0, $limit = 10000 ) {
            $prototype = New Answer();
            return $this->FindByPrototype( $prototype, $offset, $limit );
        }
        
        public function FindByUser( User $user, $offset = 0, $limit = 10000 ) {
            $query = $this->mDb->Prepare( "
                SELECT
                    *
                FROM
                    :answers 
                    CROSS JOIN :questions
                        ON `answer_questionid` = `question_id`
                WHERE
                    `answer_userid` = :userid
                ORDER BY RAND()
                LIMIT
                    :offset, :limit;" );

            $query->BindTable( 'answers', 'questions' );
            $query->Bind( 'userid', $user->Id );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );
            $res = $query->Execute();
            $ret = array();
            while ( $row = $res->FetchArray() ) {
                $answer = New Answer( $row );
                $answer->CopyQuestionFrom( New Question( $row ) );
                $ret[] = $answer;
            }
            return $ret;
        }
        public function DeleteByQuestion( Question $question ) {
            $query = $this->mDb->Query( '
                DELETE
                FROM
                    :answers
                WHERE
                    `answer_questionid` = :questionid
                ;' );
            $query->BindTable( 'answers' );
            $query->Bind( 'questionid', $question->Id );
            
            return $query->Execute->Impact();
        }
    }

    class Answer extends Satori {
        protected $mDbTableAlias = 'answers';
        
        public function CopyQuestionFrom( $value ) {
            $this->mRelations[ 'Question' ]->CopyFrom( $value );
        }

        public function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Question = $this->HasOne( 'Question', 'Questionid' );
        }
                
        public function LoadDefaults() {
            global $user;

            $this->Userid = $user->Id;
            $this->Created = NowDate();
        }
        protected function OnCreate() {
            global $user;
            
            ++$user->Count->Answers;
            $user->Count->Save();
        }
        protected function OnDelete() {
            global $user;
            
            --$user->Count->Answers;
            $user->Count->Save();
        }
    }
    
?>
