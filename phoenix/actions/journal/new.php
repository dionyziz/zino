<?php
	function ActionJournalNew( tInteger $id , tString $title , tString $text ) {
		global $user;
		global $libs;
        global $xhtmlsanitizer_goodtags;

        header( 'Content-type: text/plain' );

		$id = $id->Get();
		$title = $title->Get();
		$text = $text->Get();
		
		if ( $id != 0 ) {
			$journal = New Journal( $id );
			if ( $journal->User->Id != $user->Id ) {
				return;
			}
		}
		else {
            if ( !$user->Exists() ) {
                return;
            }
			$journal = New Journal();
		}
		$journal->Title = $title;

        $libs->Load( 'sanitizer' );

        $sanitizer = New XHTMLSanitizer();
        foreach ( $xhtmlsanitizer_goodtags as $tag => $attributes ) {
            $goodtag = New XHTMLSaneTag( $tag );
            if ( is_array( $attributes ) ) {
                foreach ( $attributes as $attribute ) {
                    $goodtag->AllowAttribute( New XHTMLSaneAttribute( $attribute ) );
                }
            }
            $sanitizer->AllowTag( $goodtag );
        }
        $sanitizer->SetSource( $text );
		$journal->Text = $sanitizer->GetXHTML();

		$journal->Save();
		
		return Redirect( '?p=journal&id=' . $journal->Id );
	}
?>
