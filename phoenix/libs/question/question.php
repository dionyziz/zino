<?php

    class QuestionFinder extends Finder {
        protected $mModel = 'Question';
                
        public function Count() {
            $query = $this->mDb->Prepare(
            'SELECT
                COUNT(*) AS questionscount
            FROM
                :questions
            WHERE questions_delid = :delid;
            ');
            $query->BindTable( 'questions' );
            $query->Bind( 'delid', 0 );
            $res = $query->Execute();
            $row = $res->FetchArray();
            return ( int )$row[ 'questionscount' ];
        }
        
        public function FindAll( $offset = 0, $limit = 10000 ) {
            $prototype = New Question();
            $prototype->Delid = 0;
            return $this->FindByPrototype( $prototype, $offset, $limit );
        }
        public function FindRandomByUser( User $user ) { 
            // This query is awesome, by dionyziz
            // OPTIMIZED: This query is awesome *now*, by abresas :)
            // FIXED: This query is aweseome *now* that works, by kostis90gr :-D
            $query = $this->mDb->Prepare(
                'SELECT 
                    *
                FROM 
                    :questions
                    LEFT JOIN :answers ON 
                        `question_id` = `answer_questionid`
                WHERE 
                    `question_id` IN (
                        SELECT 
                            `question_id`
                        FROM 
                            :questions
                            LEFT JOIN :answers ON 
                                `question_id` = `answer_questionid` AND 
                                `answer_userid` = :userid
                        WHERE 
                            answer_id IS NULL
                    )
                ORDER BY RAND()
                LIMIT 1 ;
            ');
            // It would be fine if we could use a LIMIT within an IN statement but this feature is not yet implemented in MySQL 5.0

            $query->BindTable( 'questions' );
            $query->BindTable( 'answers' );
            $query->Bind( 'userid', $user->Id );
            $query->Bind( 'limit', 1 );
            
            $q = $this->FindBySqlResource( $query->Execute() );

            if ( !empty( $q ) ) {
                return $q[ 0 ];
            }

            return false;
        }
        
        public function FindNewQuestion( User $user, $exp = 1.2 ) {
            if( pow( $exp, $user->Count->Comments ) > $user->Count->Answers ) {
                return $this->FindRandomByUser( $user );
            }
            return false;
        }
        
    }

    class Question extends Satori {
        protected $mDbTableAlias = 'questions';
        
        public function IsDeleted() {
            return $this->Delid > 0;
        }
        public function OnDelete() {
            global $libs;
            $libs->Load( 'question/answer' );

            $finder = New AnswerFinder();
            $finder->DeleteByQuestion( $this );
        }
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
        }
        public function LoadDefaults() {
            global $user;

            $this->Userid = $user->Id;
            $this->Created = NowDate();
        }        
    }
    
?>
