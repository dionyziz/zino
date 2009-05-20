<?php
    
    class ElementAlbumRow extends Element {
        public function Render( $album ) {
            global $rabbit_settings;

            if ( $album->Id == $album->Owner->Egoalbumid ) {
                $albumname = 'Εγώ';
            }
            else {
                $albumname = $album->Name;
            }
            ?><li id="<?php
            echo $album->Id;
            ?>"><?php
            echo $albumname;
            ?></li><?php
        }
    }
?>
