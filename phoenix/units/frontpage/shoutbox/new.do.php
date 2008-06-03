<?php

	function UnitFrontpageShoutbox( tString $text ) {
		global $user;
		global $libs;
		
		$libs->Load( 'shoutbox' );
		
		$text = $text->Get();
		if ( $user->Exists() ) {
			if ( $text != '' ) {
				$shout = New Shout();
				$shout->Text = $text;
				$shout->Save();
			}
		}
	}
?>
