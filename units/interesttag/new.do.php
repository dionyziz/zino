<?php

    function UnitInterestTagNew( tString $text, tCoalaPointer $callback ) {
        global $libs;
        global $user;

		$text = $text->Get();
		if ( strlen( trim( $text ) ) == 0 || strpos( $text, ',' ) !== false || strpos( $text, ' ' ) !== false ) {
			return;
		}

        $libs->Load( 'interesttag' );

        $tag = new InterestTag();
        $tag->User = $user;
        $tag->Text = $text;
        $tag->Save();
        
        echo $callback;
        ?>( <?php
        echo w_json_encode( $text );
        ?> );<?php
    }

?>
