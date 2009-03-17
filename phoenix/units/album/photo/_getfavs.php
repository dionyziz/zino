<?php
    function UnitAlbumPhotoGetfavs( tInteger $id ) {
        $id = $id->Get();
        ?>$( 'div#pview div.image_tags:last' ).html( <?php
        Element( 'album/photo/favouritedby', $id, -1 );
        ?> ); <?php
    };
?>