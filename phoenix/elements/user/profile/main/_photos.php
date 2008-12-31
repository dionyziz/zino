<?php
    
    class ElementUserProfileMainPhotos extends Element {
        public function Render( $images , $egoalbum ) {
            global $water;
            
            ?><ul class="plist">
                <li><a href="" class="s_bigadd" title="Ανέβασε μια φωτογραφία"></a></li><?php
                foreach( $images as $image ) {
                    ?><li><a href="?p=photo&amp;id=<?php
                    echo $image->Id;
                    ?>"><?php
                    Element( 'image/view' , $image->Id , $image->User->Id , $image->Width , $image->Height , IMAGE_CROPPED_100x100 , '' , $image->Name , '' , false , 0 , 0 , $image->Numcomments );
                    ?></a></li><?php
                }
            ?></ul><?php    
        }
    }
?>
