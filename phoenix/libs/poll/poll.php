<?php
    global $libs;

    $libs->Load( 'poll/option' );
    $libs->Load( 'url' );

    class PollFinder extends Finder {
        protected $mModel = 'Poll';

        public function FindByUser( $user, $offset = 0, $limit = 25 ) {
            $poll = New Poll();
            $poll->Userid = $user->Id;
            $poll->Delid = 0;

            return $this->FindByPrototype( $poll, $offset, $limit, array( 'Id', 'DESC' ) );
        }
        public function FindByUserAndUrl( $user, $url, $offset = 0, $limit = 25 ) {
            $prototype = New Poll();
            $prototype->Userid = $user->Id;
            $prototype->Url = $url;
            $prototype->Delid = 0;

            return $this->FindByPrototype( $prototype );
        }
        public function Count() {
            $query = $this->mDb->Prepare(
                'SELECT
                    COUNT( * ) AS numpolls
                FROM
                    :polls
                WHERE
                    `poll_delid`=0'
            );
            $query->BindTable( 'polls' );
            $res = $query->Execute();
            $row = $res->FetchArray();
            $numpolls = $row[ 'numpolls' ];

            return $numpolls;
        }
        public function FindAll( $offset = 0, $limit = 20 ) {
            $prototype = New Poll();
            $prototype->Delid = 0;

            return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Id', 'DESC' ) );
        }
    }

    class Poll extends Satori {
        protected $mDbTableAlias = 'polls';

        public function __get( $key ) {
            if ( $key == 'Title' ) {
                return $this->Question;
            }

            return parent::__get( $key );
        }
        public function OnVoteCreate() {
            ++$this->Numvotes;
            $this->Save();
        }
        public function OnVoteDelete() {
            --$this->Numvotes;
            $this->Save();
        }
        public function OnCommentCreate() {
            ++$this->Numcomments;
            $this->Save();
        }
        public function OnCommentDelete() {
            --$this->Numcomments;
            $this->Save();
        }
        public function CreateOption( $text ) {
            $option = New PollOption();
            $option->Text = $text;
            $option->Pollid = $this->Id;
            $option->Save();

            $this->Options[] = $option;
            
            return $option;
        }
        public function OnBeforeCreate() {
            $url = URL_Format( $this->Question );
            $length = strlen( $url );
            $finder = New PollFinder();
            $exists = true;
            while ( $exists ) {
                $offset = 0;
                $exists = false;
                do {
                    $someOfTheRest = $finder->FindByUser( $this->User, $offset, 100 );
                    foreach ( $someOfTheRest as $p ) {
                        if ( strtolower( $p->Url ) == strtolower( $url ) ) {
                            $exists = true;
                            if ( $length < 255 ) {
                                $url .= '_';
                                ++$length;
                            }
                            else {
                                $url[ rand( 0, $length - 1 ) ] = '_';
                            }
                            break;
                        }
                    }
                    $offset += 100;
                } while ( count( $someOfTheRest ) && !$exists );
            }
            $this->Url = $url;
        }
        public function IsDeleted() {
            return $this->Delid > 0;
        }
        public function OnBeforeDelete() {
            global $user;
            global $libs;
            
            $libs->Load( 'adminpanel/adminaction' );
                        
            if ( $user->id != $this->userid ) {
                $adminaction = new AdminAction();
                $adminaction->saveAdminAction( $user->id, UserIp(), OPERATION_DELETE, TYPE_POLL, $this->id );
            }
            
            $this->Delid = 1;
            $this->Save();

            $this->OnDelete();

            return false;
        }
        protected function OnCreate() {
            global $libs;
            $libs->Load( 'event' );

            ++$this->User->Count->Polls;
            $this->User->Count->Save();

            $event = New Event();
            $event->Typeid = EVENT_POLL_CREATED;
            $event->Itemid = $this->Id;
            $event->Userid = $this->Userid;
            $event->Save();

            Sequence_Increment( SEQUENCE_POLL );
        }
        protected function OnDelete() {
            global $libs;

            --$this->User->Count->Polls;
            $this->User->Count->Save();

            $libs->Load( 'comment' );
            $libs->Load( 'event' );

            $finder = New CommentFinder();
            $finder->DeleteByEntity( $this );

            $finder = New EventFinder();
            $finder->DeleteByEntity( $this );

            Sequence_Increment( SEQUENCE_POLL );
        }
        public function UndoDelete() {
            $this->Delid = 0;
            $this->Save();

            Sequence_Increment( SEQUENCE_POLL );
        }
        protected function LoadDefaults() {
            $this->Created = NowDate();
        }
        public function CopyUserFrom( $value ) {
            $this->mRelations[ 'User' ]->CopyFrom( $value );
        }
        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Options = $this->HasMany( 'PollOptionFinder', 'FindByPoll', $this );
        }    
    }

?>
