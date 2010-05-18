<?php
    class ElementUserProfileEasyupload extends Element {
        public function Render () {
            global $user;
            global $libs;
            global $rabbit_settings;
            
            $libs->Load( 'album' );
            
            ?><div>
                <span class="inalb">Στο album <b>Εγώ</b></span><?php
                $finder = New AlbumFinder();
                $albums = $finder->FindByUser( $user );
                ?><div class="ulcontainer"><ul style="width:<?php
                echo count( $albums ) * 64;
                ?>px;"><?php
                $album = New Album( $user->Egoalbumid );
                ?><li id="album_<?php
                echo $album->Id;
                ?>" class="selected"><?php
                Element( 'image/view' , $album->Mainimageid , $album->Mainimage->Userid , 100 , 100 , IMAGE_CROPPED_100x100 , '' , "Eγώ" , false , true , 50 , 50 , 0 );
                ?></li><?php
                foreach ( $albums as $album ) {
                    if ( $album->Id != $user->Egoalbumid ) {
                        ?><li id="album_<?php
                        echo $album->Id;
                        ?>"><?php
                        Element( 'image/view' , $album->Mainimageid , $album->Mainimage->Userid , 100 , 100 , IMAGE_CROPPED_100x100 , '' , $album->Name , false , true , 50 , 50 , 0 ); ?></li><?php
                    }
                }
                ?></ul></div>
            </div>
            <div class="uploaddiv">
				<object>
					<param name="movie" value="bin/multifileUploader.swf?1"/>
					<param name="wmode" value="opaque"/>
					<param name="width" value="100"/>
					<param name="height" value="25"/>
                    <param name="posturl" value="<?php
                    echo $rabbit_settings[ 'webaddress' ];
                    ?>/do/image/upload2" />
					<param name="allowScriptAccess" value="always"/>
					<embed src="bin/multifileUploader.swf" type="application/x-shockwave-flash" allowScriptAccess="always" wmode="opaque" width="100" height="25" id="flashuploader" flashvars="posturl=<?php
                    echo $rabbit_settings[ 'webaddress' ];
                    ?>/do/image/upload2" /><?php
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
                    ?>&amp;typeid=4&amp;color=e8eAec" class="uploadframe" id="uploadframe" type="text/html">
                    </object><?php
                }
            ?></object></div>
            <div class="uploadsuccess">
                <div>Η φωτογραφία προστέθηκε επιτυχώς</div> 
            </div><?php
        }
    }
?>
