<?php
    
    function UnitAlbumCreate( tText $albumname , tCoalaPointer $albumnode ) {
        global $user;
        global $rabbit_settings;
        
        if ( !$user->Exists() ) {
            return;
        }
        
        $albumname = $albumname->Get();
        if ( $albumname !== '' ) {
            $album = new Album();
            $album->Name = $albumname;
            $album->mFoo = 2;
            $album->Save();
            ?>window.location.href = '<?php
            echo $rabbit_settings[ 'webaddress' ];
            ?>?p=album&id=<?php
            echo $album->Id;
            ?>';<?php
        }
    }
?>
