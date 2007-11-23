<?php
	function ElementImage( $imagesrc , $width = 0 , $height = 0 , $class = '' , $style = '' , $title = '' , $alt = '' ) {
        global $xc_settings;
        
		// $imagesrc is an instanciated image class
		if ( !ValidId( $width ) || !ValidId( $height ) ) {
            if ( !is_object( $imagesrc ) || !$imagesrc->Exists() ) {
                $width = 50;
                $height = 50;
            }
            else {
                $width = $imagesrc->Width();
                $height = $imagesrc->Height();
            }
		}
        if ( !is_object( $imagesrc ) || !$imagesrc->Exists() ) {
            $url = $xc_settings[ 'staticimagesurl' ] . 'anonymous.jpg';
        }
        else {
            $url = $xc_settings[ 'imagesurl' ] . $imagesrc->UserId() . '/' . $imagesrc->Id();
            if ( $width > 0 || $height > 0 ) {
                $url .= '?resolution=' . $width . 'x' . $height;
            }
        }
        
		?><img src="<?php
        echo $url;
        ?>"<?php
		if ( $class != "" ) {
			$class = htmlspecialchars( $class );
			?> class="<?php
			echo $class;
			?>"<?php
		}
        ?> style="width:<?php
        echo $width;
        ?>px;height:<?php
        echo $height;
        ?>px;<?php
		if ( $style != "" ) {
			$style = htmlspecialchars( $style );
			echo $style;
		}
        ?>"<?php
		if ( $title != "" ) {
			$title = htmlspecialchars( $title );
			?> title="<?php
			echo $title;
			?>"<?php
		}
        $alt = htmlspecialchars( $alt );
        ?> alt="<?php
        echo $alt;
        ?>" /><?php
	}
?>
