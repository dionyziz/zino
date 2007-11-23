<?php
	function UnitUniversitiesDelete( tInteger $uniid ) {
		global $user;
		global $libs;
		
		$libs->Load( 'universities' );
		if ( !$user->CanModifyCategories() ) {
			return;
		}
		
		$uni = new Uni( $uniid->Get() );
		$uni->Delete();
	}
?>