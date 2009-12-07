<?php
    
    class ElementDeveloperUserProfileMainPhotos extends Element {
        public function Render( $images , $egoalbum , $theuserid ) {
            global $water;
            global $user;
          
            ?><ul class="lst ul1 border"><?php
                if ( $user->Id == $theuserid && $user->HasPermission( PERMISSION_IMAGE_CREATE ) ) {
                   ?><li class="addphoto"><a href="" class="s1_0048" title="Ανέβασε μια φωτογραφία">&nbsp;</a></li><?php
                }
                foreach( $images as $image ) {
                    ?><li><a href="?p=photo&amp;id=<?php
                    echo $image->Id;
                    ?>"><?php
                    Element( 'developer/image/view' , $image->Id , $image->Userid , $image->Width , $image->Height , IMAGE_CROPPED_100x100 , '' , $image->Name , '' , false , 0 , 0 , $image->Numcomments );
                    ?></a></li><?php
                }
            ?></ul><?php    
        }
    }
?>
