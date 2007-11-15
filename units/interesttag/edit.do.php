<?php
	function UnitInterestTagEdit( tString $old, tString $new ) {
		global $libs;
		global $user;
		
		$libs->Load( 'interesttag' );
		
		$old = $old->Get();
		$new = $new->Get();
		
		$tag = new InterestTag( $old );
		if ( !$tag->Exists() || strlen( trim( $new ) ) == 0 || strpos( $new, ',' ) !== false || strpos( $new, ' ' ) !== false ) {
			return;
		}
		$tag->Text = $new;
		$tag->Save();
	}
?>
