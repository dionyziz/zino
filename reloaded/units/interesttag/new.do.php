<?php

    function UnitInterestTagNew( tString $text ) {
        global $libs;
        global $user;

		$libs->Load( 'interesttag' );

		$text = $text->Get();
		if ( !InterestTag_Valid( $text ) ) {
			return;
		}

        $tag = new InterestTag();
        $tag->User = $user;
        $tag->Text = $text;
        $tag->Save();
    }

?>
