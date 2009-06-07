<?php
    class ElementUserAvatar extends Element {

        public function Render( $avatarid , $avataruserid , $avatarwidth , $avatarheight , $theusername , $size , $class = '' , $style = '' , $cssresizable = false , $csswidth = 0 , $cssheight = 0 ) {
            global $rabbit_settings, $xc_settings;
            
            // size can either be 150 or 50, which means avatars of size 150x150 or 50x50 respectively
            
            if ( $cssresizable ) {
                if ( $csswidth == 50 ) {
                    $aviesize = 50;
                }
                else if ( $csswidth == 75 ) {
                    $aviesize = 75;
                }
            }
            else {
                $aviesize = $size;
            }
            ?><span class="vavie<?php
            echo $aviesize;
                if ( $cssresizable ) {
                    if ( $csswidth == 50 ) {
                        ?>50<?php
                    }
                    else if ( $csswidth == 75 ) {
                        ?>75<?php
                    }
                }
                else {
                    echo $size;
                }
            ?>">
                <img src="<?php
                if ( $avatarid > 0 ) {
                    if ( $size == 150 ) {
                        $type = IMAGE_CROPPED_150x150;
                    }
                    else if ( $size == 100 ) {
                        $type = IMAGE_CROPPED_100x100;
                    }
                    else {
                        die( "avatar size must be either 150 or 100" );
                    }
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
                ?>"></img>
                <span class="rndavie<?php
                    echo $aviesize;
                ?>">&nbsp;</span>  
            </span><?php
        }
    }
?>
