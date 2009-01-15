<?php

    class ElementAlbumSmall extends Element {
        public function Render( $album, tBoolean $creationmockup, tText $subdomain, tText $url ) {
            global $water;
            global $xc_settings;
            global $rabbit_settings;
            global $libs;

            $libs->Load( 'album' );
            $libs->Load( 'user/user' );

            if ( $subdomain->Exists() && $url->Exists() ) {
                $subdomain = $subdomain->Get();
                $url = $url->Get();
                $finder = New UserFinder();
                $owner = $finder->FindBySubdomain( $subdomain );
                $finder = New AlbumFinder();
                $album = $finder->FindByUserAndUrl( $owner, $url );
            }
            if ( !$creationmockup->Exists() ) {
                $commentsnum = $album->Numcomments;
                $photonum = $album->Numphotos;
                if ( $album->Id == $album->Owner->Egoalbumid ) {
                    $albumname = 'Εγώ';
                }
                else {
                    $albumname = $album->Name;
                }
                ?><div class="album">
                    <a href="?p=album&amp;id=<?php
                    echo $album->Id;
                    ?>">
                        <span class="albummain"><?php
                            if ( $album->Mainimage->Exists() ) {    
                                Element( 'image/view', $album->Mainimage->Id , $album->Mainimage->User->Id , $album->Mainimage->Width , $album->Mainimage->Height , IMAGE_CROPPED_100x100 , '' , $albumname , '' , false , 0 , 0 , 0 ); // TODO: Optimize
                            }
                            else {
								?><img src="<?php
								echo $rabbit_settings[ 'imagesurl' ];
								?>anonymous100.jpg" alt="<?php
								echo htmlspecialchars( $albumname );
								?>" title="<?php
								echo htmlspecialchars( $albumname );
								?>" style="width:100px;height:100px" /><?php
                            }
                        ?></span>
                        <span class="desc"><?php
                        echo htmlspecialchars( $albumname );
                        ?></span>
                    </a>
                    <dl><?php
                        if ( $photonum > 0 ) {
                            ?><dd><span class="s_photonum">&nbsp;</span><?php
                            echo $photonum;
                            ?></dd><?php
                        }
                        if ( $commentsnum > 0 ) {
                            ?><dd><span class="s_commnum">&nbsp;</span><?php
                            echo $commentsnum;
                            ?></dd><?php
                        }
                    ?></dl>
                </div><?php
            }
            else {
                ?><div class="album createalbum">
                    <a href="">
                        <span class="albummain"><img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>anonymous100.jpg" alt="Νέο album" title="Νέο album" /></span>
                    </a>
                    <span class="desc">
                        <input type="text" />
                    </span>
                </div><?php
            }
        }
    }

?>
