<?php
    class FavouriteFinder extends Finder {
        protected $mModel = 'Favourite';

        function FindByUserAndType( User $user, $type = false, $offset, $limit ) {
            $prototype = New Favourite();
            if ( $type !== false ) {
                $prototype->Typeid = $type;
            }
            $prototype->Userid = $user->Id;

            $data = $this->FindByPrototype( $prototype, $offset, $limit, array( 'Id', 'DESC' ), true );

            return $data;
        }
        function FindByUserAndEntity( User $user, $entity ) {
            $prototype = New Favourite();
            $prototype->Typeid = Type_FromObject( $entity );
            $prototype->Itemid = $entity->Id;
            $prototype->Userid = $user->Id;

            return $this->FindByPrototype( $prototype );
        }
        function FindByEntity( $entity ) {
            $prototype = New Favourite();
            $prototype->Typeid = Type_FromObject( $entity );
            $prototype->Itemid = $entity->Id;

            return $this->FindByPrototype( $prototype );
        }
    }

    class Favourite extends Satori {
        protected $mDbTableAlias = 'favourites';

        public function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            if ( $this->Exists() ) {
                $this->Item = $this->HasOne( Type_GetClass( $this->Typeid ), 'Itemid' );
            }
        }
        public function LoadDefaults() {
            global $user;

            $this->Userid = $user->Id;
        }
    }
?>
