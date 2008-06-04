<?php
	
	function ElementCommentReply() {
		global $user;
		global $page;
		
		$page->AttachScript( 'js/comments.js' );
		?><div class="comment newcomment">
			<div class="toolbox">
				<span class="time">τα σχόλια είναι επεξεργάσημα για ένα τέταρτο</span>
			</div>
			<div class="who"><?php
				Element( 'user/display' , $user );
				?> πρόσθεσε ένα σχόλιο
			</div>
			<div class="text">
				<textarea rows="" cols=""></textarea>
			</div>
			<div class="bottom">
				<input type="submit" value="Σχολίασε!" onclick="Comments.Create();" />
			</div>
		</div><?php
	}
?>
