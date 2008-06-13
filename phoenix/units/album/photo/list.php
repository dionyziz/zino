<?php
    function UnitAlbumPhotoList( tInteger $albumid, tCoalaPointer $callback, tCoalaPointer $location ) {
        $albumid = $albumid->Get();

        $album = New Album( $albumid );
        if ( !$album->Exists() ) {
            return;
        }

        $images = array();
        foreach ( $album->Images as $image ) {
            ob_start();
            Element( 'image/url', $image, IMAGE_CROPPED_100x100 );
            $images[] = ob_get_clean();
        }

        echo $callback;
        ?>(<?php
        echo w_json_encode( $images );
        ?>, <?php
        echo $location;
        ?>)<?php
    }
?>
