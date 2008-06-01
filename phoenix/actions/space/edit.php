<?php	
	function ActionSpaceEdit( tString $text ) {
		global $user;
		global $libs;
		
		if ( !$user->Exists() ) {
			die( "You must login first" );
			
		}
		$text = $text->Get();
	
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
		$result = $sanitizer->GetXHTML();
		
        $user->Space->Text = $result;
		$user->Space->Save();
		
		return Redirect( '?p=space&subdomain=' . $user->Subdomain );
	}
?>
