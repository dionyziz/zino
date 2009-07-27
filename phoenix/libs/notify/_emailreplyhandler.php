<?php
	function Notify_EmailReplyHandler( $body, $email ) {
		global $libs;
		$libs->Load( 'comment' );
		$libs->Load( 'wysiwyg' );
		
		$commentid = preg_grep( "\d+", $email );
		$commentid = $commentid[ 0 ];
		$hash = preg_grep( "(?<=-)[1-9a-f]+(?=@)", $email );
		
		$comment = New Comment( $commentid );
		
		if( substr( md5( 'beast' . $comment->Created . $comment->Id ), 0, 10 ) != $hash ) {
			return;
		}
		
		if( $comment->Parentid == 0 ) {
			switch( $comment->Typeid ) {
				case 1:
					$libs->Load( 'poll/poll' );
					$entity = New Poll( $comment->Itemid );
					$userid = $entity->Userid;
					break;
				case 2:
					$libs->Load( 'image/image' );
					$entity = New Image( $comment->Itemid );
					$userid = $entity->Userid;
					break;
				case 3:
					$libs->Load( 'user/user' );
					$entity = New User( $comment->Itemid );
					$userid = $entity->Userid;
					break;
				case 4:
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
		
		
		$pattern = "^.+\n?";
		$text = preg_replace( $pattern, '', $input );
        
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

?>