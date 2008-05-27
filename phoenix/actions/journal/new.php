<?php
	function ActionJournalNew( tInteger $id , tString $title , tString $text ) {
		global $user;
		global $libs;
        global $xhtmlsanitizer_goodtags;

        header( 'Content-type: text/plain' );

		$id = $id->Get();
		$title = $title->Get();
		$text = $text->Get();
		
		if ( $id > 0 ) {
			$journal = New Journal( $id );
			if ( $journal->User->Id != $user->Id ) {
                die( 'You can\'t edit this journal' );
				return;
			}
		}
		else {
            if ( !$user->Exists() ) {
                die( 'You must login first' );
                return;
            }
			$journal = New Journal();
		}
		$journal->Title = $title;

        $libs->Load( 'sanitizer' );

        $sanitizer = New XHTMLSanitizer();
        foreach ( $xhtmlsanitizer_goodtags as $tag => $attributes ) {
            if ( $tag == '' ) {
                continue;
            }

            $goodtag = New XHTMLSaneTag( $tag );
            if ( is_array( $attributes ) ) {
                foreach ( $attributes as $attribute => $true ) {
                    $goodtag->AllowAttribute( New XHTMLSaneAttribute( $attribute ) );
                }
            }
            foreach ( $xhtmlsanitizer_goodtags[ '' ] as $attribute => $true ) {
                $goodtag->AllowAttribute( New XHTMLSaneAttribute( $attribute ) );
            }
            $sanitizer->AllowTag( $goodtag );
        }
        $sanitizer->SetSource( $text );
		$journal->Text = $sanitizer->GetXHTML();

        global $water;

        header( 'Content-type: text/html' );
        ?><html><head><title></title></head><body><?php
        $water->DebugThis();

        die( '///' . $text . '///' . $journal->Text . '///' );

		$journal->Save();
		
		return Redirect( '?p=journal&id=' . $journal->Id );
	}
?>
