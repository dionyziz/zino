<?php
    function UnitAlbumPhotoGetfavs( $id ) {
        Element( 'album/photo/favouritedby', $id, -1 );
    }