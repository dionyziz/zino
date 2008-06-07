<?php
	function UnitCommentsNew( tString $text, tInteger $parent, tInteger $compage, tInteger $type, tCoalaPointer $node, tCoalaPointer $callback ) {
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
		?> );<?php
		
	}
?>
