<?php
    global $libs;

    $libs->Load( 'poll/option' );
    $libs->Load( 'url' );
	$libs->Load( 'poll/frontpage' );

    class PollFinder extends Finder {
        protected $mModel = 'Poll';

        public function FindByUser( $user, $offset = 0, $limit = 25, $deleted = false ) {
            $poll = New Poll();
            $poll->Userid = $user->Id;
            if ( !$deleted ) {
                $poll->Delid = 0;
            }

            return $this->FindByPrototype( $poll, $offset, $limit, array( 'Id', 'DESC' ) );
        }
		public function FindByUserId( $userid, $offset = 0, $limit = 25 ) {
            $poll = New Poll();
            $poll->Userid = $userid;
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
		public function FindFrontpageLatest( $offset = 0, $limit = 4 ) {
            $finder = New FrontpagePollFinder();
            $found = $finder->FindLatest( $offset, $limit );
            $pollids = array();
            foreach ( $found as $frontpagepoll ) {
                $pollids[] = $frontpagepoll->Pollid;
            }
            if ( !count( $pollids ) ) {
                return array();
            }
            $query = $this->mDb->Prepare(
                'SELECT
                    *
                FROM
                    :polls 
                    LEFT JOIN :users ON
                        `poll_userid` = `user_id`
                WHERE
                    `poll_id` IN :pollids'
            );
            $query->BindTable( 'polls', 'users' );
            $query->Bind( 'pollids', $pollids );
            
            $res = $query->Execute();
            $polls = array();
            while ( $row = $res->FetchArray() ) {
                $poll = New Poll( $row );
                $poll->CopyUserFrom( New User( $row ) );
                $polls[] = $poll;
            }

            $ret = array();
            foreach ( $polls as $poll ) {
                $ret[ $poll->Id ] = $poll;
            }
            krsort( $ret );

            return $ret;
        }
        public function FindByIds( $ids ) {
            return parent::FindByIds( $ids );
        }
        public function FindUserRelated( $user ) {
            global $libs;
            $libs->Load( 'research/spot' );

            $ids = Spot::GetPolls( $user );
            if ( $ids === false ) {
                return $ids;
            }
            $polls = $this->FindByIds( $ids );
            $polls->PreloadRelation( 'User' );
            return $polls;
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
            // ++$this->Numvotes;
            $this->Save();
        }
        public function OnVoteDelete() {
            // --$this->Numvotes;
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
            global $libs;
            
            $libs->Load( 'url' );
            
            $url = URL_Format( $this->Question );
            $length = strlen( $url );
            $finder = New PollFinder();
            $exists = true;
            while ( $exists ) {
                $offset = 0;
                $exists = false;
                do {
                    $someOfTheRest = $finder->FindByUser( $this->User, $offset, 100, true );
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
                $adminaction = New AdminAction();
                $adminaction->saveAdminAction( $user->id, UserIp(), OPERATION_DELETE, TYPE_POLL, $this->id );
            }
            
            $this->Delid = 1;
            $this->Save();

            $this->OnDelete();

            return false;
        }
        protected function OnCreate() {
            global $libs;
            
			$this->MakeFrontpage();

            Sequence_Increment( SEQUENCE_POLL );
        }
		protected function MakeFrontpage() {
			$frontpage = New FrontpagePoll( $this->Userid );
			
            if ( !$frontpage->Exists() ) {
                $frontpage = New FrontpagePoll();
                $frontpage->Userid = $this->Userid;
            }
            $frontpage->Pollid = $this->Id;
            $frontpage->Save();
		}
        protected function OnDelete() {
            global $libs;

            $libs->Load( 'comment' );

            $finder = New CommentFinder();
            $finder->DeleteByEntity( $this );
			
			$frontpage = New FrontpagePoll( $this->Userid );
            if ( $frontpage->Exists() ) {
                if ( $frontpage->Pollid == $this->Id ) {
                    $finder = New PollFinder();
                    $oldpoll = $finder->FindByUserId( $this->Userid, 0, 1 );
                    if ( count( $oldpoll ) === 0 ) {
                        $frontpage->Delete();
                    }
                    else {
                        $oldpoll = $oldpoll[ 0 ];
                        $frontpage->Pollid = $oldpoll->Id;
                        $frontpage->Save();
                    }
                }
            }

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
