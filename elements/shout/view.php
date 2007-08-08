<?php

	function ElementShoutView( $shout ) {
		global $user;
		
		?><div id="shout_<?php
		echo $shout->Id(); 
		?>"><?php
		
		Element( 'user/icon' , $shout->User() );
		
		?><div id="shouttext_<?php 
        echo $shout->Id(); 
        ?>"><?php
		echo $shout->TextFormatted();
		
		if ( $user->CanModifyCategories() || ( $user->CanModifyStories() && $shout->UserId() == $user->Id() ) ) {
			?><a style="cursor: pointer;" onclick="Shoutbox.Edit( <?php
			echo $shout->Id();
			?> );return false;" href="" title="Επεξεργασία Μικρού Νέου"><img src="http://static.chit-chat.gr/images/icons/icon_wand.gif" width="16" height="16" alt="Επεξεργασία Μικρού Νέου" /></a>
			<a style="cursor: pointer;" onclick="Shoutbox.deleteShout( <?php
			echo $shout->Id();
			?> );return false;" href="" title="Διαγραφή Μικρού Νέου">
			<img src="http://static.chit-chat.gr/images/icons/delete_sm.png" alt="Διαγραφή Μικρού Νέου" /></a><?php
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
