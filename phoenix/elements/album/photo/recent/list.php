<?php
    class ElementAlbumPhotoRecentList extends Element {
        public function Render( tInteger $pageno ) {
            global $libs;
            
            $pageno = $pageno->Get();
            
            if ( $pageno <= 0 ) {
                $pageno = 1;
            }
            
            $finder = New ImageFinder();
            $images = $finder->FindFrontpageLatest( 40 * ( $pageno - 1 ), 40 );
            if ( count( $images ) > 0 ) {
                ?><div class="lstimages allphotos">
                        <h2>Φωτογραφίες</h2>
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
            ?><div class="eof" /><?php
            Element( 'pagify', $pageno, '?p=allphotos&pageno=', ceil( count( $images ) / 40 ) );
            echo count( $images );
        }
    }
?>