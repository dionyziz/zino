<?php
	function ElementCopyright() {
		global $user;
		
		?><div class="copy">
		Copyright &copy; Chit-Chat.gr<span>, Excalibur <?php
			if ( $user->IsSysOp() ) {
				?><a href="" onclick="Water.OpenWindow(); return false;">Phoenix</a><?php
			}
			else {
				?>Phoenix<?php
			}
		?>7.0</span>
		</div><?php
	}
?>
