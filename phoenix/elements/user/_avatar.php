<?php
    class ElementUserAvatar extends Element {

        public function Render( $avatarid , $avataruserid , $avatarwidth , $avatarheight , $theusername , $size , $class = '' , $style = '' , $cssresizable = false , $csswidth = 0 , $cssheight = 0 ) {
            global $rabbit_settings, $xc_settings;
            
            // size can either be 150 or 100, which means avatars of size 150x150 or 50x50 respectively
            
            if ( $cssresizable ) {
                $aviesize = $csswidth;
            }
            else {
                $aviesize = $size;
            }
            ?><span class="vavie<?php
                echo $aviesize;
            ?>">
                <img src="<?php
                if ( $size == 150 ) {
                    $type = IMAGE_CROPPED_150x150;
                    $width = $height = 150;
                }
                else if ( $size == 100 ) {
                    $type = IMAGE_CROPPED_100x100;
                    $width = $height = 100;
                }
                else {
                    die( "avatar size must be either 150 or 100" );
                }
                if ( $avatarid > 0 ) {
                    echo $xc_settings[ 'imagesurl' ] . $avataruserid . '/';
                    if ( !$rabbit_settings[ 'production' ] ) {
                        echo '_';
                    }
                    echo $avatarid . '/' . $avatarid . '_' . $type . '.jpg';
                    //Element( 'image/url' , $avatarid , $avataruserid , $type );
                }
                else {
                    echo $rabbit_settings[ 'imagesurl' ];
                    ?>anonymous<?php
                    echo $size;
                    ?>.jpg<?php
                }   
                ?>"<?php
                if ( $class != "" ) {
                    ?> class="<?php
                    echo htmlspecialchars( $class );
                    ?>"<?php
                }
                ?> style="<?php
                if ( $cssresizable ) {
                    ?>width:<?php
                    echo $csswidth;
                    ?>px;height:<?php
                    echo $cssheight;
                    ?>px;<?php
                }
                else {
                    ?>width:<?php
                    echo $width;
                    ?>px;height:<?php
                    echo $height;
                    ?>px;<?php
                }
                if ( $style != "" ) {
                    echo htmlspecialchars( $style );
                }
                ?>" title="<?php
                echo $theusername;
                ?>" alt="<?php
                echo $theusername;
                ?>"></img><?php
                if ( $csswidth != 50 ) {
                    ?><span class="s1_rndavie<?php
                        echo $aviesize;
                    ?>" title="<?php
                    echo $theusername;
                    ?>" alt="<?php
                    echo $theusername;
                    ?>">&nbsp;</span><?php
                }
                ?></span><?php
        }
    }
?>
