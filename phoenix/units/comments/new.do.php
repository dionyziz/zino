<?php
	function UnitCommentsNew( tText $text, tInteger $parent, tInteger $compage, tInteger $type, tCoalaPointer $node, tCoalaPointer $callback ) {
		global $libs;
		global $user;
		
		$libs->Load( 'comment' );
		$libs->Load( 'wysiwyg' );
        
		$text = $text->Get();
		$text = trim( $text );
		
		if ( !$user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
			?>alert( "Δεν έχεις το δικαίωμα να δημιουργήσεις νέο σχόλιο. Παρακαλώ κάνε login" );<?php
			return;
		}
		
		if ( $text == '' ) {
			?>alert( "Δεν μπορείς να δημιουργήσεις κενό σχόλιο" );<?php
			return;
		}
		
		$parent = $parent->Get();
		$compage = $compage->Get();
		$type = $type->Get();
		
		$comment = New Comment();
		$comment->Text = WYSIWYG_PostProcess( htmlspecialchars( $text ) ); // TODO: WYSIWYG
		$comment->Userid = $user->Id;
		$comment->Parentid = $parent;
		$comment->Typeid = $type;
		$comment->Itemid = $compage;
		$comment->Save();
		
		echo $callback;
		?>( <?php
		echo $node;
		?>, <?php
		echo $comment->Id;
		?>, <?php
		echo $parent;
		?>, <?php
		echo nl2br( $comment->Text );
		?> );<?php
	}
?>
