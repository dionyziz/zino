<?php
    class ElementFrontpageImageListforbennu extends Element {
        //protected $mPersistent = array( 'imageseq' );
        public function Render( $imageseq ) {
            global $libs;
            
            $libs->Load( 'bennu/bennu' );
        
            $finder = New ImageFinder();
            $images = $finder->FindFrontpageLatest( 0, 15 );
            
            $images = Bennu_Images_Frontpage( 5, $images );//bennu - target is not required yet            
            
            $finder = New NotificationFinder();
            if ( count( $images ) > 0 ) {
                ?><div class="latestimages">
                <ul><?php
                    foreach ( $images as $image ) {
                        ?><li><a href="?p=photo&amp;id=<?php
                        echo $image->Id;
                        ?>"><?php
                        Element( 'image/view' , $image->Id , $image->User->Id , $image->Width , $image->Height , IMAGE_CROPPED_100x100 , '' , $image->User->Name , '' , false , 0 , 0 , $image->Numcomments );
                        ?></a></li><?php
                    }
                    ?>
                </ul>
                </div><?php
            }
        }
    }
?>
