<?php
	function ElementImage_Migrate( $imagesrc , $width = 0 , $height = 0 , $class = '' , $style = '' , $title = '' , $alt = '' ) {
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
            $url = $xc_settings[ 'imagesurl' ] . 'media/' . $imagesrc->UserId() . '/' . $imagesrc->Id() . '/' . $imagesrc->Id() . '_full.jpg';
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
        ?> width="<?php
        echo $width;
        ?>" height="<?php
        echo $height;
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
