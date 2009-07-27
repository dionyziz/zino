<?php
    function UnitAlbumManagerEnumphotos( tInteger $albumid ) {
        global $libs;
        global $user;
        
        $libs->Load( 'image/image' );
        $libs->Load( 'album' );
        $albumid->Get();
        $album = New Album( $albumid );
        if ( $album->Ownerid == $user->Id ) {
            $finder = New ImageFinder();
            $images = $finder->FindByAlbum( $album, 0, 400 );
            foreach( $images as $image ) {?>
                PhotoManager.addNewPhoto( <?php echo $image->Id; ?>, "<?php Element( 'image/url' , $image->Id , $image->Userid , IMAGE_CROPPED_100x100 ); ?>" );
            <?php }
            ?>PhotoManager.postEnumphotos();<?php
        }
    }
?>