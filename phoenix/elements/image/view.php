<?php
    class ElementImageView extends Element {
        public function Render( $image, $type = IMAGE_PROPORTIONAL_210x210, $class = '', $alt = '', $title = '', $style = '' , $cssresizable = false , $csswidth = 0 , $cssheight = 0 ) {
            global $xc_settings;
            global $rabbit_settings;

            if ( !is_object( $image ) ) {
                list( $width, $height ) = explode( 'x', $type );
            }
            else {
                if ( $image->IsDeleted() ) {
                    return;
                }
                switch ( $type ) {
                    case IMAGE_PROPORTIONAL_210x210:
                        if ( $image->Width <= 210 && $image->Height <= 210 ) {
                            $width = $image->Width;
                            $height = $image->Height;
                            break;
                        }
                        list( $width, $height ) = $image->ProportionalSize( 210, 210 );
                        break;
                    case IMAGE_CROPPED_100x100:
                        $width = $height = 100;
                        break;
                    case IMAGE_CROPPED_150x150:
                        $width = $height = 150;
                        break;
                    case IMAGE_FULLVIEW:
                        $width = $image->Width;
                        $height = $image->Height;
                        break;
                    default:
                        throw New Exception( 'Invalid image type' );
                }
            }
            ?><img src="<?php
            Element( 'image/url', $image, $type );
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
                echo htmlspecialchars( $title );
                ?>" alt="<?php
                echo htmlspecialchars( $alt );
                ?>" /><?php
        }
    }
?>
