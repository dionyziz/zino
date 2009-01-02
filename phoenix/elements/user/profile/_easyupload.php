<?php
    class ElementUserProfileEasyupload extends Element {
        public function Render () {
            global $user;

            ?><div>
                Στο album <b>Εγώ</b><?php
                $finder = New AlbumFinder();
                $albums = $finder->FindByUser( $user );
                ?><ul><?php
                $album = New Album( $user->Egoalbumid );
                ?><li id="album_<?php
                echo $album->Id;
                ?>" class="selected"><?php
                Element( 'image/view' , $album->Mainimage->Id , $album->Mainimage->User->Id , 100 , 100 , IMAGE_CROPPED_100x100 , '' , $album->Name , false , true , 50 , 50 , 0 );
                ?></li><?php
                foreach ( $albums as $album ) {
                    if ( $album->Id != $user->Egoalbumid ) {
                        ?><li id="album_<?php
                        echo $album->Id;
                        ?>"><?php
                        Element( 'image/view' , $album->Mainimage->Id , $album->Mainimage->User->Id , 100 , 100 , IMAGE_CROPPED_100x100 , '' , $album->Name , false , true , 50 , 50 , 0 ); ?></li><?php
                    }
                }
                ?></ul>
            </div>
            <div class="uploaddiv"><?php
                if ( UserBrowser() == 'MSIE' ) {
                    ?><iframe frameborder="0" style="height:50px" src="?p=upload&amp;albumid=<?php
                    echo $user->Egoalbumid;
                    ?>&amp;typeid=4&amp;color=eef5f9" class="uploadframe" id="uploadframe">
                    </iframe>
                    <?php
                }
                else {
                    ?><object style="height:50px" data="?p=upload&amp;albumid=<?php
                    echo $user->Egoalbumid;
                    ?>&amp;typeid=4&amp;color=eef5f9" class="uploadframe" id="uploadframe" type="text/html">
                    </object><?php
                }
            ?></div>
            <div class="uploadsuccess">
                <span class="s_tick">.</span> Η φωτογραφία προστέθηκε επιτυχώς 
            </div><?php
        }
    }
?>
