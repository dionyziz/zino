<?php

    function ElementPmShowFolder( PMFolder $folder ) {
        $messages = $folder->PMs;

    	if ( empty( $messages ) ) {
    		?>Δεν υπάρχουν μηνύματα σε αυτόν τον φάκελο<?php
            return;
    	}

        foreach ( $messages as $msg ) {
            Element( 'pm/view', $msg, $folder );
        }
    }
