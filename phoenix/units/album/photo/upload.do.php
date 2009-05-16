<?php
    
    function UnitAlbumPhotoUpload( tInteger $imageid , tCoalaPointer $node , tBoolean $x100 ) {
        global $libs;
        global $user;
        global $rabbit_settings;
        
        $libs->Load( 'image/image' );
        
        $x100 = $x100->Get();
        $image = New Image( $imageid->Get() );
        ?>$( <?php
        echo $node;
        ?> ).html( <?php
        ob_start();
        if ( $x100 ) {
			if ( $user->Id == 872 ) {
            	?><a href="?p=photo&id=<?php
			}else {
				?><a href="?p=photo&amp;id=<?php
			}
            echo $image->Id;
            ?>"><?php
            Element( 'image/view' , $image->Id , $image->User->Id , $image->Width , $image->Height , IMAGE_CROPPED_100x100 , '' , $image->User->Name , '' , false , 0 , 0 , $image->Numcomments );
            ?></a><?php
        }
        else {
            Element( 'album/photo/small' , $image , false , true , true );
        }
        $x100 = $x100 ? 'cropped_100x100' : 'proportional_210x210';
        echo w_json_encode( ob_get_clean() );
        ?> ).show();<?php
        if ( $image->Album->Numphotos == 1 ) {
            $image->Album->Mainimageid = $image->Id;
            $image->Album->Save();
            if ( $image->Album->Id == $user->Egoalbumid ) {
                if ( $rabbit_settings[ 'production' ] ) {
                    ?>$( 'div.usersections a img' ).attr( {
                        src : ExcaliburSettings.photosurl + '<?php
                        echo $user->Id;
                        ?>/<?php
                        echo $image->Id;
                        ?>/<?php
                        echo $image->Id;
                        ?>' + ExcaliburSettings.image_<?php
                        echo $x100;
                        ?> + '.jpg'
                    } );<?php
                }
                else {
                    ?>$( 'div.usersections a img' ).attr( {
                        src : ExcaliburSettings.photosurl + '<?php
                        echo $user->Id;
                        ?>/_<?php
                        echo $image->Id;
                        ?>/<?php
                        echo $image->Id;
                        ?>_' + ExcaliburSettings.image_<?php
                        echo $x100;
                        ?> + '.jpg'
                    } );<?php
                }
            }
        }
        
    }
