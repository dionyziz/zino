<?php
	function UnitInterestTagEdit( tString $old, tString $new ) {
		global $libs;
		global $user;
		
		$libs->Load( 'interesttag' );
		
		$old = $old->Get();
		$new = $new->Get();
		
		$tag = new InterestTag( $old );
		if ( !$tag->Exists() || !InterestTag_Valid( $new ) ) {
			return;
		}
		$tag->Text = $new;
		$tag->Save();
	}
?>
