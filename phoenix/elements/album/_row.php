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
            ?>">
                <span class="imageview">
                </span>
                <span class="albumname">
                    <h3><?php
                    echo htmlspecialchars( $albumname );
                    ?></h3>
                </span>
            </li><?php
        }
    }
?>
