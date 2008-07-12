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
            Element( 'image/url', $image->Id, $image, IMAGE_CROPPED_100x100 );
            $url100 = ob_get_clean();
            ob_start();
            Element( 'image/url', $image->Id, $image, IMAGE_FULLVIEW );
            $urlfull = ob_get_clean();
            $images[] = array( $url100, $urlfull, $image->Name );
        }

        echo $callback;
        ?>(<?php
        echo w_json_encode( $images );
        ?>, <?php
        echo $location;
        ?>)<?php
    }
?>
