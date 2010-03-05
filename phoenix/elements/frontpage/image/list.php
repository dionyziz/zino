<?php
    class ElementFrontpageImageList extends Element {
        //protected $mPersistent = array( 'imageseq' );
        public function Render( $imageseq ) {
            global $user;
            global $libs;

            $libs->Load( 'image/image' );
            
            $images = false;
            if ( $user->Exists() && $user->HasPermission( PERMISSION_ADMINPANEL_VIEW ) ) {
                $images = $finder->FindUserRelated( $user );
                if ( $images === false && $user->HasPermission( PERMISSION_ADMINPANEL_VIEW ) ) {
                    ?><!-- <b>Spot connection failed (start daemon!).</b> --><?php
                }
            }
            if ( $images === false ) {
                $images = $finder->FindFrontpageLatest( 0, 16 );
            }
            
            if ( count( $images ) > 0 ) {
                ?><div>
                    <div class="more">
                        <a href="photos" class="button" title="Περισσότερες φωτογραφίες">&raquo;</a>
                    </div>
                <ul class="lst ul1 border"><?php
                    foreach ( $images as $image ) {
                        ?><li><a href="?p=photo&amp;id=<?php
                        echo $image->Id;
                        ?>"><?php
                        Element( 'image/view' , $image->Id , $image->Userid , $image->Width , $image->Height , IMAGE_CROPPED_100x100 , '' , $image->User->Name , '' , false , 0 , 0 , $image->Numcomments );
                        ?></a></li><?php
                    }
                    ?>
                </ul>
                </div><?php
            }
        }
    }
?>
