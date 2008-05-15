<?php
	
	function ElementJournalNew( tInteger $id ) {
		global $user;
		//global $page;
		global $water;
		
		//$water->Trace( "id is: " . $id->Get() );
		Element( 'user/sections' , 'journal' , $user );
		?><div id="journalnew">
			<form method="post" action="do/journal/new">
				<div class="title">
					<span>Τίτλος:</span><input type="text" value="" name="title" />
				</div>
				<textarea cols="80" rows="20" name="text"></textarea>
				<div class="submit">
					<input type="submit" value="Δημιουργία" />
				</div>
			</form>
		</div>
		<div class="eof"></div><?php
	}
?>
