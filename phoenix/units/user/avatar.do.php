<?php
    
    function UnitUserAvatar( tInteger $imageid ) {
        global $user;
        global $rabbit_settings;
        
        $image = New Image( $imageid->Get() );
        
        if ( !$image->IsDeleted() ) {
            if ( $image->User->Id == $user->Id ) {
                if ( $rabbit_settings[ 'production' ] ) {
                    ?>$( 'div.sidebar div.basicinfo h2 img' ).attr( {
                        src : ExcaliburSettings.photosurl + '<?php
                        echo $user->Id;
                        ?>/<?php
                        echo $image->Id;
                        ?>/<?php
                        echo $image->Id;
                        ?>' + ExcaliburSettings.image_cropped_150x150 + '.jpg'
                    } );<?php
                }
                else {
                    ?>$( 'div.sidebar div.basicinfo h2 img' ).attr( {
                        src : ExcaliburSettings.photosurl + '<?php
                        echo $user->Id;
                        ?>/_<?php
                        echo $image->Id;
                        ?>/<?php
                        echo $image->Id;
                        ?>_' + ExcaliburSettings.image_cropped_150x150 + '.jpg'
                    } );<?php
                }
                ?>$( $( 'div.main div.photos ul li a' )[ 0 ] ).attr( {
                    href : '?p=photo&id=<?php
                    echo $image->Id;
                    ?>'
                } ).html( <?php
                ob_start();
                Element( 'image/view' , $image , IMAGE_CROPPED_100x100 , '' , $user->Name , $user->Name , '' );
                echo w_json_encode( ob_get_clean() );
                ?> );
                $( $( 'div.main div.photos' )[ 0 ] ).show();<?php
            }
        }
    }
?>
