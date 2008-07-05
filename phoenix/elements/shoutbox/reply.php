<?php
	
	function ElementShoutboxReply() {
		global $user;
		
		?><div class="comment newcomment">
			<div class="who"><?php
				Element( 'user/display' , $user );
				?>πρόσθεσε ένα σχόλιο στη συζήτηση
			</div>
			<div class="text">
				<textarea id="shoutbox_text" rows="2" cols="50" onkeyup="$( '#shoutbox_submit' )[ 0 ].disabled = ( $.trim( this.value ).length == 0 )"></textarea>
			</div>
			<div class="bottom">
				<input id="shoutbox_submit" type="submit" value="Σχολίασε!" disabled="disabled" />
			</div>
		</div><?php
	}
?>
