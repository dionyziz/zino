<?php
    // Module inspired by "Building Scalable Web Sites', Cal Henderson, O'Reilly Press (page 86)
    // Developer: Dionyziz

    function Email_FormatSubject( $subject ) {
        if ( preg_match( "#^[a-z0-9 _-]*+$#i", $subject ) ) {
            // trivial case: subject is simple ASCII with no control characters
            return $subject;
        }
        $subject = preg_replace( "#([^a-z ])#ie", 'sprintf( "=%02x", ord( "\\1" ) )', $subject );
        $subject = str_replace( ' ', '_', $subject );

        return "=?utf-8?Q?$subject?=";
    }

    function Email( $toname, $toemail, $subject, $message, $fromname, $fromemail ) {
        global $libs;

        $libs->Load( 'rabbit/helpers/validate' );

        w_assert( preg_match( "#^[a-z0-9_.!\\/*() -]*+$#i", $toname ) );
        w_assert( preg_match( "#^[a-z0-9_.!\\/*() -]*+$#i", $fromname ) );
		w_assert( !empty( $toemail ), 'Recipient e-mail cannot be left empty' );
		w_assert( ValidEmail( $toemail ), 'Invalid recipient e-mail: ' . $toemail );
		w_assert( ValidEmail( $fromemail ) );

        $headers = "To: \"$toname\" <$toemail>\r\n"
                 . "From: \"$fromname\" <$fromemail>\r\n"
                 . "Reply-To: $fromemail\r\n"
                 . "Content-type: text/plain; charset=utf-8";
        
        $subject = Email_FormatSubject( $subject );
        mail( $toemail, $subject, $message, $headers );
    }
?>
