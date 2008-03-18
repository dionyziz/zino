<?php

	function ElementShoutView( $shout ) {
		global $user;
        global $xc_settings;
		
		?><div id="shout_<?php
		echo $shout->Id(); 
		?>"><?php
		
		Element( 'user/icon' , $shout->User() );
		
		?><div id="shouttext_<?php 
        echo $shout->Id(); 
        ?>"><?php
		echo $shout->TextFormatted();
		
        $canmodify = $user->CanModifyCategories() || $shout->UserId() == $user->Id();

		if ( $canmodify && $user->Rights() >= $xc_settings[ 'readonly' ] ) {
			?><a style="cursor: pointer;" onclick="Shoutbox.Edit( <?php
			echo $shout->Id();
			?> );return false;" href="" title="Επεξεργασία"><img src="<?php
			echo $xc_settings[ 'staticimagesurl' ];
			?>icons/icon_wand.gif" width="16" height="16" alt="Επεξεργασία" /></a>
			<a style="cursor: pointer;" onclick="Shoutbox.deleteShout( <?php
			echo $shout->Id();
			?> );return false;" href="" title="Διαγραφή">
			<img src="<?php
			echo $xc_settings[ 'staticimagesurl' ];
			?>icons/delete_sm.png" alt="Διαγραφή" /></a><?php
		}
		
		?></div>
		
		<div style="display: none;" id="shoutedit_<?php
		echo $shout->Id();
		?>"><?php
		echo htmlspecialchars( $shout->Text() );
		?></div>
		
		<div style="clear:left"></div>
		</div><?php
	}

?>
