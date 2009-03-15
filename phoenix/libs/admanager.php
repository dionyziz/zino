<?php
    class AdException extends Exception {
    }
    
    class AdFinder extends Finder {
        protected $mModel = 'Ad';
        
        public function FindToShow() {
            $query = $this->mDb->Prepare(
                'SELECT
                    *
                FROM
                    :ads
                WHERE
                    
                ORDER BY
                    RAND()
                LIMIT 1;'
            );
            $res = $query->Execute();
            if ( $res->Results() ) {
                return New Ad( $res->FetchArray() );
            }
            return false;
        }
    }
    
    class Ad extends Satori {
        protected $mDbTableAlias = 'ads';
        
        protected function LoadDefaults() {
            global $user;
            
            $this->Userid = $user->Id;
        }
        public function Relations() {
            $this->Image = $this->HasOne( 'Image', 'Imageid' );
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
