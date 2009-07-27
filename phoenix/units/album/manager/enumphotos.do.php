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
            foreach( $images as $image ) {
                ?>var newli = document.createElement( 'li' );
                $( newli ).attr( "id", <?php echo $image->Id; ?> );
                var newdiv = document.createElement( 'div' );
                var newimg = document.createElement( 'img' );
                $( newimg ).load( function( event ) {
                    $(this).fadeIn( "normal" );
                    $(this).unbind( "load" );
                } );
                $( newimg ).attr( "src", "<?php Element( 'image/url' , $image->Id , $image->Userid , IMAGE_CROPPED_100x100 ); ?>" );
                var dragdiv = document.createElement( 'div' );
                $( dragdiv ).addClass( 'draginfo' );
                
                $( newimg ).append( newdiv );
                $( newdiv ).append( dragdiv ).addClass( "photo" );
                $( newli ).append( newdiv ).css( "display", "list-item" );
                $( "ul.photolist" ).append( newli );
                <?php
            }?>
            PhotoManager.postEnumphotos();<?php
        }
    }
?>