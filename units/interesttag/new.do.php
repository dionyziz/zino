<?php

    function UnitInterestTagNew( tString $text ) {
        global $libs;
        global $user;
        global $water;

		$libs->Load( 'interesttag' );

		$text = $text->Get();
		if ( !InterestTag_Valid( $text ) ) {
			$water->Trace( "Not valid text" );
			return;
		}

        $tag = new InterestTag();
        $tag->User = $user;
        $tag->Text = $text;
        $tag->Save();
    }

?>
