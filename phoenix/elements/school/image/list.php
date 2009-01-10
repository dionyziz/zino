<?php

    class ElementSchoolImageList extends Element {

        public function Render( Array $images , $schoolid ) {
            global $user; 
            
            if ( $user->Profile->Schoolid != $schoolid && !count( $images ) ) {
                return;
            }
            ?><div class="plist" id="photolist">
                <ul>
                    <?php
                    foreach ( $images as $image ) {
                        ?><li><a href="?p=photo&amp;id=<?php
                        echo $image->Id;
                        ?>"><?php
                        Element( 'image/view' , $image->Id , $image->User->Id , $image->Width , $image->Height , IMAGE_CROPPED_100x100 , '' , $image->User->Name , '' , false , 0 , 0 , $image->Numcomments );
                        ?></a></li><?php
                    }
                    if ( $user->Profile->Schoolid == $schoolid && $user->HasPermission( PERMISSION_IMAGE_CREATE ) ) {
                        ?><li>
                            <a href=""><span class="s_bigadd">&nbsp;</span></a>
                        </li><?php
                    }
                    ?>
                </ul>
            </div><?php
        }
    }

?>
