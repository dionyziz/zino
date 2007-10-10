<?php
	function UnitUniversitiesDelete( tInteger $uniid ) {
		global $user;
		
		if ( !$user->CanModifyCategories() ) {
			return;
		}
		
		$uni = new Uni( $uniid );
		$uni->Delete();
	}
?>