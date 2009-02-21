<?php
    class ElementAlbumPhotoRecentList extends Element {
        public function Render( tInteger $pageno ) {
            global $libs;
            
            $pageno = $pageno->Get();
            
            if ( $pageno <= 0 ) {
                $pageno = 1;
            }
            
            $finder = New FrontpageImageFinder();
            $images = $finder->FindLatest( 40 * ( $pageno - 1 ), 40, true );
            if ( count( $images ) > 0 ) {
                ?><div class="lstimages" id="allphotos">
                    <h2>Φωτογραφίες</h2>
                    <ul>
                        <?php
                        foreach ( $images as $image ) {
                            ?><li><a href="?p=photo&amp;id=<?php
                            echo $image->Image->Id;
                            ?>"><?php
                            Element( 'image/view' , $image->Image->Id , $image->Image->User->Id , $image->Image->Width , $image->Image->Height , IMAGE_CROPPED_100x100 , '' , $image->Image->User->Name , '' , false , 0 , 0 , $image->Image->Numcomments );
                            ?></a></li><?php
                        }
                    ?></ul>
                    <div class="eof" /><?php
                    Element( 'pagify', $pageno, 'photos?pageno=', ceil( $images->TotalCount() / 40 ) );
                ?></div><?php
            }
            ?><div class="eof" /><?php
        }
    }
?>
