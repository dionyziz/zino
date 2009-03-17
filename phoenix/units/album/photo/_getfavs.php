<?php
    function UnitAlbumPhotoGetfavs( tInteger $id ) {
        Element( 'album/photo/favouritedby', $id, -1 );
        echo "test";
    };
?>