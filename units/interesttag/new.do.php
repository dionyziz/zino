<?php

    function UnitInterestTagNew( tString $text ) {
        global $libs;
        global $user;

		$text = $text->Get();
		if ( strlen( trim( $text ) ) == 0 ) {
			return;
		}

        $libs->Load( 'interesttag' );

        $tag = new InterestTag();
        $tag->User = $user;
        $tag->Text = $text;
        $tag->Save();
    }

?>
