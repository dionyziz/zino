<?php
    class FrontpageImageFinder extends Finder {
        protected $mModel = 'FrontpageImage';

        public function FindLatest( $offset = 0, $limit = 15, $totalcount = false ) {
            $prototype = New FrontpageImage();

            return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Imageid', 'DESC' ), $totalcount );
        }
    }
    
    class FrontpageImage extends Satori {
        protected $mDbTableAlias = 'imagesfrontpage';
        
        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Image = $this->HasOne( 'Image', 'Imageid' );
        }
    }
?>
