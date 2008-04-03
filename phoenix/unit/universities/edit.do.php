<?php
	function UnitUniversitiesEdit( tInteger $uniid , tString $uniname , tInteger $unitypeid , tInteger $uniplaceid ) {
		global $user;
		global $libs;
		
		$libs->Load( 'universities' );
		
		if ( !$user->CanModifyCategories() ) {
			return;
		}
		
		$uni = new Uni( $uniid->Get() );
		$uni->Name = $uniname->Get();
		$uni->TypeId = $unitypeid->Get();
		$uni->PlaceId = $uniplaceid->Get();
		$uni->Save();
	}
?>