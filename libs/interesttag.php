<?php
    
    function InterestTag_List( $user ) {
        global $db;
        global $interesttags;

        $sql = "SELECT
                    *
                FROM
                    `$interesttags`
                WHERE
                    `interesttag_userid` = '{ $user->Id() }'
                ;";

        $res = $db->Query( $sql );
        $ret = array();
        while ( $row = $res->FetchArray() ) {
            $ret[] = new InterestTag( $row );
        }
    
        return $ret;
    }

    function InterestTag_Clear( $user ) {
        global $db;
        global $interesttags;

        $sql = "DELETE 
                FROM 
                    `$interesttags` 
                WHERE 
                    `interesttag_userid` = '{ $user->Id() }'
                ;";

        return $db->Query( $sql );
    }

    class InterestTag extends Satori {
        protected   $mId;
        protected   $mUserId;
        protected   $mText;
        protected   $mNextId;
        private     $mUser;
        private     $mNext;
        private     $mPrevious;

        public function GetNext() {
            if ( $this->mNext === false ) {
                $this->mNext = new InterestTag( $this->NextId );
            }
            return $this->mNext;
        }
        public function GetPrevious() {
            if ( $this->mPrevious === false ) {
                $sql = "SELECT
                            *
                        FROM
                            `$this->mDbTable`
                        WHERE
                            `interesttag_next` = '{ $this->Id }'
                        LIMIT 1;";

                $this->mPrevious = new InterestTag( $this->mDb->Query( $sql )->FetchArray() );
            }
            return $this->mPrevious;
        }
        public function MoveAfter( $target ) {
            $this->MoveBefore( $target->Next );
        }
        public function MoveBefore( $target ) {
            if ( $this->Previous->Exists() ) {
                $this->Previous->NextId = $this->NextId;
                $this->Previous->Save();
            }

            $this->NextId = $target->Id;
            $this->Save();

            if ( $target->Previous->Exists() ) {
                $target->Previous->NextId = $this->Id;
                $target->Previous->Save();
            }
        }
        public function Save() {
            $existed    = $this->Exists();
            $change     = Satori::Save();

            if ( !$existed && $change->Impact() ) {
                $sql = "UPDATE
                            `$this->mDbTable`
                        SET
                            `interesttag_next` = '{ $this->Id }'
                        WHERE
                            `interesttag_userid` = '{ $this->UserId }' AND
                            `interesttag_next`  = '-1'
                        LIMIT
                            1
                        ;";

                $change = $this->mDb->Query( $sql );
            }

            return $change;
        }
        public function LoadDefaults() {
            $this->NextId = -1;
        }
        public function InterestTag( $construct = false ) {
            global $db;
            global $interesttags;

            $this->mDb      = $db;
            $this->mDbTable = $interesttags;

            $this->SetFields( array(
                'interesttag_id'        => 'Id',
                'interesttag_userid'    => 'UserId',
                'interesttag_text'      => 'Text',
                'interesttag_next'      => 'NextId'
            ) );

            $this->Satori( $construct );

            $this->mNext        = false;
            $this->mPrevious    = false;
        }
    }

?>
