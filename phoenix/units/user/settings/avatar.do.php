<?php
    
    function UnitUserSettingsAvatar( tInteger $imageid ) {
        global $user;
        global $rabbit_settings;
        
        $image = New Image( $imageid->Get() );
        
        if ( $image->IsDeleted() ) {
            ?>alert( 'Sorry, this image is deleted' );<?php
            return;
        }

        if ( $image->User->Id != $user->Id ) {
            ?>alert( 'You can\'t use somebody elses images as your avatar' );<?php
            return;
        }
       
        if ( $rabbit_settings[ 'production' ] ) {
            ?>$( 'div.settings div.tabs form#personalinfo div.option div.setting img.avie' ).attr( {
                src : ExcaliburSettings.photosurl + '<?php
                echo $user->Id;
                ?>/<?php
                echo $image->Id;
                ?>/<?php
                echo $image->Id;
                ?>_' + ExcaliburSettings.image_cropped_150x150 + '.jpg'
            } );
            $( 'img.banneravatar' ).attr( {
                src : ExcaliburSettings.photosurl + '<?php
                echo $user->Id;
                ?>/<?php
                echo $image->Id;
                ?>/<?php
                echo $image->Id;
                ?>_' + ExcaliburSettings.image_cropped_100x100 + '.jpg'
            } );<?php
        }
        else {
            ?>$( 'div.settings div.tabs form#personalinfo div.option div.setting img.avie' ).attr( {
                src : ExcaliburSettings.photosurl + '<?php
                echo $user->Id;
                ?>/_<?php
                echo $image->Id;
                ?>/<?php
                echo $image->Id;
                ?>_' + ExcaliburSettings.image_cropped_150x150 + '.jpg'
            } );
            $( 'img.banneravatar' ).attr( {
                src : ExcaliburSettings.photosurl + '<?php
                echo $user->Id;
                ?>/_<?php
                echo $image->Id;
                ?>/<?php
                echo $image->Id;
                ?>_' + ExcaliburSettings.image_cropped_100x100 + '.jpg'
            } );<?php
        }

        $user->EgoAlbum->Mainimageid = $image->Id;
        $user->EgoAlbum->Save();
    }
?>
