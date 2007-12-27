<?php
	function ElementCopyright() {
		global $user;
		global $rabbit_settings;
		
		?><div class="copy"><?php
		echo $rabbit_settings[ 'applicationname' ];
		?> <span>&copy; 2007 <a href="http://www.kamibu.com/">Kamibu</a></span></div><?php
	}
?>
