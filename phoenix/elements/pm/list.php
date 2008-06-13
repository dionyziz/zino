<?php

    function ElementPmList() {
        global $page;
        global $water;
        global $libs;
        global $user;

        if ( !$user->Exists() ) {
            Element( '404' );
            return;
        }

        $libs->Load( 'pm' );

		$page->SetTitle( 'Προσωπικά μηνύματα' );
        $page->AttachStyleSheet( 'css/pm.css' );
        $page->AttachStyleSheet( 'css/modal.css' );
    }

?>
