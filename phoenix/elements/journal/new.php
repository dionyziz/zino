<?php
	
	function ElementJournalNew( tInteger $id ) {
		global $user;
		global $page;
		
		Element( 'user/sections' , 'journal' , $user );
		?><div id="journalnew">
			<input type="text" value="" />
			<textarea></textarea>
		</div><?php
	}
?>
