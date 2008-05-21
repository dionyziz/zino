<?php
	
	function ElementImage( $image , $width , $height , $class , $alt , $title , $style ) {
		global $xc_settings;
		
		if ( !$image->IsDeleted() ) {
			if ( !ValidId( $width ) || !ValidId( $height ) ) {
	            $width = $image->Width;
				$height = $image->Height;
			}
	        if ( !is_object( $image ) ) {
	            $url = $xc_settings[ 'staticimagesurl' ] . 'anonymous.jpg';
	        }
	        else {
	            $url = $xc_settings[ 'imagesurl' ] . $image->Userid . '/' . $image->Id;
	            $url .= '?resolution=' . $width . 'x' . $height;
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
	}
?>
