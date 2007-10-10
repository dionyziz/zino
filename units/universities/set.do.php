<?php
	function UnitUniversitiesSet( tInteger $uniid ) {
		global $user;
		
		$user->SetUni( $uniid );
	}