<?php
	
	function ElementSpaceEdit() {
		global $user;
		global $page;
		
		$page->SetTitle( 'Επεξεργασία χώρου' );
		Element( 'user/sections' , 'space' , $user );
		?><div id="editspace">
			<h2>Επεξεργασία χώρου</h2>
			<div class="edit">
				<form method="post" action="do/space/edit" onsubmit="return SpaceEdit.Edit();">
					<div class="wysiwyg" id="wysiwyg"><?php
						echo $user->Space->Text;
					?></div>
					<div class="submit">
						<input type="submit" value="Δημοσίευση" />
					</div>
				</form>
			</div>
			<div class="eof"></div>
		</div><?php
	
	}
?>
