<?php
	
	function ElementCommentReply() {
		global $user;
		
		?><div class="comment newcomment">
			<div class="toolbox">
				<span class="time">τα σχόλια είναι επεξεργάσημα για ένα τέταρτο</span>
			</div>
			<div class="who">
				<a href="?p=user&amp;name=<?php
					echo $user->Subdomain;
					?>"><?php
					Element( 'user/avatar' , $user , 100 , 'avatar' , '' , true , 50 , 50 );
					echo $user->Name;
				?></a>πρόσθεσε ένα σχόλιο
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
