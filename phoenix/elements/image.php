<?php
	
	function ElementImage( $image , $width , $height , $class , $alt , $title , $style ) {
		global $xc_settings;
		global $rabbit_settings;

		if ( !is_object( $image ) ) {
			$url = $xc_settings[ 'staticimagesurl' ] . $image;
		}
		else {
			if ( !$image->IsDeleted() ) {
				if ( !ValidId( $width ) || !ValidId( $height ) ) {
		            $width = $image->Width;
					$height = $image->Height;	
				}
                // TODO: resoltuion handling
				$url = $xc_settings[ 'imagesurl' ] . $image->Userid . '/';
                if ( !$rabbit_settings[ 'production' ] ) {
                    $url .= '_';
                }
                $url .= $image->Id . '/' . $image->Id . '_full.jpg';
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
