<?php
	
	function ElementSpaceEdit() {
		global $user;
		global $page;
		
		$page->SetTitle( 'Επεξεργασία χώρου' );
		Element( 'user/sections' , 'space' , $user );
		?><div id="editspace">
			<form method="post" action="do/space/edit" onsubmit="return Space.Edit();">
				<div class="wysiwyg" id="wysiwyg"><?php
					echo $user->Space->Text;
				?></div>
				<div class="submit">
					<input type="submit" value="Δημοσίευση" />
				</div>
			</form>
			<div class="eof"></div>
		</div><?php
	
	}
?>
