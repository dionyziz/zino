<?php
    class FavouriteFinder extends Finder {
        protected $mModel = 'Favourite';

        public function FindByUserAndType( User $user, $type = false, $offset, $limit ) {
            $prototype = New Favourite();
            if ( $type !== false ) {
                $prototype->Typeid = $type;
            }
            $prototype->Userid = $user->Id;

            return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Id', 'DESC' ), true );
        }
        public function FindByUserAndEntity( User $user, $entity ) {
            $prototype = New Favourite();
            $prototype->Typeid = Type_FromObject( $entity );
            $prototype->Itemid = $entity->Id;
            $prototype->Userid = $user->Id;

            return $this->FindByPrototype( $prototype );
        }
        public function FindByEntity( $entity, $limit = 200 ) {
            $prototype = New Favourite();
            $prototype->Typeid = Type_FromObject( $entity );
            $prototype->Itemid = $entity->Id;

            return $this->FindByPrototype( $prototype, 0, $limit );
        }
        public function FindAll( $offset = 0, $limit = 25 ) {
            return parent::FindAll( $offset, $limit, array( 'Id', 'DESC' ) );
        }
        public function FindByIds( $ids ) {
            return parent::FindByIds( $ids );
        }
    }

    class Favourite extends Satori {
        protected $mDbTableAlias = 'favourites';

        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            if ( $this->Exists() ) {
                $this->Item = $this->HasOne( Type_GetClass( $this->Typeid ), 'Itemid' );
            }
        }
        protected function LoadDefaults() {
            global $user;

            $this->Userid = $user->Id;
            $this->Created = NowDate();
        }
        protected function OnCreate() {
            global $libs;
            
            $libs->Load( 'rabbit/event' );
            
            FireEvent( 'FavouriteCreated', $this );
        }
        protected function OnDelete() {
            global $libs;
            
            $libs->Load( 'rabbit/event' );
            
            FireEvent( 'FavouriteDeleted', $this );
        }
    }
?>
