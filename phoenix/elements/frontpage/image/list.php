<?php
    class ElementFrontpageImageList extends Element {
        protected $mPersistent = array( 'imageseq' );
        public function Render( $imageseq ) {
            $finder = New ImageFinder();
            $images = $finder->FindFrontpageLatest( 0, 15 );
            if ( count( $images ) > 0 ) {
                ?><div class="lstimages plist">
                    <div class="more">
                        <a href="photos" class="button" title="Περισσότερες φωτογραφίες">&raquo;</a>
                    </div>
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
