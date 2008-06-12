<?php
    function UnitAlbumPhotoList( tInteger $albumid, tCoalaPointer $callback, tCoalaPointer $location ) {
        $albumid = $albumid->Get();

        $album = New Album( $albumid );
        if ( !$album->Exists() ) {
            return;
        }

        $images = array();
        foreach ( $album->Images as $image ) {
            $images[] = array( $image->Id, $image->Userid );
        }

        echo $callback;
        ?>(<?php
        echo w_json_encode( $images );
        ?>, <?php
        echo $location;
        ?>)<?php
    }
?>
