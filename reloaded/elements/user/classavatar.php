<?php
	function ElementUserClassavatar( $theuser ) {
        global $xc_settings;
        
        ob_start();
        
		if ( $theuser->CanModifyCategories() ) {
			?><img title="Διοικητής" src="<?php
            echo $xc_settings[ 'staticimagesurl' ];
            ?>icons/officer.jpg" style="width:50px;height:50px" alt="Διοικητής" class="avatar" /><?php
		}
		else if ( $theuser->CanModifyStories() ) {
			?><img title="Δημοσιογράφος" src="<?php
            echo $xc_settings[ 'staticimagesurl' ];
            ?>icons/journalist.jpg" style="width:50px;height:50px" alt="Δημοσιογράφος" class="avatar" /><?php
		}
        
        return ob_get_clean();
	}

?>
