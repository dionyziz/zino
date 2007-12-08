<?php
	function ElementCopyright() {
		global $user;
		global $rabbit_settings;
		
		?><div class="copy">
		Copyright &copy; <?php
		echo $rabbit_settings[ 'applicationname' ];
		?>.gr<span>, Excalibur <?php
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
