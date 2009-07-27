<?php
	function Notify_EmailReplyHandler( $body, $email ) {
		global $libs;
        
		$libs->Load( 'comment' );
		$libs->Load( 'wysiwyg' );
		
		define( 'TYPE_POLL', 1 );
        define( 'TYPE_IMAGE', 2 );
        define( 'TYPE_USERPROFILE', 3 );
        define( 'TYPE_JOURNAL', 4 );
		
		$commentid = preg_grep( "\d+", $email );
		$commentid = $commentid[ 0 ];
		$hash = preg_grep( "(?<=-)[1-9a-f]+", $email );
		$hash = $hash[ 0 ];
		
		$comment = New Comment( $commentid );
		
		w_assert( $comment->Exists(), "Comment with id $commentid does not exist" );
		
		if ( substr( md5( 'beast' . $comment->Created . $comment->Id ), 0, 10 ) != $hash ) {
			return;
		}
		
		if ( $comment->Parentid == 0 ) {
			switch( $comment->Typeid ) {
				case TYPE_POLL:
					$libs->Load( 'poll/poll' );
					$entity = New Poll( $comment->Itemid );
					$userid = $entity->Userid;
					break;
				case TYPE_IMAGE:
					$libs->Load( 'image/image' );
					$entity = New Image( $comment->Itemid );
					$userid = $entity->Userid;
					break;
				case TYPE_USERPROFILE:
					$userid = $comment->Itemid;
					break;
				case TYPE_JOURNAL:
					$libs->Load( 'journal/journal' );	
					$entity = New Journal( $comment->Itemid );
					$userid = $entity->Userid;
					break;
			}
		}
		else {
			$parent = New Comment( $comment->Parentid );
			$userid = $parent->Userid;
		}
		
		
		$pattern = "^>.+\n?";
		$text = preg_replace( $pattern, '', $body );
        
        $comment = New Comment();
        $text = nl2br( htmlspecialchars( $text ) );
        $text = WYSIWYG_PostProcess( $text );
        $comment->Text = $text;
        $comment->Userid = $userid;
        $comment->Parentid = $commentid;
        $comment->Typeid = $comment->Typeid;
        $comment->Itemid = $commment->Itemid;
        $comment->Save();
	}

    function Notify_EmailReplyFilterRecipients( $to ) {
        file_put_contents( '/tmp/beast-to', $to );
        $rec = explode( ',', $to ); // multiple recipients separated using commas
        foreach ( $rec as $recipient ) {
            $recipient = trim( $recipient );
            if ( strpos( $recipient, '<' ) !== false ) { // Dionysis Zindros <dionyziz@zino.gr>
                $address = explode( '<', $recipient );
                $email = array_shift( explode( '>', $address[ 1 ] ) );
            }
            else { // dionyziz@zino.gr
                $email = $recipient;
            }
            $parts = explode( '@', $email, 2 );
            $name = $parts[ 0 ];
            $domain = $parts[ 1 ];
            if ( $domain == 'zino.gr' ) {
                return $name;
            }
        }
        return false;
    }
    
    function Notify_EmailReplyParse( $rawdata ) {
        $parts = explode( "\n\n", $rawdata, 2 );
        $header = $parts[ 0 ];
        $body = $parts[ 1 ];
        $lines = explode( "\n", $header );
        $conf = array();
        foreach ( $lines as $line ) {
            switch ( $line[ 0 ] ) {
                case ' ':
                case "\t":
                    // ignore
                    break;
                default:
                    $parts = explode( ": ", $line, 2 );
                    $key = strtolower( $parts[ 0 ] );
                    $value = trim( $parts[ 1 ] );
                    $conf[ $key ] = $value;
            }
        }
        
        if ( $conf[ 'content-transfer-encoding' ] == 'base64' ) {
            $body = base64_decode( $body );
        }
        $target = Notify_EmailReplyFilterRecipients( $conf[ 'to' ] );
        
        if ( $target !== false ) {
            file_put_contents( "/tmp/beast-body", $body );
            file_put_contents( "/tmp/beast-target", $target );
            return Notify_EmailReplyHandler( $body, $target );
        }
    }
?>
