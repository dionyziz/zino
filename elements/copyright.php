<?php
	function ElementCopyright() {
		global $user;
		
		?><div class="copy">
		Copyright &copy; Chit-Chat.gr<span>, Excalibur <?php
			if ( $user->IsSysOp() ) {
				?><a href="" onclick="Water.OpenWindow(); return false;">Reloaded</a><?php
			}
			else {
				?>Reloaded<?php
			}
		?> 6.4</span>
		</div><?php
	}
?>
