<?php
	function ElementTrackingAnalytics() {
		global $page;
		
		$page->AttachScript( 'http://www.google-analytics.com/urchin.js' );
	}
?>
