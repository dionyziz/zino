<?php
	function ElementUserStatic( $theuser, $link = true, $bold = false ) {
        global $xc_settings;
        
        $link = $link && $theuser->Exists(); // don't link to a non-existing user even if forced
        
		if ( $link ) {
            ?><a href="<?php
            Element( 'user/url', $theuser );
            ?>"<?php
			if ( $theuser->LPE() != "0000-00-00" ) {
				$nowdate = strtotime( NowDate() );
				$olddate = strtotime( $theuser->LPE() );
				$diff = $nowdate - $olddate;
				if ( $diff < 86400 ) {
					// one day
    				?> style="border-bottom: 1px dashed gray;"<?php
				}
			}
            ?> class="journalist"><?php
		}
        if ( $bold ) {
            ?><strong><?php
        }
        echo $theuser->Username();
        if ( $bold ) {
            ?></strong><?php
        }
        if ( $link ) {
            ?></a><?php
        }
	}
?>
