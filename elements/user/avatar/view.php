<?php

	function ElementUserAvatarView( $theuser ) {
        global $xc_settings;
        
		?><img title="<?php
		echo Element( 'user/avatar/title', $theuser );
		?>" style="width:50px; height: 50px;" class="avatar" src="<?php
        echo $xc_settings[ 'staticimagesurl' ];
        ?>icons/<?php
		echo Element( 'user/avatar/file', $theuser );
		?>_thumbnail.jpg" alt="<?php
		echo Element( 'user/avatar/title', $theuser );
		?>" /><?php
	}

?>