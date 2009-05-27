<?php
    /*
        Developer: Dionyziz
    */
    
    class AdException extends Exception {
    }
    
    class AdPlacesFinder extends Finder {
        protected $mModel = 'AdPlace';
        
        public function FindByAd( Ad $ad ) {
            $prototype = New AdPlace();
            $prototype->Adid = $ad->Id;
            
            $ret = $this->FindByPrototype( $prototype );
            $placeids = array();
            foreach ( $ret as $adplace ) {
                $placeids[] = $adplace->Placeid;
            }
            
            $placefinder = New PlaceFinder();
            $places = $placefinder->FindByIds( $placeids );
            $placebyid = array();
            foreach ( $places as $place ) {
                $placebyid[ $place->Id ] = $place;
            }
            
            foreach ( $ret as $i => $adplace ) {
                $ret[ $i ]->CopyPlaceFrom( $placebyid[ $adplace->Placeid ] );
            }
            
            return $ret;
        }
    }
    
    class AdPlace extends Satori {
        protected $mDbTableAlias = 'adplaces';
        
        public function Relations() {
            $this->Place = $this->HasOne( 'Place', 'Placeid' );
            $this->Ad = $this->HasOne( 'Ad', 'Adid' );
        }
        public function CopyPlaceFrom( Place $value ) {
            $this->mRelations[ 'place' ]->CopyFrom( $value );
        }
    }
    
    class AdFinder extends Finder {
        protected $mModel = 'Ad';
        
        public function FindAllActive() {
            $query = $this->mDb->Prepare(
                'SELECT
                    *
                FROM
                    :ads
                WHERE
                    `ad_active`=:yes
                    AND `ad_pageviewsremaining`>0
                ;'
            );
            $query->BindTable( 'ads' );
            $query->Bind( 'yes', 'yes' );
            $res = $query->Execute();
            
            $ads = array();
            while( $ad = $res->FetchArray() ) {
                $ads[] = new Ad( $ad );
            }
            return $ads;
        }
        
        public function FindToShow() {
            $query = $this->mDb->Prepare(
                'SELECT
                    *
                FROM
                    :ads
                WHERE
                    `ad_active`=:yes
                    AND `ad_pageviewsremaining`>0
                ORDER BY
                    RAND()
                LIMIT 1;'
            );
            $query->BindTable( 'ads' );
            $query->Bind( 'yes', 'yes' );
            $res = $query->Execute();
            if ( $res->Results() ) {
                return New Ad( $res->FetchArray() );
            }
            return false;
        }
        public function FindByUser( User $owner ) {
            $ad = New Ad();
            $ad->Userid = $owner->Id;
            
            return $this->FindByPrototype( $ad );
        }
    }
    
    class Ad extends Satori {
        protected $mDbTableAlias = 'ads';
        
        protected function LoadDefaults() {
            global $user;
            
            $this->Userid = $user->Id;
            $this->Active = 'no';
        }
        public function IsActive() {
            return $this->Active == 'yes';
        }
        public function Relations() {
            $this->Image = $this->HasOne( 'Image', 'Imageid' );
            $this->Places = $this->HasMany( 'PlaceFinder', 'FindByAd', $this );
        }
        public function WasShown() {
            if ( $this->Pageviewsremaining ) {
                --$this->Pageviewsremaining;
            }
            if ( $this->Pageviewsremainingtoday ) {
                --$this->Pageviewsremainingtoday;
            }
            $this->Save();
        }
    }
?>
