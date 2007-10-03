<?php

    function UnitInterestTagNew( tString $text ) {
        global $libs;
        global $user;

        $libs->Load( 'interesttag' );

        $tag = new InterestTag();
        $tag->User = $user;
        $tag->Text = $text->Get();
        $tag->Save();
    }

?>
