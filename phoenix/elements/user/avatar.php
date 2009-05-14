<?php
    class ElementUserAvatar extends Element {
        protected $mPersistent = array( 'avatarid' , 'theusername' , 'size' , 'class' , 'style' , 'cssresizable' , 'csswidth' , 'cssheight' );

        public function Render( $avatarid , $avataruserid , $avatarwidth , $avatarheight , $theusername , $size , $class = '' , $style = '' , $cssresizable = false , $csswidth = 0 , $cssheight = 0 ) {
            global $rabbit_settings;
            
            // size can either be 150 or 50, which means avatars of size 150x150 or 50x50 respectively
            if ( $avatarid > 0 ) {
                if ( $size == 150 ) {
                    Element( 'image/view' , $avatarid , $avataruserid , $avatarwidth , $avatarheight , IMAGE_CROPPED_150x150, $class , $theusername , $style , $cssresizable , $csswidth , $cssheight , 0 );
                }
                else if ( $size == 100 ) {
                    Element( 'image/view' , $avatarid , $avataruserid , $avatarwidth , $avatarheight ,  IMAGE_CROPPED_100x100, $class , $theusername , $style , $cssresizable , $csswidth , $cssheight , 0 );
                }
            }
            else {
				?><span class="imageview"><img src="<?php
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
				echo htmlspecialchars( $theusername );
				?>" title="<?php
				echo htmlspecialchars( $theusername );
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
				?>"></img></span><?php
            }
        }
    }
?>
