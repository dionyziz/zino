<?php
	
	function ElementJournalNew( tInteger $id ) {
		global $user;
		global $page;
		
		Element( 'user/sections' , 'journal' , $user );
		?><div id="journalnew">
			<span>Τίτλος:</span><input type="text" value="" />
			<textarea cols="50" rows="40"></textarea>
		</div>
		<div class="eof"></div><?php
	}
?>
