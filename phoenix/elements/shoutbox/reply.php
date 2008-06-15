<?php
	
	function ElementShoutboxReply() {
		global $user;
		
		?><div class="comment newcomment">
			<div class="who"><?php
				Element( 'user/display' , $user );
				?> πρόσθεσε ένα σχόλιο στη συζήτηση
			</div>
			<div class="text">
				<textarea rows="2" cols="50"></textarea>
			</div>
			<div class="bottom">
				<input type="submit" value="Σχολίασε!" />
			</div>
		</div><?php
	}
?>
