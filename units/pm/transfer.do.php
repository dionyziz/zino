<?php
	function UnitPmTransfer( tInteger $pmid , tInteger $folderid ) {
		global $libs;
		global $water;
		global $user;
		
		$libs->Load( 'pm' );
		
		$pmid = $pmid->Get();
		$folderid = $folderid->Get();
		
		$pm = new PM( $pmid );
		?>alert( 'pmid is <?php echo $pmid; ?> and folderid is <?php echo $folderid; ?>' );<?php
		$pm->FolderId = $folderid;
		$pm->Save();
	}
?>