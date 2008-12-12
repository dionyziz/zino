<?php

    class ElementSchoolImageList extends Element {

        public function Render( Array $images ) {
            if ( count( $images ) > 0 ) {
                ?><div class="plist">
                    <ul><?php
                        foreach ( $images as $image ) {
                            ?><li><a href="?p=photo&amp;id=<?php
                            echo $image->Id;
                            ?>"><?php
                            Element( 'image/view' , $image->Id , $image->User->Id , $image->Width , $image->Height , IMAGE_CROPPED_100x100 , '' , $image->User->Name , '' , false , 0 , 0 , $image->Numcomments );
                            ?></a></li><?php
                        }
                    ?></ul>
                </div><?php
            }
        }
    }

?>
