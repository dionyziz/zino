<?php
    function UnitAlbumManagerMove( tInteger $photoid, tInteger $albumid ) {
        global $libs;
        global $user;
        
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
            
            $photo->MoveTo( $album );

            //Check for Mainimageid changes from moving
            if ( $oldalbummainimageid != $oldalbum->Mainimageid ) {
                ?>var oldmain = $( "div.albumlist li#<?php echo $oldalbum->Id; ?> span.imageview img" );
                oldmain.fadeOut( "fast" , function() {
                    oldmain.attr( "src", "<?php Element( "image/url", $oldalbum->Mainimage->Id, $user->Id, IMAGE_CROPPED_100x100 ); ?>" );
                    oldmain.fadeIn( "fast" );
                } ); <?php
            }
            if ( $newalbummainimageid != $album->Mainimageid ) {
                ?>var newmain = $( "div.albumlist li#<?php echo $albumid; ?> span.imageview img" );
                newmain.fadeOut( "fast" , function() {
                    newmain.attr( "src", "<?php Element( "image/url", $photo->Album->Mainimage->Id, $user->Id, IMAGE_CROPPED_100x100 ); ?>" );
                    newmain.fadeIn( "fast" );
                } ); <?php
            }
        }
        else {
            ?>alert( "You do not own either the Album or the Photo" );<?php
        }
    }
?>