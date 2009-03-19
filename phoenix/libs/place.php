<?php
    class PlaceFinder extends Finder {
        protected $mModel = 'Place';
        
        public function FindAll( $offset = 0, $limit = 10000 ) {
            $prototype = New Place();
            $prototype->Delid = 0;

            return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Name', 'ASC' ) );
        }
        public function FindByAd( Ad $ad, $offset = 0, $limit = 10000 ) {
            $adplacesfinder = New AdPlacesFinder();
            $adplaces = $adplacesfinder->FindByAd( $ad );
            $ret = array();
            
            foreach ( $adplaces as $adplace ) {
                $ret[] = $adplace->Place;
            }
            return $ret;
        }
        public function FindByIds( $ids ) {
            $query = $this->mDb->Prepare(
                'SELECT
                    *
                FROM
                    :places
                WHERE
                    `place_id` IN :placeids;'
            );
            $query->BindTable( 'places' );
            $query->Bind( 'placeids', $ids );
            
            return $this->FindBySQLResource( $query->Execute() );
        }
    }
    
    class Place extends Satori {
        protected $mDbTableAlias = 'places';
        
        // no privcheck after this point
        public function __get( $key ) {
            switch ( $key ) {
                case 'Nameaccusative':
                    if ( empty( $this->mCurrentValues[ 'Nameaccusative' ] ) ) {
                        return $this->Name;
                    }
                    return $this->mCurrentValues[ 'Nameaccusative' ];
                default:
                    return parent::__get( $key );
            }
        }
        public function OnBeforeDelete() {
            $this->Delid = 1;
            $this->Save();
            $finder = New UserFinder();
            $finder->ClearPlace( $this->Id );

            return false;
        }
        public function LoadDefaults() {
            $this->Delid = 0;
            $this->X = 0;
            $this->Y = 0;
        }
        public function IsDeleted() {
            return $this->Delid != 0;
        }
    }
?>
