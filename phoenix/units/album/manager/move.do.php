<?php
    function UnitAlbumManagerMove( tInteger $photoid, tInteger $albumid ) {
        global $libs;
        global $user;
        global $rabbit_settings;
        
        $libs->Load( 'album' );
    
        $album = New Album( $albumid->Get() );
        $photo = New Image( $photoid->Get() );
        
        if ( !$album->Exists() || !$photo->Exists() ) {
            ?>alert( "Album or image does not exist" );<?php
            return;
        }
        
        if ( $album->Ownerid == $user->Id && $photo->Userid == $user->Id ) {
            $oldalbum = $photo->Album;
            $oldalbummainimageid = $photo->Album->Mainimageid;
            $newalbummainimageid = $album->Mainimageid;
            
            $oldavatarid = $user->Avatarid;
            
            $photo->MoveTo( $album );
            
            //Check for Mainimageid changes from moving
            if ( $oldalbummainimageid != $oldalbum->Mainimageid ) {
                ?>var oldmain = $( "div.albumlist li#<?php echo $oldalbum->Id; ?> span.imageview img" );
                oldmain.fadeOut( "fast" , function() {
                    oldmain.attr( "src", "<?php
                        if ( $oldalbum->Mainimage->Id != 0 ) {
                            Element( "image/url", $oldalbum->Mainimage->Id, $user->Id, IMAGE_CROPPED_100x100 );
                        }
                        else {
                            echo $rabbit_settings[ 'imagesurl' ] . 'anonymous100.jpg';
                        }
                    ?>" );
                    oldmain.load( function() {
                        oldmain.fadeIn( "normal" );
                        oldmain.unbind( "load");
                    } );                } ); <?php
            }
            if ( $newalbummainimageid != $album->Mainimageid ) {
                ?>var newmain = $( "div.albumlist li#<?php echo $albumid; ?> span.imageview img" );
                newmain.fadeOut( "fast" , function() {
                    newmain.attr( "src", "<?php
                        if ( $album->Mainimageid != 0 ) {
                            Element( "image/url", $album->Mainimage->Id, $user->Id, IMAGE_CROPPED_100x100 );
                        }
                        else {
                            echo $rabbit_settings[ 'imagesurl' ] . 'anonymous100.jpg';
                        }
                    ?>" );
                    newmain.load( function() {
                        newmain.fadeIn( "normal" );
                        newmain.unbind( "load");
                    } );
                } ); <?php
            }
            
            $user = New User( $user->Id );
            
            if ( $oldavatarid != $user->Avatarid ) {
                ?>var newavatar = $( "div#loggedinmenu > span > img" );
                newavatar.fadeOut( "fast" , function() {
                    newavatar.attr( "src", "<?php
                        if ( $user->Avatarid != 0 ) {
                            Element( "image/url", $user->Avatarid, $user->Id, IMAGE_CROPPED_100x100 );
                        }
                        else {
                            echo $rabbit_settings[ 'imagesurl' ] . 'anonymous100.jpg';
                        }
                    ?>" );
                    newavatar.load( function() {
                        newavatar.fadeIn( "normal" );
                        newavatar.unbind( "load");
                    } );
                } ); <?php
            }
        }
        else {
            ?>alert( "You do not own either the Album or the Photo" );<?php
        }
    }
?>