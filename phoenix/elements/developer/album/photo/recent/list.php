<?php
    class ElementDeveloperAlbumPhotoRecentList extends Element {
        public function Render( tInteger $pageno ) {
            global $libs;
            $libs->Load( 'image/image' );
            $libs->Load( 'image/frontpage' );
            
            $pageno = $pageno->Get();
            
            if ( $pageno <= 0 ) {
                $pageno = 1;
            }
            
            $finder = New FrontpageImageFinder();
            $images = $finder->FindLatest( 40 * ( $pageno - 1 ), 40, true );
            if ( count( $images ) > 0 ) {
                ?><div class="latestphotos">
                    <h2>Φωτογραφίες</h2>
                    <ul class="lst ul2 border"><?php
                        foreach ( $images as $image ) {
                            if ( $image->Imageid != null ) {
                                ?><li><a href="?p=photo&amp;id=<?php
                                echo $image->Imageid;
                                ?>"><?php
                                Element( 'image/view' , $image->Imageid , $image->Image->Userid , $image->Image->Width , $image->Image->Height , IMAGE_CROPPED_100x100 , '' , $image->Image->User->Name , '' , false , 0 , 0 , $image->Image->Numcomments );
                                ?></a></li>
                                <?php
                            }
                        }
                    ?></ul>
                    <div class="eof"></div>
                    <?php
                    Element( 'pagify', $pageno, 'photos?pageno=', ceil( $images->TotalCount() / 40 ) );
                ?>

                </div><?php
            }

        }
    }
?>
