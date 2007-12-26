<?php
	function ElementUserProfileView() {
		global $page;
		
		$page->AttachStyleSheet( 'css/user/profile/view.css' );
		?><div id="profile"><?php
			Element( 'user/profile/sidebar' );
			Element( 'user/profile/main' );
			?><div class="eof"></div>
		</div><?php
	}
?>