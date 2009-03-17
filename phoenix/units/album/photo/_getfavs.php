<?php
    function UnitAlbumPhotoGetfavs( tInteger $id ) {
        echo "test1";
        Element( 'album/photo/favouritedby', $id, -1 );
        echo "test";
    };
?>