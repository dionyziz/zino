<?php
	function ElementImage( $image, $type = IMAGE_PROPORTIONAL_210x210, $class = '', $alt = '', $title = '', $style = '' ) {
		global $xc_settings;
		global $rabbit_settings;


		if ( !is_object( $image ) ) {
			$url = $xc_settings[ 'staticimagesurl' ] . $image;
            list( $width, $height ) = explode( 'x', $type );
		}
		else {
			if ( !$image->IsDeleted() ) {
				$url = $xc_settings[ 'imagesurl' ] . $image->Userid . '/';
                if ( !$rabbit_settings[ 'production' ] ) {
                    $url .= '_';
                }
                $url .= $image->Id . '/' . $image->Id . '_' . $type . '.jpg';
			}
            else {
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
                    throw Exception( 'Invalid image type' );
            }
		}
		?><img src="<?php
		echo $url;
		?>"<?php
		if ( $class != "" ) {
			?> class="<?php
			echo htmlspecialchars( $class );
			?>"<?php
		}
		?> style="width:<?php
		echo $width;
		?>px;height:<?php
		echo $height;
		?>px;<?php
		if ( $style != "" ) {
			echo htmlspecialchars( $style );
		}
		?>"<?php
		if ( $title != "" ) {
			?> title="<?php
			echo htmlspecialchars( $title );
			?>"<?php
		}
		if ( $alt != "" ) {
			?> alt="<?php
			echo htmlspecialchars( $alt );
			?>"<?php
		}
		?> /><?php
	}
?>
