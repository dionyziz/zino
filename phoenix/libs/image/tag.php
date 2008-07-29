<?php
    global $libs;

    $libs->Load( 'image/image' );

    class ImageTagFinder extends Finder {
        protected $mModel = 'ImageTag';

        public function FindByImage( Image $image ) {
            $prototype = New ImageTag();
            $prototype->Imageid = $image->Id;

            return $this->FindByPrototype( $prototype, 0, 10, array( 'Id', 'DESC' ) );
        }
    }

    class ImageTag extends Satori {
        protected $mDbTableAlias = 'imagetags';
    }
?>
