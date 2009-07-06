<?php
    
    function UnitAlbumCreate( tText $albumname , tCoalaPointer $albumnode ) {
        global $user;
        global $rabbit_settings;
        global $libs;
        
        if ( !$user->Exists() ) {
            return;
        }
        echo 1;
        $libs->Load( 'album' );
        echo 2;
        $albumname = $albumname->Get();
        echo 3;
        if ( $albumname !== '' ) {
        echo 4;
            $album = New Album();
        echo 5;
            $album->Name = $albumname;
        echo 6;
            $album->Save();
        echo 7;
            ?>window.location.href = '<?php
            echo $rabbit_settings[ 'webaddress' ];
            ?>?p=album&id=<?php
            echo $album->Id;
            ?>';<?php
        }
    }
?>
