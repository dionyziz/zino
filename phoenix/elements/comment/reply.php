<?php
	
	function ElementCommentReply() {
		global $user;
		
		?><div class="comment newcomment">
			<div class="toolbox">
				<span class="time">τα σχόλια είναι επεξεργάσημα για ένα τέταρτο</span>
			</div>
			<div class="who"><?php
				Element( 'user/display' , $user );
				?> πρόσθεσε ένα σχόλιο
			</div>
			<div class="text">
				<textarea></textarea>
			</div>
			<div class="bottom">
				<input type="submit" value="Σχολίασε!" />
			</div>
		</div><?php
	}
?>
