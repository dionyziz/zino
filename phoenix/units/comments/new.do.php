<?php
	function UnitCommentsNew( tString $text, tInteger $parent, tInteger $compage, tInteger $type, tInteger $indent, tCoalaPointer $node ) {
		global $libs;
		global $user;
		
		$libs->Load( 'comment' );
		
		$text = $text->Get();
		
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
		$comment->Text = $text;
		$comment->Userid = $user->Id;
		$comment->Typeid = $type;
		$comment->Itemid = $compage;
		$comment->Save();
		
		echo $node;
		?>.attr( 'id', 'comment_<?php
		echo $comment->Id;
		?>' );<?php
	}
?>
