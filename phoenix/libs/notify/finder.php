<?php

    class NotificationFinder extends Finder {
        protected $mModel = 'Notification';
        protected $mCollectionClass = 'NotificationCollection';

        public function DeleteByEntity( $entity ) {
            $query = $this->mDb->Prepare( 
                'DELETE 
                FROM
                    :notify
                WHERE 
                    `notify_itemid` = :itemid AND 
                    `notify_typeid` IN :typeids;'
            );

            $query->BindTable( 'notify' );
            $query->Bind( 'itemid', $entity->Id );
            $query->Bind( 'typeids', Event_TypesByModel( strtoupper( get_class( $entity ) ) ) );

            return $query->Execute()->Impact();
        }
        public function FindByUserAfterId( $user, $id = 0, $offset = 0, $limit = 20 ) {
            if ( $user instanceof User ) {
                $userid = $user->Id;
            }
            else {
                w_assert( is_int( $user ) );
                $userid = $user;
            }
            w_assert( is_int( $id ) );

            $query = $this->mDb->Prepare(
                "SELECT
                    *
                FROM
                    :notify
                WHERE
                    `notify_touserid` = :userid
                    AND `notify_eventid` < :id
                ORDER BY
                    `notify_eventid` DESC
                LIMIT
                    :offset, :limit;" );
            $query->BindTable( 'notify' );
            $query->Bind( 'userid', $userid );
            $query->Bind( 'id', $id );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit + 6 );
        
            $res = $query->Execute();

            $ret = array();
            $i = 0;
            while ( $row = $res->FetchArray() ) {
                if ( $i < $limit ) {
                    $notif = New Notification( $row );
                    $ret[] = $notif;
                }
                ++$i;
            }

            return New Collection( $ret, $i );
        }
        public function FindByUser( User $user, $offset = 0, $limit = 20 ) {
            global $libs;
            $libs->Load( 'notify/collection' );

            $prototype = New Notification;
            $prototype->Touserid = $user->Id;
            $notifications = $this->FindByPrototype( $prototype, $offset, $limit + 6, array( 'Eventid', 'DESC' ) );
            $notifications->PreloadRelation( 'FromUser', 'User', 'Fromuserid' );
            $notifications->PreloadItems();
            $notifications->PreloadRelationsByType();
            return $notifications;
        }
        public function DeleteByCommentAndUser( Comment $comment, User $user ) {
            $query = $this->mDb->Prepare(
                "DELETE
                FROM
                    :notify
                USING
                    :notify 
                WHERE
                    `notify_typeid` = :typeid AND
                    `notify_itemid` = :commentid AND
                    `notify_touserid` = :userid;"
            );

            $query->BindTable( 'notify' );
            $query->Bind( 'typeid', EVENT_COMMENT_CREATED );
            $query->Bind( 'commentid', $comment->Id );
            $query->Bind( 'userid', $user->Id );

            return $query->Execute()->Impact();
        }
        public function FindByComment( Comment $comment ) {
            global $water; 

            $query = $this->mDb->Prepare( 
                "SELECT 
                    *
                FROM
                    :notify
                WHERE
                    `notify_typeid` = :typeid AND
                    `notify_itemid` = :commentid
                LIMIT 1;"
            );
        
            $query->BindTable( 'notify' );
            $query->Bind( 'typeid', EVENT_COMMENT_CREATED );
            $query->Bind( 'commentid', $comment->Id );
            
            $res = $query->Execute();
            if ( $res->Results() ) {
                $row = $res->FetchArray();
                $notif = New Notification( $row );

                return $notif;
            }
            else {
                $water->Warning( "No results for comment " . $comment->Id );
            }

            return false;
        }
        public function FindByRelation( FriendRelation $relation ) {
            global $water; 

            $query = $this->mDb->Prepare(
                "SELECT
                    *
                FROM
                    :notify
                WHERE
                    `notify_typeid` = :typeid AND
                    `notify_itemid` = :relationid
                LIMIT
                    1;"
            );

            $query->BindTable( 'notify' );
            $query->Bind( 'typeid', EVENT_FRIENDRELATION_CREATED );
            $query->Bind( 'relationid', $relation->Id );

            $res = $query->Execute();
            if ( $res->Results() ) {
                return New Notification( $res->FetchArray() );
            }
            else {
                $water->Warning( "No results for relation " . $relation->Id );
            }
                
            return false;
        }
        public function FindByImageTags( ImageTag $tag ) {
            global $water;
        
            $query = $this->mDb->Prepare(
                "SELECT
                    *
                FROM
                    :notify
                WHERE
                    `notify_typeid` = :typeid AND
                    `notify_itemid` = :tagid
                LIMIT
                    1;"
            );
             
            $query->BindTable( 'notify' );
            $query->Bind( 'typeid', EVENT_IMAGETAG_CREATED );
            $query->Bind( 'tagid', $tag->Id );
            
            $res = $query->Execute();
            if ( $res->Results() ) {
                return New Notification( $res->FetchArray() );
            }
            else {
                $water->Warning( "No results for image tag " . $tag->Id );
            }
                
            return false;
        }
    }

?>

