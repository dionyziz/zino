<?php
	function ElementAdminNoPriviledge() {
		global $page;
		global $rabbit_settings;
		
		$page->SetTitle( $rabbit_settings[ 'applicationname' ] . " : Ελλειπή δικαιώματα" );
		?>Δεν έχετε τα απαραίτητα δικαιώματα για αυτή τη λειτουργεία<?php
	}
?>
