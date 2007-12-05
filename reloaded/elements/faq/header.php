<?php

	function ElementFaqHeader() {
		global $page;
		global $libs;
		
		$libs->Load( 'faq' );
		$libs->Load( 'search' );
		
		$page->AttachStylesheet( 'css/faq.css' );
		$page->AttachStylesheet( 'css/sidebar.css' );
		
		?><div style="margin-top:40px; margin-bottom: 20px;">
		Καλωσήρθες στις <a href="?p=faq">Συχνές Ερωτήσεις</a> του Zino.<br />
		Εδώ θα βρεις τις απαντήσεις στις ερωτήσεις σου!
		</div>
		<div class="faq"><?php
		
		Element( 'faq/search/box' );
		Element( 'faq/sidebar' );
	}

?>
