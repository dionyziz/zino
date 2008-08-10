<?php
    
    class ElementUserAvatar extends Element {
        public function Render( $theuser , $size , $class = '' , $style = '' , $cssresizable = false , $csswidth = 0 , $cssheight = 0 ) {
            global $rabbit_settings;
            
            // size can either be 150 or 50, which means avatars of size 150x150 or 50x50 respectively
            if ( $theuser->Avatarid > 0 ) {
                $avatar = $theuser->Avatar;
                if ( $size == 150 ) {
                    Element( 'image/view' , $avatar->Id , $avatar->User->Id , $avatar->Width , $avatar->Height , IMAGE_CROPPED_150x150, $class , $theuser->Name , $style , $cssresizable , $csswidth , $cssheight );
                }
                else if ( $size == 100 ) {
                    Element( 'image/view' , $avatar->Id , $avatar->User->Id , $avatar->Width , $avatar->Height ,  IMAGE_CROPPED_100x100, $class , $theuser->Name , $style , $cssresizable , $csswidth , $cssheight );
                }
            }
            else {
				?><img src="<?php
				echo $rabbit_settings[ 'imagesurl' ];
				?>anonymous<?php
				echo $size;
				?>.jpg"<?php
				if ( $class != "" ) {
					?> class="<?php
					echo htmlspecialchars( $class ); 
					?>"<?php
				}
				?> alt="<?php
				echo htmlspecialchars( $theuser->Name );
				?>" title="<?php
				echo htmlspecialchars( $theuser->Name );
				?>" style="<?php
				if ( !$cssresizable ) {
					?>width:<?php
					echo $size;
					?>px;height:<?php
					echo $size;
					?>px;<?php
				}
				else {
					?>width:<?php
					echo $csswidth;
					?>px;height:<?php
					echo $cssheight;
					?>px;<?php
				}
				if ( $style != "" ) {
					echo htmlspecialchars( $style );
				}
				?>" /><?php
            }
        }
    }
?>
