<?php
    global $libs;

    define( 'FRIENDS_NONE', 0 );
    define( 'FRIENDS_A_HAS_B', 1 );
    define( 'FRIENDS_B_HAS_A', 2 );
    define( 'FRIENDS_BOTH', FRIENDS_A_HAS_B | FRIENDS_B_HAS_A );

    $libs->Load( 'relation/type' );

    class FriendRelationFinder extends Finder {
        protected $mModel = 'FriendRelation';

        public function FindByUser( User $user, $offset = 0, $limit = 10000 ) {
            w_assert( $user instanceof User );
            w_assert( $user->Exists() );
            
            $query = $this->mDb->Prepare(
                'SELECT
                    *
                FROM
                    :relations
                    LEFT JOIN :users ON
                        `relation_friendid` = `user_id`
                    LEFT JOIN :userprofiles ON
                        `user_id` = `profile_userid`
                    LEFT JOIN :images ON
                        `user_avatarid` = `image_id`
                WHERE
                    `relation_userid` = :userid
                ORDER BY
                    `relation_id` DESC
                LIMIT
                    :offset, :limit
                ;' );

            $query->BindTable( 'relations', 'users', 'images' );
            $query->Bind( 'userid', $user->Id );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );

            $res = $query->Execute();
            $ret = array();
            while ( $row = $res->FetchArray() ) {
                $relation = New FriendRelation( $row );
                $friend = New User( $row );
                $friend->CopyProfileFrom( New UserProfile( $row ) );
                $friend->CopyAvatarFrom( New Image( $row ) );
                $relation->CopyFriendFrom( $friend );
                $ret[] = $relation;
            }

            return $ret;
        }
        public function FindMutualByUser( $user, $offset = 0, $limit = 10000 ) {
            global $water;
        
            // Cool Query by kostis90gr and dionyziz
            $query = $this->mDb->Prepare( '
                SELECT 
                    `user_name`, `user_gender`
                FROM
                    :relations AS a
                    CROSS JOIN :relations AS b ON
                        b.relation_friendid = :userid AND
                        b.relation_userid = a.relation_friendid
                    LEFT JOIN :users ON
                        a.relation_friendid = `user_id`
                WHERE 
                    a.relation_userid = :userid
                ORDER BY
                    `user_name` ASC
                LIMIT
                    :offset, :limit
                ;' );
                
            $query->BindTable( 'relations', 'users' );
            $query->Bind( 'userid', $user->Id );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );
            
            $res = $query->Execute();
            $ret = array();
            while( $row = $res->FetchArray() ) {
                //$ret[] = $row[ 'user_name' ];
				$ret[] = $row;
            }
            
            $water->Trace( count( $ret ) );
            
            return $ret;
        }
        public function FindByFriend( $friend, $offset = 0, $limit = 10000 ) {
            $prototype = New FriendRelation();
            $prototype->Friendid = $friend->Id;

            return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Id', 'DESC' ) );
        }
        public function IsFriend( User $a, User $b ) {
            $status = FRIENDS_NONE;

            $prototype = New FriendRelation();
            $prototype->Userid = $a->Id;
            $prototype->Friendid = $b->Id;
            if ( $this->FindByPrototype( $prototype ) !== false ) {
                $status |= FRIENDS_A_HAS_B;
            }
            
            $prototype = New FriendRelation();
            $prototype->Userid = $b->Id;
            $prototype->Friendid = $a->Id;
            if ( $this->FindByPrototype( $prototype ) !== false ) {
                $status |= FRIENDS_B_HAS_A;
            }

            return $status;
        }
        public function FindFriendship( User $a, User $b ) {
            $prototype = New FriendRelation();
            $prototype->Userid = $a->Id;
            $prototype->Friendid = $b->Id;
            
            return $this->FindByPrototype( $prototype );
        }
    }

    class FriendRelation extends Satori {
        protected $mDbTableAlias = 'relations';

        public function __get( $key ) {
            if ( $key == 'Type' ) {
                return $this->RelationType->Text;
            }

            return parent::__get( $key );
        }
        public function CopyFriendFrom( $value ) {
            $this->mRelations[ 'Friend' ]->CopyFrom( $value );
        }
        protected function OnCreate() {
            global $libs;

            $libs->Load( 'event' );

            $event = New Event();
            $event->Typeid = EVENT_FRIENDRELATION_CREATED;
            $event->Itemid = $this->Id;
            $event->Userid = $this->Userid;
            $event->Save();

            ++$this->User->Count->Relations;
            $this->User->Count->Save();
        }
        /*
        protected function OnUpdate() {
            global $libs;
            
            $libs->Load( 'event' );

            $event = New Event();
            $event->Typeid = EVENT_FRIENDRELATION_UPDATED;
            $event->Itemid = $this->Id;
            $event->Userid = $this->Userid;
            $event->Save();
        }
        */
        protected function OnDelete() {
            global $libs;
            $libs->Load( 'event' );
            
            --$this->User->Count->Relations;
            $this->User->Count->Save();

            /*
            $finder = New NotificationFinder();
            $notif = $finder->FindByRelation( $this );

            if ( !is_object( $notif ) ) {
                return;
            }
            
            $notif->Delete();
            */

            $finder = New EventFinder();
            $finder->DeleteByEntity( $this );
        }
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Friend = $this->HasOne( 'User', 'Friendid' );
            $this->RelationType = $this->HasOne( 'RelationType', 'Typeid' );
        }
        public function LoadDefaults() {
            global $user;

            $this->Userid = $user->Id;
            $this->Created = NowDate();
        }
    }

?>
