<?php
	function ActionAdminpanelBan( tText $username ) {
		global $libs;
		
		$username = $username->Get();
		
		$libs->Load( 'adminpanel/ban' );
		
		$ban = new Ban();
		$res = $ban->BanUser( $username );
		   
		return Redirect( '?p=banlist' );
	}
?>
