<?php

    class NotificationCollection extends Collection {
        public function PreloadFromUserAvatars() {
            $avatarids = array();
            foreach ( $this as $notif ) {
                $avatarids[] = $notif->FromUser->Avatarid;
            }
            $finder = New ImageFinder();
            $avatars = $finder->FindByIds( $avatarids );
            $avatarsById = array();
            foreach ( $avatars as $avatar ) {
                $avatarsById[ $avatar->Id ] = $avatar;
            }
            foreach ( $this as $i => $notif ) {
                if ( !isset( $avatarsById[ $notif->FromUser->Avatarid ] ) ) {
                    continue;
                }
                $notif->FromUser->CopyRelationFrom( 'Avatar', $avatarsById[ $notif->FromUser->Avatarid ] );
                $this[ $i ] = $notif;
            }
        }
        public function PreloadItems() {
            $itemidsByType = array();
            foreach ( $this as $notif ) {
                $itemidsByType[ $notif->Typeid ][] = $notif->Itemid;
            }

            $objectsById = array();
            foreach ( $itemidsByType as $type => $itemids ) {
                $model = Event_ModelByType( $type );
                $finderClass = $model . 'Finder'; // hopefully
                $finder = New $finderClass(); // MAGIC!
                $objects = $finder->FindByIds( $itemids ); 
                foreach ( $objects as $object ) {
                    w_assert( $object->Exists(), 'Object of type ' . get_class( $object ) . ' does not exist. Id: ' . $object->Id );
                    $objectsById[ $type ][ $object->Id ] = $object;
                }
            }
            
            foreach ( $this as $i => $notif ) {
                if ( !isset( $objectsById[ $notif->Typeid ][ $notif->Itemid ] ) ) { // failed finding it from db
                    continue;
                }
                $this[ $i ]->CopyRelationFrom( 'Item', $objectsById[ $notif->Typeid ][ $notif->Itemid ] );
            }
        }
        public function PreloadItemRelations() {
            $itemsByType = array();
            foreach ( $this as $notif ) {
                $itemsByType[ $notif->Typeid ][] = $notif->Item;
            }
            $itemsById = array();
            foreach ( $itemsByType as $type => $items ) {
                switch ( $type ) {
                    case EVENT_COMMENT_CREATED:
                        global $libs;
                        $libs->Load( 'bulk' );

                        $comments = New CommentCollection( $items );
                        $comments->PreloadRelation( 'User' );
                        $comments->PreloadUserAvatars();
                        $comments->PreloadBulk();
                        $comments->PreloadItems();
                        $items = $comments->ToArrayById();
                        break;
                    case EVENT_FRIENDRELATION_CREATED:
                        $relations = New FriendRelationCollection( $items );
                        $relations->PreloadRelation( 'User' );
                        // $relations->PreloadUserAvatars();
                        $items = $relations->ToArrayById();
                        break;
                }
                foreach ( $items as $item ) {
                    $itemsById[ $type ][ $item->Id ] = $item;
                }
            }
            global $water;
            foreach ( $this as $i => $notif ) {
                if ( !isset( $itemsById[ $notif->Typeid ][ $notif->Itemid ] ) ) {
                    $water->Trace( 'Notify PreloadItemRelations miss ' . $notif->Typeid . ' ' . $notif->Itemid );
                    continue;
                }
                $this[ $i ]->CopyRelationFrom( 'Item', $itemsById[ $notif->Typeid ][ $notif->Itemid ] );
            }
        }
    }

?>
