<?php
	function UnitUniversitiesCreate( tString $uniname , tInteger $typeid , tInteger $placeid ) {
		global $libs;
		global $water;
		global $user;
		
		$libs->Load( 'universities' );
		
		if ( !$user->CanModifyCategories() ) {
			return;
		}
		$uniname = $uniname->Get();
		$typeid = $typeid->Get();
		$placeid = $placeid->Get();
		
		$uni = new Uni();
		$uni->Name = $uniname;
		$uni->TypeId = $typeid;
		$uni->PlaceId = $placeid;
		$uni->Save();
	}
?>